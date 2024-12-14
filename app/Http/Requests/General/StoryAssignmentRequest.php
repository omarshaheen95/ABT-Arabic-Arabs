<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class StoryAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        $rules =  [
            'school_id' => ['required', 'exists:schools,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'students_grade' => ['required'],
            'story_grade' => [],
            'stories_ids' => ['array'],
            'stories_ids.*' => ['exists:stories,id'],
            'sections' => ['nullable', 'array'],
            'students' => ['required', 'array',],
            'deadline' => ['nullable'],
            'exclude_students' => ['required','in:1,2'],
        ];
        if (Route::currentRouteName() == getGuard().'.story_assignment.store' || Route::currentRouteName() == getGuard().'.story_assignment.create')
        {
            $rules['stories_ids'][] = 'required';
            $rules['story_grade'][] = 'required';
            $rules['deadline'][] =  'after:today';
        }else{
            $rules['story_grade'][] = 'nullable';
        }
        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
