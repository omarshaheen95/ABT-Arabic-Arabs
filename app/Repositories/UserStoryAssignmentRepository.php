<?php

namespace App\Repositories;


use App\Exports\StudentStoryAssignmentExport;
use App\Helpers\Response;
use App\Interfaces\UserStoryAssignmentRepositoryInterface;
use App\Models\Grade;
use App\Models\School;
use App\Models\Teacher;
use App\Models\UserStoryAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class UserStoryAssignmentRepository implements UserStoryAssignmentRepositoryInterface
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserStoryAssignment::query()->has('user')->with(['user.school','user.grade', 'user.teacher', 'story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {

                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex fw-bold">' . $row->user->name . '</div>';
                    $html .= '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>';
                    if (guardIs('manager')) {
                        $html .= '<div class="d-flex">' . $row->user->school->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '<span class="fw-bold text-primary pe-1 ms-2">' . t('Section') . ':</span>' . $row->user->section . '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('story', function ($row) {
                    $teacher = optional($row->user->teacher)->name ? optional($row->user->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . $row->story->name . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->story->grade_name . '</div>';
                    if (!guardIs('teacher')) {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Teacher') . ':</span>' . $teacher . '</div>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $test_status = $row->done_test_assignment ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $status = $row->completed ? '<span class="badge badge-primary">' . t('Completed') . '</span>' : '<span class="badge badge-danger">' . t('Uncompleted') . '</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Test') . ':</span>' . $test_status . '</span>' . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Status') . ':</span>' . $status . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('dates', function ($row) {
                    $deadline = $row->deadline?Carbon::parse($row->deadline)->format('Y-m-d'):'-';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Assigned in') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Deadline') . ':</span>' . $deadline . '</div>' .
                        '<div class="d-flex mt-1"><span class="fw-bold text-primary pe-1">' . t('Submit Status') . ':</span>' . $row->submit_status . '</div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        };
        $title = t('Users Stories Assignments');
        $grades = Grade::query()->get();
        $compact = compact('title','grades');
        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        } elseif (guardIn(['school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        } elseif (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.user_story_assignment.index', $compact);
    }


    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required|array']);
        UserStoryAssignment::destroy($request->get('row_id'));
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryAssignmentExport($request))->download('Students Stories assignments.xlsx');
    }


}
