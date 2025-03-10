<?php

namespace App\Repositories;

use App\Classes\GeneralFunctions;
use App\Exports\StudentInformation;
use App\Helpers\Response;
use App\Http\Requests\General\UserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Mail\AddNewStudentMail;
use App\Models\Grade;
use App\Models\Package;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherUser;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


class UserRepository implements UserRepositoryInterface
{

    public function index(Request $request)
    {
        if (guardIs('teacher')){
            //to get all students for teacher school in [teacher]
            unset($request['teacher_id']);
        }
        if (request()->ajax()) {
            $rows = User::query()->with(['school', 'package', 'teacher', 'year','grade','login_sessions'])->filter($request)->latest();

            $datatable =  DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('student', function ($row) {
                    $section = !is_null($row->section) ? $row->section : '<span class="text-danger">-</span>';

                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->name . '</div>' .
                        '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="'.$row->email.'" onclick="copyToClipboard(this)">' . $row->email . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold ">' . $row->grade->name .'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold ">' . t('Section') . '</span> : ' . $section . '</div></div>';
                    return $student;
                })
                ->addColumn('school', function ($row) {
                    $school = optional($row->school)->name;
                    $teacher = optional($row->teacher)->name ? optional($row->teacher)->name : '<span class="text-danger">' . t('Unsigned') . '</span>';
                    $package = optional($row->package)->name;
                    $gender = !is_null($row->gender) ? $row->gender : '<span class="text-danger">-</span>';
                    $html = '<div class="d-flex flex-column">';
                    if (guardIs('manager')){
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('School') . ' </span> : ' . '<span> ' . $school . '</span></div>';
                    }
                    if (guardNotIs('teacher')){
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Teacher') . ' </span> : ' . '<span> ' . $teacher . '</span></div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Package') . ' </span> : ' . '<span> ' . $package . '</span></div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span> ' . $gender . '</span></div>';
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('dates', function ($row) {
                    $register_date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $active_to = $row->active_to ? optional($row->active_to)->format('Y-m-d') : t('unpaid');
                    $last_login = $row->login_sessions->count() ? Carbon::parse($row->login_sessions->last()->created_at)->toDateTimeString() : '-';
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
                        '<div class="d-flex"><span class="fw-bold text-primary">' . t('Year') . '</span> : ' . optional($row->year)->name . '</div>' .
                        '</div>';
                    return $data;
                });
                   if (!guardIs('teacher')){
                       $datatable->addColumn('actions', function ($row) {
                           return $row->action_buttons;
                       });
                   }
                return $datatable->make();
        }
        $title = t('Show Users');
        $packages = Package::query()->get();
        $years = Year::query()->get();
        $grades = Grade::query()->get();
        $compact = compact('title','packages','years','grades');

        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }elseif (guardIn(['school','supervisor'])){
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections($request->get('school_id'));
        }elseif (guardIs('teacher')){
            $compact['sections'] = schoolSections(Auth::guard('teacher')->user()->school_id);
        }

        return view('general.user.index', $compact);
    }

    public function create()
    {
        $title = t('Add User');
        $grades = Grade::all();
        $packages = Package::query()->get();
        $years = Year::query()->get();
        $compact = compact('title','grades','packages','years');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }elseif (guardIs('school')){
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections(\request()->get('school_id'));
        }elseif (guardIs('teacher')){
            $compact['sections'] = teacherSections();
        }
        return view('general.user.edit', $compact);
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'users')['path'];
        }
        if (!$request->get('demo')){
            $data['demo_grades']=null;
        }
        if (guardIs('manager')){
            $data['manager_id'] = Auth::guard('manager')->user()->id;
        }
        $data['package_id'] = Package::query()->latest()->first()->id;
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));

        //get month number
        $month = Carbon::now()->month;
        //get year number
        $year = Carbon::now()->year;
        if ($month > 7){
            $data['active_to'] = Carbon::create($year+1, 7, 1)->toDateString();
        }else{
            $data['active_to'] = Carbon::create($year, 7, 31)->toDateString();
        }
        if (guardIn(['school', 'teacher']))
        {
            $data['active'] = false;
        }
        $data['added_by_id'] = Auth::id();
        $data['added_by_type'] = 'App\Models\\'.class_basename(Auth::user());
        $user = User::query()->create($data);
        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id) {
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        }
        if (guardIn(['teacher', 'school']))
        {
            \Mail::send(new AddNewStudentMail($user));
        }
        return redirect()->route(getGuard().'.user.index')->with('message', t('User created successfully'));
    }

    public function edit(Request $request,$id)
    {
        $title = t('Edit User');
        $user = User::query()->filter()->findOrFail($id);
        $grades = Grade::all();
        $years = Year::query()->get();

        $compact = compact('title','user','grades','years');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();

        }
        if (guardIn(['manager','school'])){
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['packages'] = Package::query()->get();
            $compact['sections'] = schoolSections(\request()->get('school_id'));
        }
        if (guardIs('teacher')){
            $compact['sections'] = teacherSections();
        }
        return view('general.user.edit', $compact);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'users')['path'];
        }
        if (guardIs('manager') && !$request->get('demo')){
            $data['demo_grades']=null;
        }
        $data['active'] = guardIs('manager') ? $request->get('active', 0):$user->active;
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $user->password;
        $user->update($data);

        $teacher_id = $request->get('teacher_id', false);
        if ($teacher_id) {
            $user->teacherUser()->forceDelete();
            $user->teacherUser()->updateOrCreate([
                'teacher_id' => $teacher_id,
            ], [
                'teacher_id' => $teacher_id,
            ]);
        } else {
            $user->teacherUser()->delete();
        }
        return redirect()->route(getGuard().'.user.index')->with('message', t('User updated successfully'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        $users = User::query()
            ->when($request->get('school_id'), function ($query) use ($request) {
                $query->where('school_id', $request->get('school_id'));
            })
            ->when($request->get('teacher_id'), function (Builder $query) use ($request) {
                $query->whereRelation('teacherUser', 'teacher_id',$request->get('teacher_id'));
            })
            ->whereIn('id', $request->get('row_id'))->get();
        foreach ($users as $user){
            $user->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }


    public function export(Request $request)
    {
        $file_name = "Students Information.xlsx";
        if ($request->get('school_id', false)) {
            $school = School::query()->findOrFail($request->get('school_id'));
            $file_name = $school->name . " Students Information.xlsx";
        }
        return (new StudentInformation($request))->download($file_name);
    }

    public function report(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->userReport($request,$id);
    }

    public function cards(Request $request)
    {
        $request->validate([
            'import_file_id' => 'sometimes|exists:import_student_files,id',
            'school_id' => 'required_if:import_file_id,null|exists:schools,id',
        ]);

        $students = User::with(['grade','school', 'teacher'])->filter($request)->get()->chunk(6);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($request->get('school_id'));
        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }

    public function userCard(Request $request,$id)
    {
        $students = User::with(['grade','school', 'teacher'])->where('id',$id)->get();
        $student_login_url = config('app.url') . '/login';
        $school = $students->first()->school;
        $students= $students->chunk(6);
        $title = $school ? $school->name . ' | ' . t('Student Card') : t('Student Card');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }


    public function login($id)
    {
        $user = User::query()->findOrFail($id);
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('home');
    }


    public function userActivation(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        $request_data = $request->all();
        $data['active'] = $request['activation_data']['activation_status'] == 1 ? 1 : 0;
        if (isset($request['activation_data']['active_to_date']) && !is_null($request['activation_data']['active_to_date'])) {
            $data['active_to'] = Carbon::parse($request['activation_data']['active_to_date'])->format('Y-m-d');
        }
        User::query()->filter($request)->get()->each(function ($user) use ($data) {
            $user->update($data);
        });
        return Response::response(t('Activation Updated Successfully'));
    }

    public function updateGrades(Request $request)
    {
        $request->validate([
            'school_id' => 'required',
        ]);
        $data = [];
        //check if grade is not null and not false then update users
        if (isset($request['users_grades']['grade']) && !is_null($request['users_grades']['grade'])) {
            $data['grade_id'] = $request['users_grades']['grade'];
        }

        //check if grade is not null and not false then update users
        if (isset($request['users_grades']['alternate_grade']) && !is_null($request['users_grades']['alternate_grade'])) {
            $data['alternate_grade_id'] = $request['users_grades']['alternate_grade'];
        }

        //check if assigned_year_id is null and not false then update users
        if (isset($request['users_grades']['assigned_year_id']) && !is_null($request['users_grades']['assigned_year_id'])) {
            $data['year_id'] = $request['users_grades']['assigned_year_id'];
        }

        //check if assigned_year_id is null and not false then update users
        if (isset($request['users_grades']['archived']) && !is_null($request['users_grades']['archived'])) {
            $data['archived'] = $request['users_grades']['archived'] != 2 ? 1 : 0;
        }

        if (isset($request['users_grades']['move_grade']) && !is_null($request['users_grades']['move_grade'])) {
            User::query()->filter($request)->get()->each(function ($user) use ($request) {
                $new_grade = $request['users_grades']['move_grade'] > 0 ? $user->grade_id + $request['users_grades']['move_grade'] : $user->grade_id - abs($request['users_grades']['move_grade']);
                if ($new_grade == 0)
                {
                    $new_grade = 13;
                }
                $user->update([
                    'grade_id' => $new_grade,
                ]);
            });
        }



        User::query()->filter($request)->update($data);

        return Response::response(t('Users Updated Successfully'));
    }

    public function assignedToTeacher(Request $request)
    {
        if (guardIs('teacher')){
            $ids = $request->get('user_id', false);
            foreach ($ids as $id)
            {
                TeacherUser::query()->updateOrCreate(['user_id' => $id],
                    ['teacher_id' => $request->get('teacher_id'),'user_id' => $id]
                );
            }
        }else{
            $request->validate([
                'school_id' => 'required_if:guard,school|required_if:guard,teacher',
                'users_data' => 'required|array',
                'users_data.teacher_school_id' => 'required_if:guard,manager',
                'users_data.users_teacher_id' => 'required',
            ]);

            $users = User::query()->filter($request)->get();

            foreach ($users as $user) {
                if ($user->teacherUser) {
                    $user->teacherUser->delete();
                }
                $user->teacherUser()->create([
                    'teacher_id' => $request->get('users_data')['users_teacher_id'],
                ]);
            }
        }


        return Response::response(t('Users Updated Successfully'));
    }

    public function unassignedUserTeacher(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        $users = User::query()->with('teacherUser')->filter($request)->get();
        foreach ($users as $user) {
            if ($user->teacherUser) {
                $user->teacherUser->delete();
            }
        }
        return Response::response(t('Unsigned Successfully'));
    }

    public function restoreUser($id)
    {
        $user = User::query()->withTrashed()->findOrFail($id);
        if ($user) {
            $other_users = User::query()->where('email', $user->email)->where('id', '!=', $user->id)->get();
            if ($other_users->count() > 0) {
                return Response::response(t('Cannot Restore Student Before Email Already Exist'),null,false);
            }
            $user->restore();
            return Response::response(t('Successfully Restored'));
        }
        return Response::response(t('Student Not Restored'),null,false);

    }



    public function lessonReview(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->review($request,$id,getGuard());

    }

    public function storyReview(Request $request, $id)
    {
        $general = new GeneralFunctions();
        return $general->storyReview($request,$id,getGuard());

    }

    public function resetPasswords(Request $request)
    {
        $request->validate(['password'=>'required|string']);
        $password = $request->get('password');
        $update = User::query()->filter()->update(['password' => bcrypt($password)]);
        return Response::response(t('Password Reset Successfully').': '.$password.' for ('.$update.') '.t('user'));
    }

    public function pdfReports(Request $request)
    {
        $request->validate([
            'school_id' => 'required',
            'grade_id' => 'required',
        ]);

        $students = User::query()
            ->with(['year'])
            ->filter()
            ->select(['id', 'name as student_name', 'id_number as std_id'])->get()->values()->toArray();
//        dd($students);

        $client = new \GuzzleHttp\Client([
            'timeout'  => 36000,
        ]);

        $data = [];
        $res = $client->request('POST', 'https://pdfservice.arabic-uae.com/getpdf.php', [
            'form_params' => [
                'platform' => 'arabic-arabs',
                'studentid' => $students,
                'data' => $data,
            ],
        ]);
        $data = json_decode($res->getBody());
        $url = $data->url;
        $fileContent = file_get_contents($url);
        if ($fileContent === false) {
            throw new \Exception('Unable to download file');
        }else{
            return response($fileContent, 200, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'inline; filename="reports.zip"'
            ]);
        }
        return redirect($data->url);
    }

}
