<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SchoolProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'mobile' => 'nullable',
            'website' => 'nullable',
            'logo' => 'nullable',
        ];
        $user = Auth::guard('school')->user()->id;
        $rules['email'] = "required|email|unique:schools,email,$user,id,deleted_at,NULL";
        return $rules;
    }
}
