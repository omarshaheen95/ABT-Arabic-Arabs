<?php

namespace App\Http\Controllers\General;

use App\Exports\StudentStoryTestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\UpdateUserRecordRequest;
use App\Interfaces\StoryRecordRepositoryInterface;
use App\Interfaces\StoryTestRepositoryInterface;
use App\Models\School;
use App\Models\StoryQuestion;
use App\Models\StudentStoryTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StoryRecordController extends Controller
{
    protected $storyRecordRepository;
    public function __construct(StoryRecordRepositoryInterface $storyRecordRepository)
    {
        $this->storyRecordRepository = $storyRecordRepository;
        $this->middleware('permission:show user records')->only(['index','show']);
        $this->middleware('permission:marking user records')->only(['update']);
        $this->middleware('permission:delete user records')->only(['destroy']);
        $this->middleware('permission:export user records')->only('export');

    }

    public function index(Request $request)
    {
        return $this->storyRecordRepository->index($request);
    }

    public function show(Request $request,$id){
        return $this->storyRecordRepository->show($request,$id);
    }

    public function update(UpdateUserRecordRequest $request,$id)
    {
        return $this->storyRecordRepository->update($request,$id);
    }

    public function export(Request $request)
    {
        return $this->storyRecordRepository->export($request);
    }

    public function destroy(Request $request)
    {
        return $this->storyRecordRepository->destroy($request);
    }
}
