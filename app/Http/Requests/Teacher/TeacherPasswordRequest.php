<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherPasswordRequest extends FormRequest
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
        return [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',

        ];
    }


}
