<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;

use App\Http\Requests\General\LessonAssignmentRequest;
use App\Http\Requests\General\TeacherRequest;
use Illuminate\Http\Request;

interface UserStoryAssignmentRepositoryInterface
{
    public function index(Request $request);

    public function export(Request $request);

    public function destroy(Request $request);

}
