<?php

namespace App\Http\Requests\General\RoleAndPermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class RoleRequest extends FormRequest
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
            'guard_name' => 'required',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ];

        if (Route::currentRouteName() == getGuard().'.role.edit' || Route::currentRouteName() == getGuard().'.role.update')
        {
            $id = $this->route('role');
            $rules["name"] = "required|unique:roles,name,$id,id";
        }else{
            $rules["name"] = 'required|unique:roles,name,{$id},id';
        }
        return  $rules;
    }

}
