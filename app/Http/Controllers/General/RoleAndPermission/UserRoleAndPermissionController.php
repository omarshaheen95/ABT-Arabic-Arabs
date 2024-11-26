<?php

namespace App\Http\Controllers\General\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class UserRoleAndPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users roles and permissions')->only('index');
    }
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $user_guard = $request->get('user_guard')??'manager';
            $modelClass = $this->getModelClass($user_guard);
            $users = $modelClass::query()->filter($request)->latest();
            return DataTables::make($users)
                ->escapeColumns([])
                ->addColumn('name', function ($row) use ($user_guard) {
                    if ($user_guard == 'school'){
                        return $row->getTranslation('name','en');
                    }
                    return $row->name;

                })->addColumn('email', function ($row) {
                    return '<span class="cursor-pointer" style="direction: ltr" data-clipboard-text="'.$row->email.'" onclick="copyToClipboard(this)">' . $row->email . '</span>';
                })->addColumn('guard_name', function ($row) use ($user_guard)  {
                    return '<span class="badge badge-info">'.camelCaseText($user_guard).'</span>';
                })
                ->addColumn('actions', function ($row) use ($user_guard) {
                    $route =route('manager.user_role_and_permission.edit',['user_guard'=>$user_guard,'id'=>$row->id]);
                    return '<div class="d-flex justify-content-center"><a class="btn btn-success btn-sm" href="'.$route.'" target="_blank">'.t('Edit').'</a></div>';
                })
                ->make();
        }

        $title = t('Manage Users Roles & Permissions');
        return view('general.role_and_permission.users_index', compact('title'));
    }


    public function edit(Request $request,$user_guard,$id)
    {
        if (!guardHasPermission('edit '.$user_guard.'s permissions')){
            return redirect()->route(getGuard().'.'.$user_guard.'.index')->with('message', t('You dont have permission'));
        }

        $user = $this->getModelClass($user_guard)::query()->find($id);

        $title = t('Manage Roles & Permissions').' | '.$user->name.' ('.t(camelCaseText(getGuard())).')';
        $guard_id = $id;

        $compact = compact('title','user_guard','guard_id');


        //get roles and selected roles for user
        $compact['roles'] = Role::where('guard_name',$user_guard)->get();
        $compact['user_roles'] = \DB::table('model_has_roles')->where('model_id',$id)->get();

        //get permissions and direct permissions for users
        $compact['permissions'] = Permission::query()->where('guard_name',$user_guard)
            ->get()->groupBy('group');
        $compact['user_permissions'] = \DB::table('model_has_permissions')->where('model_id',$id)->get();


        $compact['index_route'] = route(getGuard().'.'.$user_guard.'.index');
        $compact['update_route'] = route(getGuard().'.user_role_and_permission.update',
            ['user_guard'=>$user_guard,'id'=>$user->id]
        );

        return view('general.role_and_permission.user_role_and_permission', $compact);

    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'user_guard' => 'required|string',
        ]);

        if (!guardHasPermission('edit '.$validated['user_guard'].'s permissions')){
            return redirect()->route(getGuard().'.'.$validated['user_guard'].'.index')->with('message', t('You dont have permission'));
        }

        $modelClass = $this->getModelClass($validated['user_guard']);


        // Find the user
        $user = $modelClass::findOrFail($id);

        // Assign or update roles and permissions
       $this->assignOrUpdateRolesAndPermissions(
            $user,
            $validated['roles'] ?? [],
            $validated['permissions'] ?? [],
            $validated['user_guard'] ?? null
        );
        return redirect()->route(getGuard().'.'.$validated['user_guard'].'.index')->with('message', t('Roles and permissions updated successfully.'));
//        return redirect()->route(getGuard().'.user_role_and_permission.index')->with('message', t('Roles and permissions updated successfully.'));

    }



    private function assignOrUpdateRolesAndPermissions(
        Model $user,
        array $roles = [],
        array $permissions = [],
        ?string $guardName = null
    ) {
        // Validate that the model uses the Spatie HasRoles trait
        if (!method_exists($user, 'assignRole') || !method_exists($user, 'givePermissionTo')) {
            throw new \Exception('The provided model must use the Spatie HasRoles trait.');
        }

        // If guard name is not provided, default to the user's guard
        $guardName = $guardName ?? $user->guard_name ?? 'web';

        // Validate and assign roles
        if (!empty($roles)) {
            $validRoles = Role::whereIn('name', $roles)->where('guard_name', $guardName)->pluck('name')->toArray();
            $user->syncRoles($validRoles); // Replace existing roles with the new ones
        }

        // Validate and assign permissions
        if (!empty($permissions)) {
            $validPermissions = Permission::whereIn('name', $permissions)->where('guard_name', $guardName)->pluck('name')->toArray();
            $user->syncPermissions($validPermissions); // Replace existing permissions with the new ones
        }

        return $user;
    }

    private function getModelClass($guard)
    {
        switch ($guard) {
            case'manager':
            {
                return  \App\Models\Manager::class;
            }
            case'school':
            {
                return \App\Models\School::class;
            }
            case'teacher':
            {
                return \App\Models\Teacher::class;
            }
            case'supervisor':
            {
                return \App\Models\Supervisor::class;
            }

        };
        return null;
    }
}
