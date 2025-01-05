<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Reports;

use App\Models\Lesson;
use App\Models\Story;
use App\Models\StudentStoryTest;
use App\Models\UserTest;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\StoryUserRecord;
use App\Models\UserStoryAssignment;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentReport
{
    public $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function report()
    {
        $student = $this->student;

        $test_lessons = UserTest::query()->where('user_id', $student->id)->get();
        $passed_lessons = $test_lessons->where('status', 'Pass')->count();
        $failed_lessons = $test_lessons->where('status', 'Fail')->count();

        $test_stories = StudentStoryTest::query()->where('user_id', $student->id)->get();
        $passed_stories = $test_stories->where('status', 'Pass')->count();
        $failed_stories = $test_stories->where('status', 'Fail')->count();

        $assignment_lessons = UserAssignment::query()->where('user_id', $student->id)->get();
        $completed_lessons = $assignment_lessons->where('completed', 1)->count();
        $uncompleted_lessons = $assignment_lessons->where('completed', 0)->count();

        $assignment_stories = UserStoryAssignment::query()->where('user_id', $student->id)->get();
        $completed_stories = $assignment_stories->where('completed', 1)->count();
        $uncompleted_stories = $assignment_stories->where('completed', 0)->count();


        $stories_records = StoryUserRecord::query()->where('user_id', $student->id)->get();
        $pending_stories = $stories_records->where('status', 'pending')->count();
        $corrected_stories = $stories_records->where('status', 'corrected')->count();
        $returned_stories = $stories_records->where('status', 'returned')->count();

        $startDate = $student->created_at->format('Y-m-d');
        $endDate = now()->format('Y-m-d');
        $types = ['learn', 'practise', 'test', 'play'];
        // Generate a list of all months within the range
        $months = collect();
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);


        //check month of start date and end date
        while ($current->month  <= $end->month ) {
            $months->push($current->format('m/Y'));
            $current->addMonth();
        }

        // Prepare the query
        $data = collect($types)->flatMap(function ($type) use ($months) {
            return $months->map(function ($month) use ($type) {
                return [
                    'type' => $type,
                    'month_year' => $month,
                ];
            });
        })->map(function ($combination) {
            $monthYear = explode('/', $combination['month_year']);
            return [
                'type' => $combination['type'],
                'month_year' => $combination['month_year'],
                'year' => $monthYear[1],
                'month' => $monthYear[0],
            ];
        });


        $user_lessons_trackers = [];
        foreach ($data as $trackType)
        {
            foreach ($types as $type)
            {
                $user_lessons_trackers[$trackType['month_year']][$type] = UserTracker::query()
                    ->where('user_id', $student->id)
                    ->where('type', $type)
                    ->whereYear('created_at', $trackType['year'])
                    ->whereMonth('created_at', $trackType['month'])
                    ->has('lesson')
                    ->count();
            }
        }
        $types = ['watching', 'reading', 'test'];
        $data = collect($types)->flatMap(function ($type) use ($months) {
            return $months->map(function ($month) use ($type) {
                return [
                    'type' => $type,
                    'month_year' => $month,
                ];
            });
        })->map(function ($combination) {
            $monthYear = explode('/', $combination['month_year']);
            return [
                'type' => $combination['type'],
                'month_year' => $combination['month_year'],
                'year' => $monthYear[1],
                'month' => $monthYear[0],
            ];
        });



        $user_stories_trackers = [];
        foreach ($data as $trackType)
        {
            foreach ($types as $type)
            {
                $user_stories_trackers[$trackType['month_year']][$type] = UserTrackerStory::query()
                    ->where('user_id', $student->id)
                    ->where('type', $type)
                    ->whereYear('created_at', $trackType['year'])
                    ->whereMonth('created_at', $trackType['month'])
                    ->has('story')
                    ->count();
            }
        }

        $student_lessons = UserTracker::query()->where('user_id', $student->id)
            ->pluck('lesson_id')->unique()->values()->all();
        $user_tests = 0;
        $user_learning = 0;
        $user_training = 0;
        $user_tracker = 0;
        $lessons_info = [];
        $d_user_tracker = UserTracker::query()->where('user_id', $student->id)->filter()->get();
        foreach ($student_lessons as $lesson)
        {
            $lesson_info  = [];
            $user_tests = $d_user_tracker->where('type', 'test')->where('lesson_id', $lesson)->count();
            $user_learning = $d_user_tracker->where('type', 'learn')->where('lesson_id', $lesson)->count();
            $user_training = $d_user_tracker->where('type', 'practise')->where('lesson_id', $lesson)->count();

            $user_tracker = $d_user_tracker->where('lesson_id', $lesson)->count();
            if ($user_tracker) {
                $lesson_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $lesson_info['trainings'] = round(($user_training / $user_tracker) * 100, 1);
                $lesson_info['learnings'] = round(($user_learning / $user_tracker) * 100, 1);
                $lesson_info['tracker'] = $user_tracker;
            } else {
                $lesson_info['tests'] = 0;
                $lesson_info['trainings'] = 0;
                $lesson_info['learnings'] = 0;
                $lesson_info['tracker'] = 0;
            }

            $user_test = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->latest('total')->first();
            $lesson_info['user_test'] = $user_test;
            if (isset($user_test) && !is_null($user_test->start_at) && !is_null($user_test->end_at)) {
                $time1 = new \DateTime($user_test->start_at);
                $time2 = new \DateTime($user_test->end_at);
                $interval = $time1->diff($time2);

                $lesson_info['time_consumed'] = $interval->format('%i minute(s)');

            } else {
                $lesson_info['time_consumed'] = '-';
            }

            $user_lesson = UserLesson::query()->where('user_id', $student->id)->where('lesson_id', $lesson)->where('status', 'corrected')->first();
            $lesson_info['user_lesson'] = $user_lesson;

            $lesson_info['lesson'] = Lesson::query()->find($lesson);

            array_push($lessons_info, $lesson_info);
        }

        $lessons_info = array_chunk($lessons_info, 15);

        $student_stories = UserTrackerStory::query()->where('user_id', $student->id)
            ->pluck('story_id')->unique()->values()->all();

        $stories_info = [];
        $d_user_tracker = UserTrackerStory::query()->where('user_id', $student->id)->filter()->get();
        foreach ($student_stories as $story)
        {
            $story_info  = [];
            $user_watching = $d_user_tracker->where('type', 'watching')->where('story_id', $story)->count();
            $user_tests = $d_user_tracker->where('type', 'test')->where('story_id', $story)->count();
            $user_reading = $d_user_tracker->where('type', 'reading')->where('story_id', $story)->count();

            $user_tracker = $d_user_tracker->where('story_id', $story)->count();
            if ($user_tracker) {
                $story_info['tests'] = round(($user_tests / $user_tracker) * 100, 1);
                $story_info['watching'] = round(($user_watching / $user_tracker) * 100, 1);
                $story_info['reading'] = round(($user_reading / $user_tracker) * 100, 1);
                $story_info['tracker'] = $user_tracker;
            } else {
                $story_info['tests'] = 0;
                $story_info['watching'] = 0;
                $story_info['reading'] = 0;
                $story_info['tracker'] = 0;
            }

            $user_test = StudentStoryTest::query()->where('user_id', $student->id)->where('story_id', $story)->latest('total')->first();
            $story_info['user_test'] = $user_test;
            if (isset($user_test) && !is_null($user_test->start_at) && !is_null($user_test->end_at)) {
                $time1 = new \DateTime($user_test->start_at);
                $time2 = new \DateTime($user_test->end_at);
                $interval = $time1->diff($time2);

                $story_info['time_consumed'] = $interval->format('%i minute(s)');

            } else {
                $story_info['time_consumed'] = '-';
            }

            $story_info['story'] = Story::query()->find($story);

            array_push($stories_info, $story_info);
        }

        $stories_info = array_chunk($stories_info, 15);

//        dd($user_stories_trackers);


        return view('general.reports.user_report', [
            'student' => $student,
            'passed_lessons' => $passed_lessons,
            'failed_lessons' => $failed_lessons,
            'passed_stories' => $passed_stories,
            'failed_stories' => $failed_stories,
            'completed_lessons' => $completed_lessons,
            'uncompleted_lessons' => $uncompleted_lessons,
            'completed_stories' => $completed_stories,
            'uncompleted_stories' => $uncompleted_stories,
            'pending_stories' => $pending_stories,
            'corrected_stories' => $corrected_stories,
            'returned_stories' => $returned_stories,
            'user_lessons_trackers' => $user_lessons_trackers,
            'user_stories_trackers' => $user_stories_trackers,
            'lessons_info' => $lessons_info,
            'stories_info' => $stories_info,
        ]);

    }
}
