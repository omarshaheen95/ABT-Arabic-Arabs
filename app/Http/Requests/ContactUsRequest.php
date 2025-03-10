<?php

namespace App\Http\Requests;

use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email:rfc,dns',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha()],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => t('Name required'),
            'mobile.required' => t('Mobile required'),
            'email.required' => t('Email required'),
            'email.email' => t('Email invalid'),
            'message.required' => t('Message required'),
            'g-recaptcha-response.required' => 'من فضلك أكمل تحقق الكابتشا',

        ]; // TODO: Change the autogenerated stub
    }
}
