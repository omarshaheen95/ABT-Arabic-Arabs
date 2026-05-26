<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Interfaces;

use App\Http\Requests\General\SupervisorRequest;
use Illuminate\Http\Request;

interface SupervisorRepositoryInterface
{
    public function index(Request $request);

    public function create();

    public function store(SupervisorRequest $request);

    public function edit(Request $request, $id);

    public function update(SupervisorRequest $request, $id);

    public function destroy(Request $request);

    public function export(Request $request);
    public function login($id);

    public function activation(Request $request);

    public function resetPasswords(Request $request);

}
