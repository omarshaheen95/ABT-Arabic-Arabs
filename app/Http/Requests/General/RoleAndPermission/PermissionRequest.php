<?php

namespace App\Http\Requests\General\RoleAndPermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'group' => 'required',
            'guard_name' => 'required',
//            'roles' => 'nullable|array',
//            'roles.*'=>'exists:roles,id'
        ];

        if (Route::currentRouteName() == getGuard().'.permission.edit' || Route::currentRouteName() == getGuard().'.permission.update')
        {
            $id = $this->route('permission');
            $rules["name"] = "required|unique:permissions,name,$id,id";
        }else{
            $rules["name"] = 'required|unique:permissions,name,{$id},id';
        }
        return  $rules;
    }

}
