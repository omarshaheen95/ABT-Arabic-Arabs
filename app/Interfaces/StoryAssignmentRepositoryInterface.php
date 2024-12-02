<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;


use App\Http\Requests\General\StoryAssignmentRequest;
use Illuminate\Http\Request;

interface StoryAssignmentRepositoryInterface
{
    public function index(Request $request);

    public function create();

    public function store(StoryAssignmentRequest $request);

    public function edit(Request $request, $id);

    public function update(StoryAssignmentRequest $request,$id);

    public function export(Request $request);

    public function destroy(Request $request);

}
