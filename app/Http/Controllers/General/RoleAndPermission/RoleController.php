<?php

namespace App\Http\Controllers\General\RoleAndPermission;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\RoleAndPermission\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show roles')->only('index');
        $this->middleware('permission:add roles')->only(['create', 'store']);
        $this->middleware('permission:edit roles')->only(['edit', 'update']);
        $this->middleware('permission:delete roles')->only('destroy');
    }
    public function index()
    {
        if (request()->ajax()) {
            $rows = Role::query()->withCount(['permissions','users'])->filter()->latest();
            return \Yajra\DataTables\DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('name', function ($row) {
                    return $row->name;
                }) ->addColumn('permission_count', function ($row) {
                    return $row->permissions_count;
                })->addColumn('users_count', function ($row) {
                    return $row->users_count;
                })->addColumn('guard_name', function ($row) {
                    return '<span class="badge badge-info">'.camelCaseText($row->guard_name).'</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }

        $title = t('Roles');
        return view('general.role_and_permission.role.index', compact('title'));
    }
    public function create()
    {
        $title = t('Create Role');
        $permissions = Permission::all();
        $guards = sysGuards();
        return view('general.role_and_permission.role.edit', compact('title','guards','permissions'));
    }

    // Store a newly created role
    public function store(RoleRequest $request)
    {
        $request->validated();
        $role = Role::create(['name' => $request->name,'guard_name' => $request->guard_name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route(getGuard().'.role.index')->with('message', t('Role created successfully.'));
    }

    // Show the form for editing the role
    public function edit($id)
    {
        $title = t('Edit Role');
        $role = Role::with('users')->findOrFail($id);
        $selected_permissions = $role->permissions;
        $permissions = Permission::query()
            ->where('guard_name',$role->guard_name)
            ->whereNotIn('id',$selected_permissions->pluck('id')->toArray())->get();

        return view('general.role_and_permission.role.edit', compact('title','role', 'permissions', 'selected_permissions'));
    }

    // Update the specified role
    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name,'guard_name' => $request->guard_name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route(getGuard().'.role.index')->with('message', t('Role updated successfully.'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $row_id = $request->get('row_id');
        if (!is_array($row_id)){
            $row_id = [$row_id];
        }
        $roles = Role::with('users')->whereIn('id', $row_id)->get();
        foreach ($roles as $role) {
            if ($role->users->count()>0){
                return Response::response(t('You cant remove role because linked with users').': ('.$role->name.')',null,false);
            }
            $role->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

}
