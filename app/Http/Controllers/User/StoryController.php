<?php

namespace App\Http\Controllers\User;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryMatch;
use App\Models\StoryMatchResult;
use App\Models\StoryOption;
use App\Models\StoryOptionResult;
use App\Models\StoryQuestion;
use App\Models\StorySortResult;
use App\Models\StorySortWord;
use App\Models\StoryTrueFalse;
use App\Models\StoryTrueFalseResult;
use App\Models\StudentStoryTest;
use App\Models\UserRecord;
use App\Models\UserStoryAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class StoryController extends Controller
{
    public function storiesLevels()
    {
        $title = t('Stories');
        $user = Auth::guard('web')->user();
        if (Auth::guard('web')->user()->demo){
            $levelGrades = $user->demo_grades;
        }else{
            $levelGrades = [
                15, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
            ];
        }

        // Get completed story IDs for this user (approved = 1 and status = 'Pass')
        $completedStoryIds = StudentStoryTest::query()
            ->where('user_id', $user->id)
            ->where('approved', 1)
            ->where('status', 'Pass')
            ->pluck('story_id')
            ->unique()
            ->toArray();

        // Build levels with progress
        $levels = collect($levelGrades)->map(function ($grade) use ($user, $completedStoryIds) {
            // Get total stories for this level
            $totalStories = Story::query()
                ->whereDoesntHave('hidden_stories', function ($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                })
                ->where('grade', $grade)
                ->where('active', 1)
                ->count();

            // Get completed stories for this level
            $completedStories = Story::query()
                ->whereDoesntHave('hidden_stories', function ($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                })
                ->where('grade', $grade)
                ->where('active', 1)
                ->whereIn('id', $completedStoryIds)
                ->count();

            // Calculate progress percentage
            $progress = $totalStories > 0 ? round(($completedStories / $totalStories) * 100) : 0;

            return (object) [
                'id' => $grade,
                'grade' => $grade,
                'progress' => $progress,
                'completed_stories' => $completedStories,
                'total_stories' => $totalStories,
            ];
        });

        // Get completed level IDs (100% progress)
        $completed_levels = $levels->filter(function ($level) {
            return $level->progress == 100 && $level->total_stories > 0;
        })->pluck('id')->toArray();

        return view('user.stories.levels', compact('title', 'levels', 'completed_levels'));
    }

    public function storiesByLevel($level)
    {
        $title = t('Stories list');
        $user = Auth::guard('web')->user();
        $stories = Story::query()->whereDoesntHave('hidden_stories',function ($query) use ($user){
            $query->where('school_id', $user->school_id);
        })->where('grade', $level)->where('active', 1)->get();

        // Get completed story IDs for this user (approved = 1 and status = 'Pass')
        $completedStoryIds = StudentStoryTest::query()
            ->whereRelation('story', 'grade', $level)
            ->where('user_id', $user->id)
            ->where('approved', 1)
            ->where('status', 'Pass')
            ->pluck('story_id')
            ->unique()
            ->toArray();

        return view('user.stories.stories_by_level', compact('title', 'stories', 'level', 'completedStoryIds'));
    }

    public function story($id, $key)
    {
        $user = Auth::guard('web')->user();

        $story = Story::query()->whereDoesntHave('hidden_stories',function ($query) use ($user){
            $query->where('school_id', $user->school_id);
        })->where('id',$id)->first();

        if (!$story) {
            return redirect()->route('home')->with('message', 'القصة غير متاحة')->with('m-class', 'error');
        }

        switch ($key) {
            case 'watch':
                $story->load('media');
                return view('user.stories.pages.watch', compact('story'));
            case 'read':
                $user_story = UserRecord::query()->where('user_id', $user->id)->where('story_id', $story->id)->first();
                $users_story = UserRecord::query()
                    ->has('user')
                    ->where('user_id','<>', $user->id)
                    ->where('story_id', $story->id)->latest()
                    ->where('status', 'corrected')
                    ->where('approved', 1)
                    ->limit(10)
                    ->get();
                return view('user.stories.pages.read', compact('story', 'user_story', 'users_story'));
            case 'test':
                $questions = StoryQuestion::with(['trueFalse','options','matches','sort_words'])->where('story_id', $id)->get();
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
                return view('user.stories.pages.test', compact('questions', 'story','quizData'));
            default:
                return redirect()->route('home');
        }
    }

    public function saveReadRecordAnswer(Request $request, $id)
    {
        $story = Story::query()->findOrFail($id);
        $user = Auth::guard('web')->user();
        if ($user->demo){
            return response()->json("(Demo)تمت العملية بنجاح",'200');
        }
        $user_record = UserRecord::query()->where('user_id', $user->id)->where('story_id', $id)->first();
        if ($user_record) {
            if ($user_record->status == 'pending' || $user_record->status == 'returned') {
                if ($request->hasFile('record')) {
                    $new_name = uniqid() . '.' . 'wav';

                    $destination = public_path('uploads/record_result'.'/'.date("Y").'/'.date("m").'/'.date("d"));
                    File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);
                    move_uploaded_file($_FILES['record']['tmp_name'], $destination . '/' . $new_name);
                    $record = 'uploads' . DIRECTORY_SEPARATOR . 'record_result'.'/'.date("Y").'/'.date("m").'/'.date("d") . DIRECTORY_SEPARATOR . $new_name;
                    $user_record->update([
                        'record' => $record,
                        'status' => 'pending',
                    ]);
                    return $this->sendResponse($record, 'Record Saved Successfully');
                }
            }else{
                return $this->sendResponse(null, 'Your record cannot accept new updates');
            }
        } else {
            if ($request->hasFile('record')) {
                $new_name = uniqid() . '.' . 'wav';
                $destination = public_path('uploads/record_result'.'/'.date("Y").'/'.date("m").'/'.date("d"));
                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);
                move_uploaded_file($_FILES['record']['tmp_name'], $destination . '/' . $new_name);
                $record = 'uploads' . DIRECTORY_SEPARATOR . 'record_result'.'/'.date("Y").'/'.date("m").'/'.date("d") . DIRECTORY_SEPARATOR . $new_name;
                UserRecord::query()->create([
                    'user_id' => $user->id,
                    'story_id' => $id,
                    'record' => $record,
                ]);
                return $this->sendResponse($record, 'Record Saved Successfully');
            }
        }

        return $this->sendResponse([], 'Record Saved Successfully');
    }
    public function saveStoryTest(Request $request, $id)
    {
        $student = Auth::user();
        if ($student->demo){
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        $transaction = \DB::transaction(function () use ($request, $student,$id) {
            $questions = StoryQuestion::query()->where('story_id', $id)->get();

            $test = StudentStoryTest::query()->create([
                'user_id' => $student->id,
                'story_id' => $id,
                'corrected' => 1,
                'total' => 0,
            ]);

            foreach ($request->get('tf', []) as $key => $result)
            {
                StoryTrueFalseResult::create([
                    'user_id' => $student->id,
                    'story_question_id' => $key,
                    'result' => $result,
                    'student_story_test_id' => $test->id
                ]);
            }
            foreach ($request->get('option', []) as $key => $option)
            {
                StoryOptionResult::create([
                    'user_id' => $student->id,
                    'story_question_id' => $key,
                    'story_option_id' => $option,
                    'student_story_test_id' => $test->id
                ]);
            }
            $matching = $request->get('matching', []);
            $sorting = $request->get('sorting', []);

            foreach ($matching as $key => $match)
            {
                $match_answers = StoryMatch::query()->where('story_question_id', $key)->get();
                foreach ($match as $uid => $value)
                {
                    if (!is_null($value))
                    {
                        $result_id = $match_answers->where('uid', $uid)->first()->id;
                        StoryMatchResult::create([
                            'user_id' => $student->id,
                            'story_question_id' => $key,
                            'story_match_id' => $value,
                            'story_result_id' => $result_id,
                            'student_story_test_id' => $test->id,
                            'match_answer_uid' => $uid,
                        ]);
                    }
                }
            }
            foreach($sorting as $key => $sort)
            {
                $sort_words = StorySortWord::query()->where('story_question_id', $key)->get();
                foreach ($sort as $uid => $value)
                {
                    if (!is_null($value))
                    {
                        $result_id = $sort_words->where('uid', $uid)->first()->id;
                        StorySortResult::create([
                            'user_id' => $student->id,
                            'story_question_id' => $key,
                            'story_sort_word_id' => $result_id,
                            'student_story_test_id' => $test->id,
                            'story_sort_answer_uid' => $uid,
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
                    $student_result = StoryTrueFalseResult::query()->where('story_question_id', $question->id)->where('user_id', $student->id)
                        ->where('student_story_test_id', $test->id)->first();
                    $main_result = StoryTrueFalse::query()->where('story_question_id', $question->id)->first();
                    if(isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result){
                        $total += $question->mark;
                        $tf_total += $question->mark;
                    }
                }

                if ($question->type == 2)
                {
                    $student_result = StoryOptionResult::query()->where('story_question_id', $question->id)->where('user_id', $student->id)
                        ->where('student_story_test_id', $test->id)->first();
                    if($student_result)
                    {
                        $main_result = StoryOption::query()->find($student_result->story_option_id);
                    }

                    if(isset($student_result) && isset($main_result) && optional($main_result)->result == 1){
                        $total += $question->mark;
                        $o_total += $question->mark;
                    }

                }

                $match_mark = 0;
                if ($question->type == 3)
                {
                    $match_results = StoryMatchResult::query()->where('user_id', $student->id)->where('story_question_id', $question->id)
                        ->where('student_story_test_id', $test->id)->get();
                    $sub_mark = $question->mark / $question->matches()->count();
                    foreach ($match_results as $match_result)
                    {
                        $match_mark += $match_result->story_match_id == $match_result->story_result_id ? $sub_mark:0;
                    }
                    $total += $match_mark;
                    $m_total += $match_mark;
                }

                if ($question->type == 4)
                {
                    $sort_words = StorySortWord::query()->where('story_question_id', $question->id)->get()->pluck('id')->all();
                    $student_sort_words = StorySortResult::query()->where('story_question_id', $question->id)->where('user_id', $student->id)
                        ->where('student_story_test_id', $test->id)->get();
                    if (count($student_sort_words))
                    {
                        $student_sort_words = $student_sort_words->pluck('story_sort_word_id')->all();
                        if ($student_sort_words === $sort_words)
                        {
                            $total += $question->mark;
                            $s_total += $question->mark;
                        }

                    }
                }
            }

            $mark = 25;


            $test->update([
                'total' => $total,
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
                'status' => $total >= $mark ? 'Pass':'Fail',
            ]);



            $student_tests = StudentStoryTest::query()->where('total', '>=', $mark)
                ->where('user_id',  $student->id)
                ->where('total', '<=', $total)
                ->where('story_id', $id)->orderByDesc('total')->get();



            if (optional($student_tests->first())->total >= $mark)
            {
                StudentStoryTest::query()->where('user_id', $student->id)
                    ->where('story_id', $id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                StudentStoryTest::query()->where('user_id', $student->id)
                    ->where('story_id', $id)
                    ->where('id',  $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }

            $user_assignment = UserStoryAssignment::query()->where('user_id', $student->id)
                ->where('story_id', $id)
                ->where('test_assignment', 1)
                ->where('done_test_assignment', 0)
                ->first();

            if ($user_assignment)
            {
                $user_assignment->update([
                    'done_test_assignment' => 1,
                    'completed' => 1,
                    'completed_at' => now(),
                ]);
            }

            $student->user_tracker_story()->create([
                'story_id' => $id,
                'type' => 'test',
                'color' => 'danger',
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
            ]);

            // Calculate timing in minutes
            $start = \Carbon\Carbon::parse($request->get('start_at', now()));
            $end = \Carbon\Carbon::now();
            $timingMinutes = $start->diffInMinutes($end);

            $xpEarned = Constant::POINTS_LIST['test'];

            // Calculate percentage
            $maxTotal = $questions->sum('mark');
            $percentage = $maxTotal > 0 ? round(($total / $maxTotal) * 100) : 0;

            // Get the next story for this user and grade
            $current_story = $test->story;
            $next_story = Story::query()
                ->where('grade', $current_story->grade)
                ->where('id', '>', $current_story->id)
                ->where('active', 1)
                ->first();

            $next_story_url = $next_story
                ? route('story.story-index', ['id' => $next_story->id, 'key' => 'watch'])
                : route('story.stories-by-level', ['id' => $current_story->grade]);
            $certificate_url = route('certificate.get-certificate', ['id' => $test->id,'type' => 'stories']);
            return view('user.stories.pages.test_result', [
                'test' => $test,
                'total' => $total,
                'maxTotal' => $maxTotal,
                'percentage' => $percentage,
                'xpEarned' => $xpEarned,
                'timingMinutes' => $timingMinutes,
                'story' => $test->story,
                'level' => $test->story->grade,
                'next_story_url' => $next_story_url,
                'certificate_url' => $certificate_url,
            ]);
        });

        return $transaction;

    }

    public function trackStory($id, $key)
    {
        $user =  Auth::user();
        if ($user->demo){
            return response()->json("(Demo)تمت العملية بنجاح",'200');
        }
        $story = Story::query()->findOrFail($id);
        switch ($key)
        {
            case 'watching':
                $user->user_tracker_story()->create([
                    'story_id' => $story->id,
                    'type' => 'watching',
                    'color' => 'warning',
                    'start_at' => now(),
                ]);
                break;
            case 'reading':
                $user->user_tracker_story()->create([
                    'story_id' => $story->id,
                    'type' => 'reading',
                    'color' => 'primary',
                    'start_at' => now(),
                ]);
                break;
            case 'test':
                $user->user_tracker_story()->create([
                    'story_id' => $story->id,
                    'type' => 'test',
                    'color' => 'danger',
                    'start_at' => now(),
                ]);
                break;
        }
        return $this->sendResponse(true);
    }
    public function assignments()
    {
        $title = 'Assigned Stories Homeworks';
        $student_assignments = UserStoryAssignment::query()
            ->has('story')
            ->when(request()->has('assignment_id'), function ($query) {
                $query->where('id', request()->get('assignment_id'));
            })
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);
        $type = 'story';

        return view('user.assignments.index', compact('student_assignments', 'type','title'));
    }


    public function storyTestResult($id)
    {
        $title = w('Student story test result');
        $student = Auth::user();
        $student_test = StudentStoryTest::query()->where('user_id', $student->id)->findOrFail($id);
        $level = optional($student_test->story)->level;
        $story = $student_test->story;

        return view('user.story.story_test_result',compact('student_test', 'title', 'level', 'story'));
    }

    public function certificateAnswers($id)
    {
        $title = 'Student test answers';
        $student = Auth::user();
        $student_test = StudentStoryTest::query()->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');

        $questions = StoryQuestion::query()->where('story_id', $student_test->story_id)->get();

        return view('user.story.certificate_result',compact('student_test', 'title', 'questions'));
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
