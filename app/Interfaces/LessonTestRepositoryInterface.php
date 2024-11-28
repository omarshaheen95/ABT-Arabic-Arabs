<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;

use App\Http\Requests\General\TeacherRequest;
use Illuminate\Http\Request;

interface LessonTestRepositoryInterface
{
    public function index(Request $request);

    public function show(Request $request, $id);

    public function certificate(Request $request, $id);

    public function export(Request $request);

    public function destroy(Request $request);

}
