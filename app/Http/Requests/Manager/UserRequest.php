<?php

namespace App\Http\Requests\Manager;

use App\Models\User;
use App\Rules\UniqueMobilePhone;
use App\Rules\UserEmailRule;
use App\Rules\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
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
        $rules= [
            'name' =>['required', new UserNameRule()],
            'id_number' => 'nullable',
            'password' => 'nullable|min:6',
            'image' => 'nullable|image',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'grade_id' => 'required',
            'alternate_grade_id' => 'nullable',
            'package_id' => 'required|exists:packages,id',
//           'type' => 'required|in:trial,member',
            'active_to' => 'required|date_format:Y-m-d',
            'year_id' => 'required',
            'section' => 'nullable',
            'nationality' => 'nullable',
            'gender' => 'nullable|in:Boy,Girl',
            'demo_grades' => 'required_if:demo,1',

        ];

        if (Route::currentRouteName() == 'manager.user.edit' || Route::currentRouteName() == 'manager.user.update')
        {
            $id = $this->route('user');
            $rules['email'] = ['required',"unique:users,email,$id,id,deleted_at,NULL,archived,0",new UserEmailRule()];
        }else{
            $rules['email'] = ['required','unique:users,email,{$id},id,deleted_at,NULL,archived,0',new UserEmailRule()];
        }

        return $rules;
    }
}
