<?php


namespace App\Interfaces;

use App\Http\Requests\General\MotivationalCertificateRequest;
use Illuminate\Http\Request;

interface MotivationalCertificateRepositoryInterface
{
    public function index(Request $request);

    public function create();

    public function store(MotivationalCertificateRequest $request);

    public function show($id);

    public function destroy(Request $request);

    public function export(Request $request);

}
