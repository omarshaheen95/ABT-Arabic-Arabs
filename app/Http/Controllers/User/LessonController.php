<?php

namespace App\Http\Controllers\User;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\HiddenLesson;
use App\Models\Lesson;
use App\Models\MatchResult;
use App\Models\Option;
use App\Models\OptionResult;
use App\Models\Question;
use App\Models\SortResult;
use App\Models\SortWord;

use App\Models\SpeakingResult;
use App\Models\TQuestion;
use App\Models\TrueFalse;
use App\Models\TrueFalseResult;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\UserTest;
use App\Models\WritingResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Str;

class LessonController extends Controller
{

    public function lessonsLevels()
    {
        $user = Auth::guard('web')->user();
        if ($user->demo) {
            $grades = Grade::query()->whereIn('id', $user->demo_grades)->get();
            $alternate_grades = [];
            $grades_ids = $grades->pluck('id')->toArray();
        } else {
            $grades = Grade::query()->where('id', Auth::user()->grade_id)->get();
            $alternate_grades = Grade::query()->where('id', Auth::user()->alternate_grade_id)->get();
            $grades_ids = array_merge($grades->pluck('id')->toArray(), $alternate_grades->pluck('id')->toArray());
        }


        // Get hidden lessons for this user's school
        $hiddenLessonIds = HiddenLesson::query()
            ->where('school_id', $user->school_id)
            ->pluck('lesson_id')
            ->toArray();

        // Get completed lesson IDs for this user (approved = 1 and status = 'Pass')
        $completedLessonIds = UserTest::query()
            ->where('user_id', $user->id)
            ->where('approved', 1)
//            ->where('status', 'Pass')
            ->pluck('lesson_id')
            ->unique()
            ->toArray();

        $allGrades = Grade::with(['lessons' => function ($query) use ($hiddenLessonIds) {
                $query->whereNotIn('id', $hiddenLessonIds)->where('active', 1);
            }])
            ->withCount(['lessons' => function ($query) use ($hiddenLessonIds) {
                $query->whereNotIn('id', $hiddenLessonIds)->where('active', 1);
            }])
            ->whereIn('id', $grades_ids)
            ->get();

        // Calculate progress for each grade and each skill within the grade
        $allGrades = $allGrades->map(function ($grade) use ($completedLessonIds) {
            // Calculate overall grade progress
            $totalLessons = $grade->lessons->count();
            $completedLessons = $grade->lessons->whereIn('id', $completedLessonIds)->count();
            $grade->progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $grade->completed_lessons = $completedLessons;
            $grade->total_lessons = $totalLessons;

            // Calculate progress for each skill (lesson_type) within this grade
            $skillsProgress = [];
            if (isset($grade->grade_skills) && is_array($grade->grade_skills)) {
                foreach ($grade->grade_skills as $skillData) {
                    $skillType = $skillData['skill'];

                    // Get lessons for this specific skill type
                    $skillLessons = $grade->lessons->where('lesson_type', $skillType);
                    $totalSkillLessons = $skillLessons->count();
                    $completedSkillLessons = $skillLessons->whereIn('id', $completedLessonIds)->count();

                    $skillProgress = $totalSkillLessons > 0
                        ? round(($completedSkillLessons / $totalSkillLessons) * 100)
                        : 0;

                    $skillsProgress[$skillType] = [
                        'progress' => $skillProgress,
                        'completed' => $completedSkillLessons,
                        'total' => $totalSkillLessons,
                        'isCompleted' => $skillProgress == 100 && $totalSkillLessons > 0
                    ];
                }
            }

            $grade->skills_progress = $skillsProgress;
            return $grade;
        });

        // Get completed grade IDs (100% progress)
        $completedGradeIds = $allGrades->filter(function ($grade) {
            return $grade->progress == 100 && $grade->total_lessons > 0;
        })->pluck('id')->toArray();

        return view('user.lessons.levels', compact('allGrades', 'completedGradeIds', 'grades', 'alternate_grades'));
    }
    public function lessonsSubLevels($grade, $type)
    {
        if ($type == 'grammar') {
            $type_name = 'القواعد النحوية';
        } elseif ($type == 'dictation') {
            $type_name = 'الإملاء';
        } else {
            $type_name = 'البلاغة';
        }
        $title = 'مستويات الدروس -  ' . $type_name;
        $user = Auth::guard('web')->user();

        // Get completed lesson IDs for this user (approved = 1)
        $completedLessonIds = UserTest::query()
            ->where('user_id', $user->id)
            ->where('approved', 1)
            ->pluck('lesson_id')
            ->unique()
            ->toArray();

        if ($user->demo) {
            $grade = Grade::query()->findOrFail($grade);
            $gradeId = $grade->id;
        } else {
            $grade = Grade::query()->where('id', Auth::user()->grade_id)->first();
            $gradeId = Auth::user()->grade_id;
        }

        // Get all levels with lesson counts
        $levels = Lesson::query()
            ->whereNotNull('level')
            ->where('lesson_type', $type)
            ->where('grade_id', $gradeId)
            ->select('level', 'grade_id', \DB::raw('COUNT(*) as c'))
            ->groupBy('level', 'grade_id')
            ->havingRaw('c > 0')
            ->get();

        // Calculate progress for each level
        $levels = $levels->map(function ($level) use ($gradeId, $type, $completedLessonIds) {
            // Get all lessons for this level
            $levelLessons = Lesson::query()
                ->where('grade_id', $gradeId)
                ->where('lesson_type', $type)
                ->where('level', $level->level)
                ->pluck('id')
                ->toArray();

            $totalLessons = count($levelLessons);
            $completedLessons = count(array_intersect($levelLessons, $completedLessonIds));

            $level->progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
            $level->completed_lessons = $completedLessons;
            $level->total_lessons = $totalLessons;
            $level->isCompleted = $level->progress == 100 && $totalLessons > 0;

            return $level;
        })->values()->toArray();

        return view('user.lessons.sub-levels', compact('title', 'type', 'grade', 'levels'));
    }

