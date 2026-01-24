<?php

namespace App\Http\Requests\Manager;

use App\Helpers\Constant;
use App\Rules\ArrayWithKeys;
use Illuminate\Foundation\Http\FormRequest;

class AchievementLevelRequest extends FormRequest
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
        $rules = [
            'required_points' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ];

        $rules["name"] = ['required', new ArrayWithKeys(Constant::OUR_LANGUAGES)];
        $rules["name.*"] = ['required'];

        // Badge icon validation - required for create, optional for update
        if ($this->isMethod('post')) {
            $rules['badge_icon'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['badge_icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => t('The name field is required'),
            'required_points.required' => t('The required points field is required'),
            'required_points.integer' => t('The required points must be a number'),
            'required_points.min' => t('The required points must be at least 0'),
            'badge_icon.required' => t('The badge icon is required'),
            'badge_icon.image' => t('The badge icon must be an image'),
            'badge_icon.mimes' => t('The badge icon must be a file of type: jpeg, png, jpg, gif, svg'),
            'badge_icon.max' => t('The badge icon may not be greater than 2MB'),
            'description.max' => t('The description may not be greater than 1000 characters'),
        ];
    }
}
