<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\StoryAssignmentRequest;
use App\Interfaces\StoryAssignmentRepositoryInterface;
use Illuminate\Http\Request;

class StoryAssignmentController extends Controller
{
    protected $storyAssignmentRepository;

    public function __construct(StoryAssignmentRepositoryInterface $storyAssignmentRepository)
    {
        $this->storyAssignmentRepository = $storyAssignmentRepository;

        $this->middleware('permission:show story assignments')->only('index');
        $this->middleware('permission:add story assignments')->only(['create', 'store']);
        $this->middleware('permission:delete story assignments')->only('destroy');
        $this->middleware('permission:export story assignments')->only('export');

    }


    public function index(Request $request)
    {
        return $this->storyAssignmentRepository->index($request);
    }

    public function create()
    {
        return $this->storyAssignmentRepository->create();
    }

    public function store(StoryAssignmentRequest $request)
    {
        return $this->storyAssignmentRepository->store($request);
    }

    public function edit(Request $request,$id)
    {
        return $this->storyAssignmentRepository->edit($request,$id);
    }

    public function update(StoryAssignmentRequest $request, $id)
    {
        return $this->storyAssignmentRepository->update($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->storyAssignmentRepository->destroy($request);
    }

    public function export(Request $request)
    {
        return $this->storyAssignmentRepository->export($request);
    }

}
