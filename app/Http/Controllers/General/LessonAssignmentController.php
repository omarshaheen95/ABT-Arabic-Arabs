<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\LessonAssignmentRequest;
use App\Interfaces\LessonAssignmentRepositoryInterface;
use Illuminate\Http\Request;

class LessonAssignmentController extends Controller
{
    protected $lessonAssignmentRepository;

    public function __construct(LessonAssignmentRepositoryInterface $lessonAssignmentRepository)
    {
        $this->lessonAssignmentRepository = $lessonAssignmentRepository;

        $this->middleware('permission:show lesson assignments')->only('index');
        $this->middleware('permission:add lesson assignments')->only(['create', 'store']);
        $this->middleware('permission:delete lesson assignments')->only('destroy');
        $this->middleware('permission:export lesson assignments')->only('export');

    }


    public function index(Request $request)
    {
        return $this->lessonAssignmentRepository->index($request);
    }

    public function create()
    {
        return $this->lessonAssignmentRepository->create();
    }

    public function store(LessonAssignmentRequest $request)
    {
        return $this->lessonAssignmentRepository->store($request);
    }

    public function edit(Request $request,$id)
    {
        return $this->lessonAssignmentRepository->edit($request,$id);
    }

    public function update(LessonAssignmentRequest $request, $id)
    {
        return $this->lessonAssignmentRepository->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->lessonAssignmentRepository->destroy($request);
    }

    public function export(Request $request)
    {
        return $this->lessonAssignmentRepository->export($request);
    }

}
