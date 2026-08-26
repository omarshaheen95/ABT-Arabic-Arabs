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

    public function preview(Request $request, $id);

    public function certificate(Request $request, $id);

    public function export(Request $request);

    public function correctingUserTestView(Request $request, $id);

    public function correctingUserTest(Request $request, $id);

    public function correctingAndFeedbackView(Request $request, $id);
    public function autoCorrectingUsersTests(Request $request);
    public function correctingAndFeedback(Request $request, $id);

    public function destroy(Request $request);

}
