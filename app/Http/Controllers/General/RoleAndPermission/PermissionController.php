<?php

namespace App\Http\Controllers\General\RoleAndPermission;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\RoleAndPermission\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show permissions')->only('index');
        $this->middleware('permission:add permissions')->only(['create', 'store']);
        $this->middleware('permission:edit permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete permissions')->only('destroy');
    }
    public function index()
    {
        if (request()->ajax()) {
            $rows = Permission::query()->withCount(['roles'])->filter()->latest();
            return \Yajra\DataTables\DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('name', function ($row) {
                    return $row->name;
                }) ->addColumn('roles_count', function ($row) {
                    return $row->roles_count;
                })->addColumn('group', function ($row) {
                    return '<span class="badge badge-secondary">'.camelCaseText($row->group).'</span>';
                })->addColumn('guard_name', function ($row) {
                    return '<span class="badge badge-info">'.camelCaseText($row->guard_name).'</span>';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }

        $title = t('Permissions');
        $groups = Permission::query()->pluck('group')->unique();
        return view('general.role_and_permission.permission.index', compact('title','groups'));
    }
    public function create()
    {
        $title = t('Create Permission');
        $roles = Role::all();
        return view('general.role_and_permission.permission.edit', compact('title','roles'));
    }

    // Store a newly created role
    public function store(PermissionRequest $request)
    {
        $request->validated();
        $permission = Permission::create(['name' => $request->name,'group' => $request->group,'guard_name' => $request->guard_name]);
        // Assign roles to this permission
        if ($request->has('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
        }
        return redirect()->route(getGuard().'.permission.index')->with('message', t('Permission created successfully.'));
    }

    // Show the form for editing the role
    public function edit($id)
    {
        $title = t('Edit Permission');
        $permission = Permission::findOrFail($id);
        $roles = Role::query()->where('guard_name',$permission->guard_name)->get();
        $selected_roles = $permission->roles;

        return view('general.role_and_permission.permission.edit', compact('title','permission','roles','selected_roles'));
    }

    // Update the specified role
    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name,'group' => $request->group,'guard_name' => $request->guard_name]);

        // Assign roles to this permission
        if ($request->has('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route(getGuard().'.permission.index')->with('message', t('Permission updated successfully.'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $row_id = $request->get('row_id');
        if (!is_array($row_id)){
            $row_id = [$row_id];
        }
        $permissions = Permission::with(['roles','users'])->whereIn('id', $row_id)->get();
        foreach ($permissions as $permission) {
            if ($permission->roles->count()>0 || $permission->users->count()>0){
                return Response::response(t('You cant remove permission because is used').': ('.$permission->name.')',null,false);
            }
            $permission->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

}
