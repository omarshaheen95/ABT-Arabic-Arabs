<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Http\Requests\Manager;

use App\Rules\UserEmailRule;
use App\Rules\UserNameRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportFileLogDataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'student_data_file_id' => 'required|exists:import_files,id',
            'student' => 'array|required',
            'student.*.row_num' => 'required',

            'student.*.name' =>['required', 'regex:/^[a-zA-Z0-9_.\s-]*$/u','max:25'],
            'student.*.email' => ['required', 'regex:/^(?=.{4,}$)[a-zA-Z0-9._@-]+(?<![_.@])$/u'],
            'student.*.mobile' => 'sometimes',
            'student.*.password' => 'sometimes',
            'student.*.grade' => 'required|exists:grades,id',
            'student.*.alternative_grade' => 'nullable|exists:grades,id',
            'student.*.section' => 'sometimes',
            'student.*.gender' => 'sometimes|in:Boy,Girl',
            'student.*.active' => 'sometimes|in:1,0',
            'student.*.student_id' => 'required',
            'student.*.nationality' => 'sometimes',
            //'student.*.date_of_birth' => 'sometimes',
            'student.*.teacher' => 'required',

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
