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
            ->where('status', 'Pass')
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

    public function lessonsByLevel($id, $type)
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
            ->get();



        // Get completed lesson IDs for this user (approved = 1 and status = 'Pass')
        $completedLessonIds = UserTest::query()
            ->whereRelation('lesson', 'grade_id', $grade->id)
            ->where('user_id', $user->id)
            ->where('approved', 1)
            ->where('status', 'Pass')
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
                    $existingTest = UserTest::query()
                        ->where('user_id', $student->id)
                        ->where('lesson_id', $id)
                        ->latest()
                        ->first();

                    $existingResults = [];
                    $isCorrected = false;
                    $totalScore = 0;
                    $maxScore = count($questions) * 10; // Assuming 10 points per question

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

                    return view('user.lessons.pages.writing_test', compact('questions', 'lesson', 'existingResults', 'isCorrected', 'totalScore', 'maxScore'));

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
                    if ($existingTest) {
                        $existingResults = SpeakingResult::query()
                            ->where('user_test_id', $existingTest->id)
                            ->get()
                            ->keyBy('question_id')
                            ->toArray();
                    }

                    return view('user.lessons.pages.speaking_test', compact('questions', 'lesson', 'existingTest', 'existingResults'));

                }
                $questions->load(['trueFalse','options','matches','sort_words']);
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
        if ($student->demo){
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        $questions = Question::query()->where('lesson_id', $id)->get();

//        dump($request->get('tf', []));
//        dump($request->get('option', []));
//        dump($request->get('matching', []));
//        dd($request->get('sorting', []));
       $result = \DB::transaction(function () use ($request, $questions, $student,$id) {
           $test = StudentTest::query()->create([
               'user_id' => $student->id,
               'lesson_id' => $id,
               'corrected' => 1,
               'total' => 0,
           ]);

           foreach ($request->get('tf', []) as $key => $result)
           {
               TrueFalseResult::create([
                   'user_id' => $student->id,
                   'question_id' => $key,
                   'result' => $result,
                   'student_test_id' => $test->id
               ]);
           }
           foreach ($request->get('option', []) as $key => $option)
           {
               OptionResult::create([
                   'user_id' => $student->id,
                   'question_id' => $key,
                   'option_id' => $option,
                   'student_test_id' => $test->id
               ]);
           }
           $matching = $request->get('matching', []);
           $sorting = $request->get('sorting', []);

           foreach ($matching as $key => $match)
           {
               $match_answers = Match::query()->where('question_id', $key)->get();
               foreach ($match as $uid => $value)
               {
                   if (!is_null($value))
                   {
                       $result_id = $match_answers->where('uid', $uid)->first()->id;
                       MatchResult::create([
                           'user_id' => $student->id,
                           'question_id' => $key,
                           'match_id' => $value,
                           'result_id' => $result_id,
                           'student_test_id' => $test->id,
                           'match_answer_uid' => $uid,
                       ]);
                   }
               }
           }

           foreach($sorting as $key => $sort)
           {
               $sort_words = SortWord::query()->where('question_id', $key)->get();
               foreach ($sort as $uid => $value)
               {
                   if (!is_null($value))
                   {
                       $result_id = $sort_words->where('uid', $uid)->first()->id;
                       SortResult::create([
                           'user_id' => $student->id,
                           'question_id' => $key,
                           'sort_word_id' => $result_id,
                           'student_test_id' => $test->id,
                           'sort_answer_uid' => $uid,
                       ]);
                   }
               }
           }


           $total = 0;
           $tf_total = 0;
           $o_total = 0;
           $m_total = 0;
           $s_total = 0;

           foreach ($questions as $question)
           {
               if ($question->type == 1)
               {
                   $student_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_id', $student->id)
                       ->where('student_test_id', $test->id)->first();
                   $main_result = TrueFalse::query()->where('question_id', $question->id)->first();
                   if(isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result){
                       $total += $question->mark;
                       $tf_total += $question->mark;
                   }
               }

               if ($question->type == 2)
               {
                   $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_id', $student->id)
                       ->where('student_test_id', $test->id)->first();
                   if($student_result)
                   {
                       $main_result = Option::query()->find($student_result->option_id);
                   }

                   if(isset($student_result) && isset($main_result) && optional($main_result)->result == 1){
                       $total += $question->mark;
                       $o_total += $question->mark;
                   }

               }

               $match_mark = 0;
               if ($question->type == 3)
               {
                   $match_results = MatchResult::query()->where('user_id', $student->id)->where('question_id', $question->id)
                       ->where('student_test_id', $test->id)->get();
                   foreach ($match_results as $match_result)
                   {
                       $match_mark += $match_result->match_id == $match_result->result_id ? 2:0;
                   }
                   $total += $match_mark;
                   $m_total += $match_mark;
               }

               if ($question->type == 4)
               {
                   $sort_words = SortWord::query()->where('question_id', $question->id)->get()->pluck('id')->all();
                   $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_id', $student->id)
                       ->where('student_test_id', $test->id)->get();
                   if (count($student_sort_words))
                   {
                       $student_sort_words = $student_sort_words->pluck('sort_word_id')->all();
                       if ($student_sort_words === $sort_words)
                       {
                           $total += $question->mark;
                           $s_total += $question->mark;
                       }

                   }
               }
           }

           $mark = $test->lesson->level->level_mark;


           $test->update([
               'total' => $total,
               'start_at' => $request->get('start_at', now()),
               'end_at' => now(),
               'status' => $total >= $mark ? 'Pass':'Fail',
           ]);

           $student_tests = StudentTest::query()->where('total', '>=', $mark)
               ->where('user_id',  $student->id)
               ->where('total', '<=', $total)
               ->where('lesson_id', $id)->orderByDesc('total')->get();



           if (optional($student_tests->first())->total >= $mark)
           {
               StudentTest::query()->where('user_id', $student->id)
                   ->where('lesson_id', $id)
                   ->where('id', '<>', $student_tests->first()->id)->update([
                       'approved' => 0,
                   ]);
               StudentTest::query()->where('user_id', $student->id)
                   ->where('lesson_id', $id)
                   ->where('id',  $student_tests->first()->id)->update([
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

           if ($test->user->teacher)
           {
               updateTeacherStatistics($test->user->teacher->id);
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
                       'completed_at' => now(),
                       'completed' => 1,
                   ]);
               }
           }
//        dd($total);

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
               ->where('level_id', $current_lesson->level_id)
               ->where('id', '>', $current_lesson->id)
               ->where('active', 1)
               ->first();

           $next_lesson_url = $next_lesson
               ? route('lesson.lesson-index', ['id' => $next_lesson->id, 'key' => 'learn'])
               : route('lesson.lessons-by-level', ['id' => $current_lesson->level->id]);
           $certificate_url = route('certificate.get-certificate', ['id' => $test->id,'type' => 'lessons']);
           return view('user.lessons.pages.test_result', [
               'test' => $test,
               'total' => $total,
               'maxTotal' => $maxTotal,
               'percentage' => $percentage,
               'xpEarned' => $xpEarned,
               'timingMinutes' => $timingMinutes,
               'lesson' => $test->lesson,
               'level' => $test->lesson->level,
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

    public function lessonTestResult($id)
    {
        $title = w('Student test result');
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->findOrFail($id);
        $level = optional($student_test->lesson)->grade;
        $lesson = $student_test->lesson;

        return view('user.lesson.lesson_test_result',compact('student_test', 'title', 'level', 'lesson'));
    }


//    public function saveUserLearnAnswers(Request $request, $id)
//    {
//        $user = Auth::user();
//        if ($user->demo){
//            return response()->json("(Demo)تمت العملية بنجاح",'200');
//        }
//        $user_lesson = UserLesson::query()->updateOrCreate([
//            'user_id' => $user->id,
//            'lesson_id' => $id,
//        ],[
//            'user_id' => $user->id,
//            'lesson_id' => $id,
//            'status' => 'pending',
//        ]);
//        $record = null;
//
//        if($request->hasFile('record_file')){
//            $record = uploadFile($request->file('record_file'), 'record_result')['path'];
//        }else if(isset($_FILES['record1']) && $_FILES['record1']['type'] != 'text/plain' && $_FILES['record1']['error'] <= 0){
//            $new_name = uniqid().'.'.'wav';
////            $destination = public_path('uploads/record_result');
//            $destination = public_path('uploads/record_result'.'/'.date("Y").'/'.date("m").'/'.date("d"));
//            File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);
//            move_uploaded_file($_FILES['record1']['tmp_name'], $destination .'/'. $new_name);
////            $record = 'uploads'.DIRECTORY_SEPARATOR.'record_result'.DIRECTORY_SEPARATOR.$new_name;
//            $record = 'uploads' . DIRECTORY_SEPARATOR . 'record_result'.'/'.date("Y").'/'.date("m").'/'.date("d") . DIRECTORY_SEPARATOR . $new_name;
//
//        }else{
//            $record = $user_lesson->getOriginal('reading_answer');
//        }
//
//
//        if($request->hasFile('writing_attachment')){
//            $writing_attachment_file = uploadFile($request->file('writing_attachment'), 'writing_attachments')['path'];
//        }else{
//            $writing_attachment_file = $user_lesson->getOriginal('attach_writing_answer');
//        }
//
//        $user_lesson->writing_answer = $request->get('writing_answer', null) ;
//        $user_lesson->attach_writing_answer = $writing_attachment_file ;
//        $user_lesson->reading_answer = $record ;
//        $user_lesson->submitted_at = now() ;
//
//        $user_lesson->save();
//
//        if ($user_lesson->user->teacher_student)
//        {
//            updateTeacherStatistics($user_lesson->user->teacher_student->teacher_id);
//        }
//
//        $user_assignment = UserAssignment::query()->where('user_id', $user->id)
//            ->where('lesson_id', $id)
//            ->where('tasks_assignment', 1)
//            ->where('done_tasks_assignment', 0)
//            ->first();
//
//        if ($user_assignment)
//        {
//            $user_assignment->update([
//                'done_tasks_assignment' => 1,
//            ]);
//
//            if (($user_assignment->test_assignment && $user_assignment->done_test_assignment) || !$user_assignment->test_assignment){
//                $user_assignment->update([
//                    'completed_at' => now(),
//                    'completed' => 1,
//                ]);
//            }
//        }
//
//
//        return response()->json('saved - تم الحفظ','200');
//
//    }

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
