<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;

use App\Http\Requests\General\TeacherRequest;
use Illuminate\Http\Request;

interface StoryTestRepositoryInterface
{
    public function index(Request $request);

    public function correctingView(Request $request, $id);
    public function correcting(Request $request, $id);
    public function autocorrectingTests(Request $request);

    public function certificate(Request $request, $id);

    public function export(Request $request);

    public function destroy(Request $request);

}
