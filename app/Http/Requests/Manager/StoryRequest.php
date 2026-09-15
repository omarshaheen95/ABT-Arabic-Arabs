<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StoryRequest extends FormRequest
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
        $rules = [];
        $rules["name"] = 'required';
        $rules["image"] = 'nullable|file|mimetypes:' . allowedUploadMimetypes('image');
        $rules["video"] = 'nullable|file|mimetypes:' . allowedUploadMimetypes('video');
        $rules["alternative_video"] = 'nullable|file|mimetypes:' . allowedUploadMimetypes('video');
        $rules["content"] = 'nullable';
        $rules["grade"] = 'required';
//        $rules["ordered"] = 'required';
        return $rules;
    }
}