    public function lessonsByLevel($id, $type,$level=null)
    {
        $user = Auth::guard('web')->user();

        $grade = Grade::query()->find($id);

        if (!$grade || !$user->demo && $user->grade_id != $grade->id && $user->alternate_grade_id != $grade->id && $user->id != 1) {
            return redirect()->route('home')->with('message', 'الدروس غير متاحة')->with('m-class', 'error');
        }

        $lessons = Lesson::query()
            ->whereDoesntHave('hiddenLessons',function ($query) use ($user){
                $query->where('school_id', $user->school_id);
            })
            ->where('lesson_type', $type)
            ->where('grade_id', $grade->id)
            ->when($level,function ($query) use ($level){
                $query->where('level', $level);
            })
            ->get();



        // Get completed lesson IDs for this user (approved = 1 and status = 'Pass')
        $completedLessonIds = UserTest::query()
            ->whereRelation('lesson', 'grade_id', $grade->id)
            ->where('user_id', $user->id)
            ->where('approved', 1)
//            ->where('status', 'Pass')
            ->pluck('lesson_id')
            ->toArray();

        return view('user.lessons.lessons_by_level', compact('grade','lessons', 'completedLessonIds'));
    }

    public function assignments()
    {
        $title = 'Assigned Homeworks';
        $student_assignments = UserAssignment::query()
            ->when(request()->has('assignment_id'), function ($query) {
                $query->where('id', request()->get('assignment_id'));
            })
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);

