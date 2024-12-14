<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;

use App\Http\Requests\General\TeacherRequest;
use Illuminate\Http\Request;

interface TeacherRepositoryInterface
{
    public function index(Request $request);

    public function create();

    public function store(TeacherRequest $request);

    public function edit($id);

    public function update(TeacherRequest $request, $id);

    public function destroy(Request $request);

    public function activation(Request $request);

    public function exportTeachersExcel(Request $request);

    public function login($id);

    public function deleteStudents(Request $request);

    public function teachersTracking(Request $request);

    public function teachersTrackingExport(Request $request);

    public function teachersTrackingReport(Request $request, $id);

    public function resetPasswords(Request $request);

}
