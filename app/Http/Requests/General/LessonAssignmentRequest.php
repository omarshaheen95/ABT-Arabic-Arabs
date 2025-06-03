<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LessonAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        $rules =  [
            'school_id' => ['required', 'exists:schools,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'year_id' => ['required', 'exists:years,id'],
            'grade_id' => ['exists:grades,id'],
            'lessons_ids' => ['array'],
            'lessons_ids.*' => ['exists:lessons,id'],
            'sections' => ['nullable', 'array'],
            'students' => ['required', 'array', ''],
            'deadline' => ['required'],
            'exclude_students' => ['required', 'in:1,2'],
//            'test_assignment' => ['sometimes'],
        ];

        if (Route::currentRouteName() == getGuard().'.lesson_assignment.store' || Route::currentRouteName() == getGuard().'.lesson_assignment.create')
        {
            $rules['lessons_ids'][] = 'required';
            $rules['grade_id'][] = 'required';
            $rules['deadline'][] =  'after:today';

        }else{
            $rules['grade_id'][] = 'nullable';
        }

        return  $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