        $type = 'lesson';
        return view('user.assignments.index', compact('student_assignments', 'type','title'));
    }

    public function lesson($id, $key)
    {
        $user = Auth::guard('web')->user();

        $lesson = Lesson::with('grade')->whereDoesntHave('hiddenLessons',function ($query) use ($user){
            $query->where('school_id', $user->school_id);
        })->where('id',$id)->first();

        if (!$lesson || (!$user->demo && $user->grade_id != $lesson->grade_id && $user->alternate_grade_id != $lesson->grade_id && $user->id != 1)) {
            return redirect()->route('home')->with('message', 'Level not found')->with('m-class', 'error');
        }

        switch ($key) {
            case 'learn':
                return view('user.lessons.pages.learn', compact('lesson'));
            case 'training':
                $questions = TQuestion::with(['trueFalse','options','matches','sortWords'])->where('lesson_id', $id)->get();

                // Format questions data for JavaScript
                $quizData = [
                    'questions' => $questions->map(function($question, $index) {
                        $questionData = [
                            'id' => $question->id,
                            'type' => $this->getQuestionTypeName($question->type),
                            'content' => $question->content,
                            'attachment' => $question->attachment,
                        ];

                        // Add type-specific data
                        switch($question->type) {
                            case 1: // True/False
                                $questionData['correctAnswer'] = $question->trueFalse ? (bool)$question->trueFalse->result : false;
                                break;
                            case 2: // Multiple Choice
                                $correctOption = $question->options->firstWhere('result', 1);
                                $questionData['correctAnswer'] = $correctOption ? $correctOption->id : null;
                                $questionData['options'] = $question->options->map(function($option) {
                                    return [
                                        'id' => $option->id,
                                        'content' => $option->content,
                                        'image' => $option->image
                                    ];
                                })->values()->toArray();
                                break;
                            case 3: // Matching
                                $data = [];
                                $question->matches->each(function($match)use (&$data) {
                                    $data[$match->id] = [$match->uid];
                                });
                                $questionData['correctItems'] = $data;

                                break;
                            case 4: // Sort Words
                                $questionData['correctOrder'] = $question->sortWords->pluck('uid')->toArray();
                                break;
                        }

                        return $questionData;
                    })->values()->toArray(),
                    'totalQuestions' => $questions->count(),
                    'enableDragAndDrop' => true
                ];
                $type = 'training';
                return view('user.lessons.pages.training', compact('questions','type','lesson', 'quizData'));
            case 'test':
                $questions = Question::query()->where('lesson_id', $id)->get();

                if ($lesson->lesson_type == 'writing') {
                    // Get user's existing test and writing results
                    $student = Auth::user();
                    $existingTest = UserTest::with('lesson')
                        ->where('user_id', $student->id)
                        ->where('lesson_id', $id)
                        ->latest()
                        ->first();

                    $existingResults = [];
                    $isCorrected = false;
                    $totalScore = 0;
                    $maxScore = $lesson->success_mark; // Assuming 10 points per question

                    if ($existingTest) {
                        $isCorrected = $existingTest->corrected == 1;
                        $totalScore = $existingTest->total ?? 0;

                        $writingResults = WritingResult::query()
                            ->where('user_test_id', $existingTest->id)
                            ->get();

                        foreach ($writingResults as $result) {
                            $existingResults[$result->question_id] = [
                                'result' => $result->result,
                                'attachment' => $result->attachment,
                            ];
                        }
                    }

                    return view('user.lessons.pages.writing_test', compact('questions', 'lesson', 'existingResults', 'isCorrected', 'totalScore', 'maxScore', 'existingTest'));

                }
                if ($lesson->lesson_type == 'speaking') {
                    // Get user's existing test and speaking results
                    $student = Auth::user();
                    $existingTest = UserTest::query()
                        ->where('user_id', $student->id)
                        ->where('lesson_id', $id)
                        ->latest()
                        ->first();

                    $existingResults = [];
                    $isCorrected = false;
                    $totalScore = 0;
                    $maxScore = $lesson->success_mark;

                    if ($existingTest) {
                        $isCorrected = $existingTest->corrected == 1;
                        $totalScore = $existingTest->total ?? 0;

                        $existingResults = SpeakingResult::query()
                            ->where('user_test_id', $existingTest->id)
                            ->get()
                            ->keyBy('question_id')
                            ->toArray();
                    }

                    return view('user.lessons.pages.speaking_test', compact('questions', 'lesson', 'existingTest', 'existingResults', 'isCorrected', 'totalScore', 'maxScore'));

                }
                $questions->load(['trueFalse','options','matches','sortWords']);
                // Format questions data for JavaScript
                $quizData = [
                    'questions' => $questions->map(function($question, $index) {
                        $questionData = [
                            'id' => $question->id,
                            'type' => $this->getQuestionTypeName($question->type),
                            'content' => $question->content,
                            'attachment' => $question->attachment,
                        ];
                        return $questionData;
                    })->values()->toArray(),
                    'totalQuestions' => $questions->count(),
                    'enableDragAndDrop' => true,
                    'duration'=> 15, // in minutes
                ];
                $type = 'test';
                return view('user.lessons.pages.test', compact('questions', 'lesson','type','quizData'));

            default:
                return redirect()->route('home');
        }
    }

    public function trackLesson($id, $key)
    {
        $user =  Auth::user();
//        if ($user->demo){
////            return response()->json("(Demo)تمت العملية بنجاح",'200');
//        }
        $lesson = Lesson::query()->findOrFail($id);
        switch ($key)
        {
            case 'learn':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'learn',
                    'color' => 'warning',
                    'start_at' => now(),
                ]);
                break;
            case 'practise':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'practise',
                    'color' => 'primary',
                    'start_at' => now(),
                ]);
                break;
            case 'test':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'test',
                    'color' => 'danger',
                    'start_at' => now(),
                ]);
                break;
            case 'play':
                $user->user_tracker()->create([
                    'lesson_id' => $lesson->id,
                    'type' => 'play',
                    'color' => 'success',
                    'start_at' => now(),
                ]);
                break;
        }
        return $this->sendResponse(true);
    }
    public function saveLessonTest(Request $request, $id)
    {
        $student = Auth::user();
        $test = null;

        if ($student->demo) {
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

       $result =  DB::transaction(function () use ($request,$id,$student,&$test) {
            $questions = Question::with(['trueFalse', 'matches', 'sortWords', 'options'])->where('lesson_id', $id)->get();

            $test = UserTest::create([
                'user_id' => $student->id,
                'lesson_id' => $id,
                'corrected' => 1,
                'total' => 0,
            ]);

            $total = 0;

            // True/False Results and Total
            $tf_total = 0;
            $tf_data = [];
            foreach ($request->get('tf', []) as $key => $result) {
                $main_result = $questions->where('id', $key)->first()->trueFalse;
                $mark = optional($main_result)->result == $result ? $questions->where('id', $key)->first()->mark : 0;
                $tf_total += $mark;
                $tf_data[] = [
                    'question_id' => $key,
                    'result' => $result,
                    'user_test_id' => $test->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TrueFalseResult::insert($tf_data);
            $total += $tf_total;

            // Option Results and Total
            $o_total = 0;
            $o_data = [];
            foreach ($request->get('option', []) as $key => $option) {
                $main_result = $questions->where('id', $key)->first()->options->where('id',$option)->first();
                $mark = optional($main_result)->result == 1 ? $questions->where('id', $key)->first()->mark : 0;
                $o_total += $mark;

                $o_data[] = [
                    'question_id' => $key,
                    'option_id' => $option,
                    'user_test_id' => $test->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            OptionResult::insert($o_data);
            $total += $o_total;

            // Matching Results and Total
            $m_total = 0;
            $m_data = [];
            foreach ($request->get('matching', []) as $key => $match) {
                $matchMark = $questions->where('id', $key)->first()->mark / $questions->where('id', $key)->first()->matches->count();

                foreach ($match as $uid => $match_id) {
                    if (!is_null($match_id)) {
                        $result_id = $questions->where('id', $key)->first()->matches->where('uid', $uid)->first()->id;
                        $m_total += $match_id == $result_id ? $matchMark : 0;

                        $m_data[] = [
                            'question_id' => $key,
                            'match_id' => $match_id,
                            'result_id' => $result_id,
                            'user_test_id' => $test->id,
                            'match_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            MatchResult::insert($m_data);
            $total += $m_total;

            // Sorting Results and Total
            $s_total = 0;
            $s_data = [];
            foreach ($request->get('sorting', []) as $key => $sort) {
                $sort_words = $questions->where('id',$key)->first()->sortWords->pluck('uid')->toArray();
                $student_sort_words = collect($sort)->keys()->toArray();

                if ($student_sort_words === $sort_words) {
                    $mark = $questions->where('id',$key)->first()->mark;
                    $s_total += $mark;
                }

                foreach ($sort as $uid => $value) {
                    if (!is_null($value)) {
                        $result_id = $questions->where('id',$key)->first()->sortWords->where('uid', $uid)->first()->id;
                        $s_data[] = [
                            'question_id' => $key,
                            'sort_word_id' => $result_id,
                            'user_test_id' => $test->id,
                            'sort_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            SortResult::insert($s_data);
            $total += $s_total;

            // Update Test Total and Status
            $mark = $test->lesson->success_mark;


            $test->update([
                'approved' => 1,
                'total' => $total,
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
                'status' => $total >= $mark ? 'Pass' : 'Fail',
            ]);


            $student_tests = UserTest::query()
                ->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->orderByDesc('total')->get();


            if (optional($student_tests->first())->total >= $mark) {
                UserTest::query()->where('user_id', $student->id)
                    ->where('lesson_id', $id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                UserTest::query()->where('user_id', $student->id)
                    ->where('lesson_id', $id)
                    ->where('id', $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }


            $student->user_tracker()->create([
                'lesson_id' => $id,
                'type' => 'test',
                'color' => 'danger',
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
            ]);

            if ($test->user->teacherUser) {
                updateTeacherStatistics($test->user->teacherUser->teacher_id);
            }

            $user_assignment = UserAssignment::query()->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->where('test_assignment', 1)
                ->where('done_test_assignment', 0)
                ->first();

            if ($user_assignment) {
                $user_assignment->update([
                    'done_test_assignment' => 1,
                    'completed' => 1,
                ]);
            }

            // Calculate timing in minutes
            $start = \Carbon\Carbon::parse($request->get('start_at', now()));
            $end = \Carbon\Carbon::now();
            $timingMinutes = $start->diffInMinutes($end);

            $xpEarned = Constant::POINTS_LIST['test'];

            // Calculate percentage
            $maxTotal = $questions->sum('mark');
            $percentage = $maxTotal > 0 ? round(($total / $maxTotal) * 100) : 0;

            // Get the next lesson for this user and level
            $current_lesson = $test->lesson;
            $next_lesson = Lesson::query()
                ->where('grade_id', $current_lesson->grade_id)
                ->where('id', '>', $current_lesson->id)
                ->where('active', 1)
                ->first();

            $next_lesson_url = $next_lesson
                ? route('lesson.lesson-index', ['id' => $next_lesson->id, 'key' => 'learn'])
                : route('lesson.lessons-by-level', ['id' => $current_lesson->grade_id, 'type' => $current_lesson->lesson_type]);
            $certificate_url = route('certificate.get-certificate', ['id' => $test->id,'type' => 'lessons']);
            return view('user.lessons.pages.test_result', [
                'test' => $test,
                'total' => $total,
                'maxTotal' => $maxTotal,
                'percentage' => $percentage,
                'xpEarned' => $xpEarned,
                'timingMinutes' => $timingMinutes,
                'lesson' => $test->lesson,
                'level_mark' => $test->lesson->success_mark,
                'next_lesson_url' => $next_lesson_url,
                'certificate_url' => $certificate_url,
            ]);
        });
        return $result;

    }

    public function saveLessonWritingTest(Request $request, $id)
    {
        $student = Auth::user();
        if ($student->demo){
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        $test = UserTest::query()->create([
            'user_id' => $student->id,
            'lesson_id' => $id,
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
            'corrected' => 0,
            'total' => 0,
        ]);

        foreach ($request->get('writing_answer', []) as $key => $value)
        {
            if ($request->hasFile("writing_attachment.$key"))
            {
                $attachment = uploadFile($request->file("writing_attachment.$key"), 'writing_results')['path'];
            }else{
                $attachment = null;
            }
            WritingResult::query()->create([
                'user_test_id' => $test->id,
                'question_id' => $key,
                'result' => $value,
                'attachment' => $attachment,
            ]);
        }



        $student->user_tracker()->create([
            'lesson_id' => $id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
        ]);

        if ($test->user->teacherUser)
        {
            updateTeacherStatistics($test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
            ->where('lesson_id', $id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment)
        {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }

        return redirect()->route('lesson.lessons-by-level', ['id'=>$test->lesson->grade_id,'type' => 'writing'])->with('message', "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها")->with('m-type', 'success');
    }

    public function saveLessonSpeakingTest(Request $request, $id)
    {
        $student = Auth::user();
        if ($student->demo) {
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        $test = UserTest::query()->create([
            'user_id' => $student->id,
            'lesson_id' => $id,
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
            'corrected' => 0,
            'total' => 0,
        ]);

        if ($request->hasFile('record') && $request->get('question_id', false)) {
            // Create date-based directory structure
            $yearDir = date('Y');
            $monthDir = date('m');
            $dayDir = date('d');

            $relativePath = 'uploads/' . $yearDir . '/' . $monthDir . '/' . $dayDir . '/record_results';
            $fullPath = public_path($relativePath);

            // Create directory structure if it doesn't exist
            if (!File::isDirectory($fullPath)) {
                File::makeDirectory($fullPath, 0777, true, true);
            }

            $new_name = uniqid() . '.wav';
            $uploadedFile = $request->file('record');

            // Use Laravel's move method instead of move_uploaded_file
            if ($uploadedFile->move($fullPath, $new_name)) {
                $record = $relativePath . '/' . $new_name;

                SpeakingResult::query()->create([
                    'question_id' => $request->get('question_id'),
                    'user_test_id' => $test->id,
                    'attachment' => $record,
                ]);
            }
        }

        $student->user_tracker()->create([
            'lesson_id' => $id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
        ]);

        if ($test->user->teacherUser) {
            updateTeacherStatistics($test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
            ->where('lesson_id', $id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment) {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment) {
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }
        $url = route('lesson.lessons-by-level', ['id'=>$test->lesson->grade_id,'type' => 'speaking']);
        return $this->sendResponse(['redirect_url'=>$url], "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها");

    }


    /**
     * Get question type name for frontend
     */
    private function getQuestionTypeName($type)
    {
        switch($type) {
            case 1:
                return 'true-false';
            case 2:
                return 'multiple-choice';
            case 3:
                return 'matching';
            case 4:
                return 'sorting';
            default:
                return 'unknown';
        }
    }


}
