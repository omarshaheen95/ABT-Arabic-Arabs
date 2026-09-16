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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherReport
{
    private $request;

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

        // Compared against the raw datetime rather than DATE(created_at), so the
        // created_at index can actually be used.
        $from = $start_date ? Carbon::parse($start_date)->startOfDay() : null;
        $to   = $end_date ? Carbon::parse($end_date)->endOfDay() : null;

        $betweenDates = function ($q) use ($from, $to) {
            $q->when($from, function ($q) use ($from) {
                $q->where('created_at', '>=', $from);
            })->when($to, function ($q) use ($to) {
                $q->where('created_at', '<=', $to);
            });
        };

        $teacher_ids = (array) $request->get('teacher_ids', ['all']);

        // Archiving only blocks a student from logging in; the work they did is
        // still the teacher's output. Defaults to true so an older link without
        // the parameter keeps reporting everything.
        $include_archived = $request->boolean('include_archived', true);

        $teachers = Teacher::query()
            ->where('school_id', $school_id)
            ->when(!in_array('all', $teacher_ids), function ($q) use ($teacher_ids) {
                $q->whereIn('id', $teacher_ids);
            })
            ->when($guard === 'supervisor', function ($q) use ($guard_user) {
                $q->whereHas('supervisor_teachers', function ($q) use ($guard_user) {
                    $q->where('supervisor_id', $guard_user->id);
                });
            })
            ->get();

        foreach ($teachers as $teacher) {
            // طلاب المعلم في السنة الدراسية المحددة
            $studentIds = User::query()
                ->when($include_archived, function ($q) {
                    $q->withoutGlobalScope('not_archived');
                })
                ->where('school_id', $school_id)
                ->when($year, function ($q) use ($year) {
                    $q->where('year_id', $year->id);
                })
                ->whereHas('teacherUser', function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id);
                })
                ->pluck('id');

            $teacher->total_students = $studentIds->count();

            // اختبارات الدروس — UserTest
            $lessonTests = UserTest::whereIn('user_id', $studentIds)
                ->tap($betweenDates)
                ->get(['status', 'corrected', 'approved']);
            $teacher->total_lesson_tests = $lessonTests->count();
            $teacher->pass_lesson_tests  = $lessonTests->where('status', 'Pass')->count();
            $teacher->fail_lesson_tests  = $lessonTests->where('status', 'Fail')->count();
            $teacher->correction_required = $lessonTests->where('corrected', 0)->where('approved', 0)->count();


            // اختبارات القصص — StudentStoryTest
            $storyTests = StudentStoryTest::whereIn('user_id', $studentIds)
                ->tap($betweenDates)
                ->get(['status']);
            $teacher->total_story_tests = $storyTests->count();
            $teacher->pass_story_tests  = $storyTests->where('status', 'Pass')->count();
            $teacher->fail_story_tests  = $storyTests->where('status', 'Fail')->count();

            // مهام الدروس بواسطة المعلم — LessonAssignment
            $teacher->total_lesson_assignments = LessonAssignment::where('teacher_id', $teacher->id)
                ->when($year, function ($q) use ($year) {
                    $q->where('year_id', $year->id);
                })
                ->tap($betweenDates)
                ->count();

            // مهام القصص بواسطة المعلم — StoryAssignment
            $teacher->total_story_assignments = StoryAssignment::where('teacher_id', $teacher->id)
                ->when($year, function ($q) use ($year) {
                    $q->where('year_id', $year->id);
                })
                ->tap($betweenDates)
                ->count();

            // واجبات الدروس المنجزة للطلاب — UserAssignment
            $lessonHw = UserAssignment::whereIn('user_id', $studentIds)
                ->tap($betweenDates)
                ->get(['completed']);
            $teacher->total_lesson_hw     = $lessonHw->count();
            $teacher->completed_lesson_hw = $lessonHw->where('completed', 1)->count();

            // واجبات القصص المنجزة للطلاب — UserStoryAssignment
            $storyHw = UserStoryAssignment::whereIn('user_id', $studentIds)
                ->tap($betweenDates)
                ->get(['completed']);
            $teacher->total_story_hw     = $storyHw->count();
            $teacher->completed_story_hw = $storyHw->where('completed', 1)->count();

            // تسجيلات القصص — StoryUserRecord
            $storyRecords = StoryUserRecord::whereIn('user_id', $studentIds)
                ->tap($betweenDates)
                ->get(['status']);
            $teacher->total_story_records     = $storyRecords->count();
            $teacher->corrected_story_records = $storyRecords->where('status', 'corrected')->count();
            $teacher->pending_story_records   = $storyRecords->where('status', 'pending')->count();
            $teacher->returned_story_records  = $storyRecords->where('status', 'returned')->count();
        }

        return view('general.reports.teacher_report.teacher_report',
            compact('teachers', 'school', 'year', 'start_date', 'end_date', 'include_archived'));
    }
}
