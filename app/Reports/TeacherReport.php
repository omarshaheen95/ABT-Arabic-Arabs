<?php

namespace App\Reports;

use App\Models\LessonAssignment;
use App\Models\StoryAssignment;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserStoryAssignment;
use App\Models\UserTest;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherReport
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function report()
    {
        $request    = $this->request;
        $guard      = getGuard();
        $guard_user = Auth::guard($guard)->user();

        $school    = $guard === 'school' ? $guard_user : $guard_user->school;
        $school_id = $school->id;

        $year       = $request->year_id ? Year::find($request->year_id) : null;
        $start_date = $request->get('start_date') ?: null;
        $end_date   = $request->get('end_date') ?: null;

        $teacher_ids = (array) $request->get('teacher_ids', ['all']);

        $teachers = Teacher::query()
            ->where('school_id', $school_id)
            ->when(!in_array('all', $teacher_ids), fn($q) => $q->whereIn('id', $teacher_ids))
            ->when($guard === 'supervisor', fn($q) => $q->whereHas('supervisor_teachers',
                fn($q) => $q->where('supervisor_id', $guard_user->id)))
            ->get();

        foreach ($teachers as $teacher) {
            // طلاب المعلم في السنة الدراسية المحددة
            $studentIds = User::query()
                ->where('school_id', $school_id)
                ->when($year, fn($q) => $q->where('year_id', $year->id))
                ->whereHas('teacherUser', fn($q) => $q->where('teacher_id', $teacher->id))
                ->pluck('id');

            $teacher->total_students = $studentIds->count();

            // اختبارات الدروس — UserTest
            $lessonTests = UserTest::whereIn('user_id', $studentIds)
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->get(['status']);
            $teacher->total_lesson_tests = $lessonTests->count();
            $teacher->pass_lesson_tests  = $lessonTests->where('status', 'Pass')->count();
            $teacher->fail_lesson_tests  = $lessonTests->where('status', 'Fail')->count();

            // اختبارات القصص — StudentStoryTest
            $storyTests = StudentStoryTest::whereIn('user_id', $studentIds)
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->get(['status']);
            $teacher->total_story_tests = $storyTests->count();
            $teacher->pass_story_tests  = $storyTests->where('status', 'Pass')->count();
            $teacher->fail_story_tests  = $storyTests->where('status', 'Fail')->count();

            // مهام الدروس بواسطة المعلم — LessonAssignment
            $teacher->total_lesson_assignments = LessonAssignment::where('teacher_id', $teacher->id)
                ->when($year,       fn($q) => $q->where('year_id', $year->id))
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->count();

            // مهام القصص بواسطة المعلم — StoryAssignment
            $teacher->total_story_assignments = StoryAssignment::where('teacher_id', $teacher->id)
                ->when($year,       fn($q) => $q->where('year_id', $year->id))
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->count();

            // واجبات الدروس المنجزة للطلاب — UserAssignment
            $lessonHw = UserAssignment::whereIn('user_id', $studentIds)
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->get(['completed']);
            $teacher->total_lesson_hw     = $lessonHw->count();
            $teacher->completed_lesson_hw = $lessonHw->where('completed', 1)->count();

            // واجبات القصص المنجزة للطلاب — UserStoryAssignment
            $storyHw = UserStoryAssignment::whereIn('user_id', $studentIds)
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->get(['completed']);
            $teacher->total_story_hw     = $storyHw->count();
            $teacher->completed_story_hw = $storyHw->where('completed', 1)->count();

            // تسجيلات القصص — StoryUserRecord
            $storyRecords = StoryUserRecord::whereIn('user_id', $studentIds)
                ->when($start_date, fn($q) => $q->whereDate('created_at', '>=', $start_date))
                ->when($end_date,   fn($q) => $q->whereDate('created_at', '<=', $end_date))
                ->get(['status']);
            $teacher->total_story_records     = $storyRecords->count();
            $teacher->corrected_story_records = $storyRecords->where('status', 'corrected')->count();
            $teacher->pending_story_records   = $storyRecords->where('status', 'pending')->count();
            $teacher->returned_story_records  = $storyRecords->where('status', 'returned')->count();
        }

        return view('general.reports.teacher_report.teacher_report',
            compact('teachers', 'school', 'year', 'start_date', 'end_date'));
    }
}
