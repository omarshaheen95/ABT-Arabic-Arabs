<?php

namespace App\Http\Controllers\General;

use App\Exports\StudentInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\UserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Grade;
use App\Models\ImportFile;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\School;
use App\Models\StudentStoryTest;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserTracker;
use App\Models\UserTrackerStory;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

        $this->middleware('permission:show users')->only('index');
        $this->middleware(['permission:show users'])->only('myStudents');
        $this->middleware('permission:add users')->only(['create', 'store']);
        $this->middleware('permission:edit users')->only(['edit', 'update']);
        $this->middleware('permission:delete users')->only('destroy');
        $this->middleware('permission:export users')->only('exportStudentsExcel');
        $this->middleware('permission:review users')->only('review');
        $this->middleware('permission:users story review')->only('storyReview');
        $this->middleware('permission:users login')->only('userLogin');
        $this->middleware('permission:restore deleted users')->only('restoreUser');
        $this->middleware('permission:assign users')->only('assignedToTeacher');
        $this->middleware('permission:unassign users')->only('deleteTeacherUser');
        $this->middleware('permission:users activation')->only('userActivation');
        $this->middleware('permission:update users grade')->only('updateGrades');
        $this->middleware('permission:reset users passwords')->only('resetPasswords');

    }

    public function index(Request $request)
    {
        return $this->userRepository->index($request);
    }

    public function create()
    {
        return $this->userRepository->create();
    }

    public function store(UserRequest $request)
    {
        return $this->userRepository->store($request);
    }

    public function edit(Request $request, $id)
    {
        return $this->userRepository->edit($request, $id);
    }

    public function update(UserRequest $request, $id)
    {
        return $this->userRepository->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->userRepository->destroy($request);
    }


    public function export(Request $request)
    {
        return $this->userRepository->export($request);
    }

    public function lessonReview(Request $request, $id)
    {
        return $this->userRepository->lessonReview($request, $id);

    }

    public function storyReview(Request $request, $id)
    {
        return $this->userRepository->storyReview($request, $id);

    }

    public function report(Request $request, $id)
    {
        return $this->userRepository->report($request, $id);
    }

    public function cards(Request $request)
    {
        return $this->userRepository->cards($request);
    }

    public function login($id)
    {
        return $this->userRepository->login($id);
    }

    public function userActivation(Request $request)
    {
        return $this->userRepository->userActivation($request);
    }

    public function updateGrades(Request $request)
    {
        return $this->userRepository->updateGrades($request);
    }

    public function assignedToTeacher(Request $request)
    {
        return $this->userRepository->assignedToTeacher($request);
    }
    public function unassignedUserTeacher(Request $request)
    {
        return $this->userRepository->unassignedUserTeacher($request);
    }

    public function restoreUser($id)
    {
        return $this->userRepository->restoreUser($id);
    }

    public function userCard(Request $request, $id)
    {
        return $this->userRepository->userCard($request, $id);
    }


    //just for teacher
    public function myStudents(Request $request)
    {
        if (getGuard() !='teacher') {
            throw new UnauthorizedException('You are not authorized to access this page.');
        }
        if (request()->ajax()) {
            $rows = User::query()->with(['package','year','grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('school', function ($row) {
                    $package = optional($row->package)->name;
                    $gender = !is_null($row->gender) ? $row->gender : '<span class="text-danger">-</span>';
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';
                    $html = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Section') . ' </span> : ' . '<span> ' . $section . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('active_to', function ($row) {
                    return is_null($row->active_to) ? 'unpaid' : optional($row->active_to)->format('Y-m-d');
                })
                ->addColumn('package', function ($row) {
                    return optional($row->package)->name;
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="' . $row->email . '" onclick="copyToClipboard(this)">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name .'</span></div></div>';
                    return $student;
                })
                ->addColumn('dates', function ($row) {
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->last_login ? Carbon::parse($row->last_login)->format('Y-m-d H:i') : '';
                    if ($row->active == 0) {
                        $status = '<span class="text-danger">' . t('Suspend') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') <= now()) {
                        $status = '<span class="text-danger">' . t('Expired') . '</span>';
                    } elseif ($row->active == 1 && !is_null($row->active_to) && optional($row->active_to)->format('Y-m-d') > now()) {
                        $status = '<span class="text-success">' . t('Active') . '</span>';
                    } else {
                        $status = '<span class="text-warning">' . t('Unknown') . '</span>';
                    }

                    if ($row->active_to) {
                        $active_to = optional($row->active_to)->format('Y-m-d') <= now() ? '<span class="text-danger">' . optional($row->active_to)->format('Y-m-d') . '</span>' : '<span class="text-success">' . optional($row->active_to)->format('Y-m-d') . '</span>';
                    }
                    $data = '<div class="d-flex flex-column">' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Register Date') . '</span> : ' . $register_date . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Active To') . '</span> : ' . $active_to . '-' . $status . '</div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Last Login') . '</span> : ' . $last_login . '</div>' .
                        '</div>';
                    return $data;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('My Students');
        $grades = Grade::all();
        $packages = Package::query()->where('active', 1)->get();
        return view('general.user.teacher_students', compact('title', 'packages','grades'));
    }

    public function resetPasswords(Request $request)
    {
        return $this->userRepository->resetPasswords($request);
    }

    public function pdfReports(Request $request)
    {
        return $this->userRepository->pdfReports($request);
    }
}
