<?php

namespace App\Http\Requests\General;

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
            'password' => 'nullable|min:6',
            'image' => 'nullable|image',
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required',
            'alternate_grade_id' => 'nullable',
//           'type' => 'required|in:trial,member',
            'year_id' => 'required',
            'section' => 'nullable',
            'nationality' => 'nullable',
            'demo_grades' => 'required_if:demo,1',

        ];

        if (guardIn(['manager']) && guardHasPermission('add users')){
            $rules ['package_id'] = 'required|exists:packages,id';
            $rules['active_to'] = 'required|date_format:Y-m-d';
            $rules['gender'] = ['nullable','in:Boy,Girl',];
            $rules['id_number'] = ['nullable'];
            $rules['teacher_id'] = ['nullable', 'exists:teachers,id'];
        }else{
            $rules['gender'] = ['required','in:Boy,Girl'];
            $rules['teacher_id'] = ['required', 'exists:teachers,id'];
            if (Route::currentRouteName() == getGuard().'.user.edit' || Route::currentRouteName() == getGuard().'.user.update') {
                $id = $this->route('user');
                $rules['id_number'] = ['required','unique:users,id_number,'.$id.',id,deleted_at,NULL,archived,0'];
            }else{
                $rules['id_number'] = ['required','unique:users,id_number,NULL,id,deleted_at,NULL,archived,0'];
            }
        }

        if (Route::currentRouteName() == getGuard().'.user.edit' || Route::currentRouteName() == getGuard().'.user.update')
        {
            $id = $this->route('user');
            $rules['email'] = ['required','email:rfc,dns',"unique:users,email,$id,id,deleted_at,NULL,archived,0",new UserEmailRule()];
        }else{
            $rules['email'] = ['required','email:rfc,dns','unique:users,email,{$id},id,deleted_at,NULL,archived,0',new UserEmailRule()];
        }

        return $rules;
    }
}
