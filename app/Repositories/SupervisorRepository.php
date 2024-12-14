<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Repositories;

use App\Exports\SupervisorExport;
use App\Helpers\Response;
use App\Http\Requests\General\SupervisorRequest;
use App\Interfaces\SupervisorRepositoryInterface;
use App\Models\School;
use App\Models\Supervisor;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class SupervisorRepository implements SupervisorRepositoryInterface
{

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = Supervisor::query()->withCount(['supervisor_teachers'])->with(['school'])->filter($request)->latest();
            return \Yajra\DataTables\DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('last_login', function ($row) {
                    return $row->last_login ? Carbon::parse($row->last_login)->toDateTimeString() : '';
                })
                ->addColumn('supervisor_data', function ($row){
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex fw-bold">' . '<span class="fw-bold me-1">' . t('Name') . ' : </span>' . $row->name . '</div>';
                    if (getGuard() == 'manager') {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary me-1">' . t('School') . ' : </span><span style="direction: ltr">' . optional($row->school)->name . '</span></div>';
                    }
                    $html .= '<div class="d-flex text-danger"><span class="cursor-pointer" style="direction: ltr" data-clipboard-text="' . $row->email . '" onclick="copyToClipboard(this)">' . $row->email . '</span></div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('active', function ($row) {
                    return $row->active ? '<span class="badge badge-primary">'.t('Active').'</span>' : '<span class="badge badge-danger">'.t('Inactive').'</span>';
                })
                ->addColumn('approved', function ($row) {
                    return $row->approved ? '<span class="badge badge-primary">'.t('Approved').'</span>' : '<span class="badge badge-warning">'.t('Under review').'</span>';
                })
                ->addColumn('role', function ($row) {
                    $roles = '<div class="d-flex  gap-1">';
                    if ($row->roles->count()>0){
                        foreach ($row->roles as $role){
                            $roles .= '<span class="badge badge-info">'.$role->name.'</span>';
                        }
                    }else{
                        $roles .= '<span class="badge badge-warning">'.t('No Role').'</span>';
                    }

                    $roles .= '</div>';
                    return $roles;
                })

                ->addColumn('teachers_count', function ($row) {
                    return $row->supervisor_teachers_count;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Supervisors');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools']  = School::query()->get();
        }
        return view('general.supervisor.index', $compact);
    }

    public function create()
    {
        $title = t('Add supervisor');
        $compact = compact('title');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        return view('general.supervisor.edit',$compact);
    }

    public function store(SupervisorRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'supervisors')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = bcrypt($request->get('password', 123456));

        if (guardIs('manager')){
            $data['approved'] = $request->get('approved', 0);
        }
        //$data['teachers'] = [];
        $supervisor = Supervisor::query()->create($data);

        if (count($request->get('teachers', []))) {
            $supervisor->teachers()->sync($request->get('teachers', []));
        }
        return redirect()->route(getGuard().'.supervisor.index')->with('message', t('Successfully Added'));
    }

    public function edit(Request $request,$id)
    {
        $title = t('Edit Supervisor');
        $supervisor = Supervisor::query()->with(['supervisor_teachers'])->findOrFail($id);

        $compact = compact('title','supervisor');
        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }
        $compact['teachers'] = Teacher::query()->where('school_id',$supervisor->school_id)->get();

        return view('general.supervisor.edit', $compact);
    }

    public function update(SupervisorRequest $request, $id)
    {
        $supervisor = Supervisor::query()->findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request->file('image'), 'supervisors')['path'];
        }
        $data['active'] = $request->get('active', 0);
        $data['password'] = $request->get('password', false) ? bcrypt($request->get('password', 123456)) : $supervisor->password;
        if (guardIs('manager')){
            $data['approved'] = $request->get('approved', 0);
        }
        $supervisor->update($data);
        $supervisor->teachers()->sync($request->get('teachers', []));
        return redirect()->route(getGuard().'.supervisor.index')->with('message', t('Successfully Updated'));
    }

    public function login($id)
    {
        Supervisor::query()->findOrFail($id);
        Auth::guard('supervisor')->loginUsingId($id);
        return redirect()->route('supervisor.home');
    }

    public function export(Request $request)
    {
        return (new SupervisorExport($request))->download('Supervisors Information.xlsx');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $supervisors = Supervisor::query()->whereIn('id',$request->get('row_id'))->get();
        foreach ($supervisors as $supervisor){
            $supervisor->delete();
        }
        return Response::response([Response::SUCCESS]);
    }

    public function activation(Request $request)
    {
        if (getGuard()!='manager'){
            return Response::response(t('You are not authorized to do that'));
        }
        $data = [];
        $activation_data = $request->get('activation_data',false);
        if ($activation_data){
            if ($activation_data['active']){
                $data['active'] = $activation_data['active']!=2;
            }
            if ($activation_data['approved']){
                $data['approved'] = $activation_data['approved']!=2;
            }
        }

        if (count($data)){
            $update = Supervisor::query()->filter($request)->update($data);
            return Response::response(t('Updated Successfully : ') .$update);
        }
        return Response::response(t('Successfully Updated'));
    }


    public function resetPasswords(Request $request)
    {
        $request->validate(['password'=>'required|string']);
        $password = $request->get('password');
        $update = Supervisor::query()->filter()->update(['password' => bcrypt($password)]);
        return Response::response(t('Password Reset Successfully').': '.$password.' for ('.$update.') '.t('supervisor'));
     }
}
