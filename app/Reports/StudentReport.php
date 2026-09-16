<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Reports;

use App\Models\Lesson;
use App\Models\Story;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\UserStoryAssignment;
use App\Models\UserTest;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class StudentReport
{
    /** Rows per printed page. */
    const ROWS_PER_PAGE = 15;

    const LESSON_TRACK_TYPES = ['learn', 'practise', 'test'];

    const STORY_TRACK_TYPES = ['watching', 'reading', 'test'];

    public $student;

    public function __construct(User $student)
    {
        $this->student = $student;
    }

    public function report()
    {
        $student = $this->student;

        $test_lessons = UserTest::query()->where('user_id', $student->id)->get(['status']);
        $passed_lessons = $test_lessons->where('status', 'Pass')->count();
        $failed_lessons = $test_lessons->where('status', 'Fail')->count();

        $test_stories = StudentStoryTest::query()->where('user_id', $student->id)->get(['status']);
        $passed_stories = $test_stories->where('status', 'Pass')->count();
        $failed_stories = $test_stories->where('status', 'Fail')->count();

        $assignment_lessons = UserAssignment::query()->where('user_id', $student->id)->get(['completed']);
        $completed_lessons = $assignment_lessons->where('completed', 1)->count();
        $uncompleted_lessons = $assignment_lessons->where('completed', 0)->count();

        $assignment_stories = UserStoryAssignment::query()->where('user_id', $student->id)->get(['completed']);
        $completed_stories = $assignment_stories->where('completed', 1)->count();
        $uncompleted_stories = $assignment_stories->where('completed', 0)->count();

        $stories_records = StoryUserRecord::query()->where('user_id', $student->id)->get(['status']);
        $pending_stories = $stories_records->where('status', 'pending')->count();
        $corrected_stories = $stories_records->where('status', 'corrected')->count();
        $returned_stories = $stories_records->where('status', 'returned')->count();

        $months = $this->monthsSinceRegistration();

        $user_lessons_trackers = $this->monthlyTracks(
            UserTracker::query()->where('user_id', $student->id)->has('lesson'),
            $months,
            self::LESSON_TRACK_TYPES
        );

        $user_stories_trackers = $this->monthlyTracks(
            UserTrackerStory::query()->where('user_id', $student->id)->has('story'),
            $months,
            self::STORY_TRACK_TYPES
        );

        $lessons_info = array_chunk($this->lessonsInfo(), self::ROWS_PER_PAGE);
        $stories_info = array_chunk($this->storiesInfo(), self::ROWS_PER_PAGE);

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

    /**
     * Every month from the student's registration to today, as "mm/YYYY".
     *
     * The walk starts at the first of the month: stepping a month on from the
     * 31st lands on the 3rd of the month after next, which used to drop a month
     * out of the chart for anyone registered late in a month.
     */
    private function monthsSinceRegistration()
    {
        $months = [];
        $current = Carbon::parse($this->student->created_at)->startOfMonth();
        $end = now()->startOfMonth();

        while ($current <= $end) {
            $months[] = $current->format('m/Y');
            $current->addMonth();
        }

        return $months;
    }

    /**
     * Track counts per month and per type, in one grouped query.
     *
     * Every month of the range is present in the result, zero filled, because
     * the chart reads the months straight off the array keys.
     */
    private function monthlyTracks($query, array $months, array $types)
    {
        $rows = $query
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, type, COUNT(*) as aggregate')
            ->groupBy('year', 'month', 'type')
            ->toBase()
            ->get();

        $counts = [];
        foreach ($rows as $row) {
            $counts[sprintf('%02d/%d', $row->month, $row->year)][$row->type] = (int) $row->aggregate;
        }

        $tracks = [];
        foreach ($months as $month) {
            foreach ($types as $type) {
                $tracks[$month][$type] = isset($counts[$month][$type]) ? $counts[$month][$type] : 0;
            }
        }

        return $tracks;
    }

    /**
     * One row per lesson the student worked on.
     */
    private function lessonsInfo()
    {
        $student_id = $this->student->id;

        $lesson_ids = UserTracker::query()
            ->where('user_id', $student_id)
            ->pluck('lesson_id')
            ->unique()
            ->filter()
            ->values()
            ->all();

        if (empty($lesson_ids)) {
            return [];
        }

        $tracks = UserTracker::query()->where('user_id', $student_id)->filter()->get(['lesson_id', 'type']);
        $lessons = Lesson::query()->whereIn('id', $lesson_ids)->get()->keyBy('id');

        // Ascending on total, so keyBy leaves the best attempt per lesson.
        $best_tests = UserTest::query()
            ->where('user_id', $student_id)
            ->whereIn('lesson_id', $lesson_ids)
            ->orderBy('total')
            ->get()
            ->keyBy('lesson_id');

        $corrected = UserLesson::query()
            ->where('user_id', $student_id)
            ->whereIn('lesson_id', $lesson_ids)
            ->where('status', 'corrected')
            ->get()
            ->keyBy('lesson_id');

        $lessons_info = [];

        foreach ($lesson_ids as $lesson_id) {
            // A lesson removed after the student worked on it has nothing left
            // to show, and the page prints its name unguarded.
            if (!$lessons->has($lesson_id)) {
                continue;
            }

            $lesson_tracks = $tracks->where('lesson_id', $lesson_id);
            $user_test = $best_tests->get($lesson_id);

            $lessons_info[] = $this->rates($lesson_tracks, [
                'test' => 'tests',
                'practise' => 'trainings',
                'learn' => 'learnings',
            ]) + [
                'user_test' => $user_test,
                'time_consumed' => $this->timeConsumed($user_test),
                'user_lesson' => $corrected->get($lesson_id),
                'lesson' => $lessons->get($lesson_id),
            ];
        }

        return $lessons_info;
    }

    /**
     * One row per story the student worked on.
     */
    private function storiesInfo()
    {
        $student_id = $this->student->id;

        $story_ids = UserTrackerStory::query()
            ->where('user_id', $student_id)
            ->pluck('story_id')
            ->unique()
            ->filter()
            ->values()
            ->all();

        if (empty($story_ids)) {
            return [];
        }

        $tracks = UserTrackerStory::query()->where('user_id', $student_id)->filter()->get(['story_id', 'type']);
        $stories = Story::query()->whereIn('id', $story_ids)->get()->keyBy('id');

        $best_tests = StudentStoryTest::query()
            ->where('user_id', $student_id)
            ->whereIn('story_id', $story_ids)
            ->orderBy('total')
            ->get()
            ->keyBy('story_id');

        $stories_info = [];

        foreach ($story_ids as $story_id) {
            if (!$stories->has($story_id)) {
                continue;
            }

            $story_tracks = $tracks->where('story_id', $story_id);
            $user_test = $best_tests->get($story_id);

            $stories_info[] = $this->rates($story_tracks, [
                'test' => 'tests',
                'watching' => 'watching',
                'reading' => 'reading',
            ]) + [
                'user_test' => $user_test,
                'time_consumed' => $this->timeConsumed($user_test),
                'story' => $stories->get($story_id),
            ];
        }

        return $stories_info;
    }

    /**
     * Share of each track type for one lesson or story, as a percentage.
     *
     * @param array $types track type => key to report it under
     */
    private function rates($tracks, array $types)
    {
        $total = $tracks->count();
        $rates = ['tracker' => $total];

        foreach ($types as $type => $key) {
            $rates[$key] = $total ? round(($tracks->where('type', $type)->count() / $total) * 100, 1) : 0;
        }

        return $rates;
    }

    private function timeConsumed($test)
    {
        if (!$test || is_null($test->start_at) || is_null($test->end_at)) {
            return '-';
        }

        $start = new \DateTime($test->start_at);
        $end = new \DateTime($test->end_at);

        return $start->diff($end)->format('%i minute(s)');
    }
}
