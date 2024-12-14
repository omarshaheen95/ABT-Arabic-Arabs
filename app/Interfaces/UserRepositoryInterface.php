<?php


namespace App\Interfaces;

use App\Http\Requests\General\UserRequest;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index(Request $request);

    public function create();

    public function store(UserRequest $request);

    public function edit(Request $request,$id);

    public function update(UserRequest $request, $id);

    public function destroy(Request $request);

    public function export(Request $request);

    public function lessonReview(Request $request, $id);

    public function storyReview(Request $request, $id);

    public function report(Request $request, $id);

    public function cards(Request $request);

    public function userCard(Request $request, $id);

    public function login($id);

    public function userActivation(Request $request);

    public function updateGrades(Request $request);

    public function assignedToTeacher(Request $request);
    public function unassignedUserTeacher(Request $request);

    public function restoreUser($id);
    public function resetPasswords(Request $request);

}
