<?php

namespace App\Http\Controllers\General;

use App\Exports\StudentStoryTestExport;
use App\Http\Controllers\Controller;
use App\Interfaces\StoryTestRepositoryInterface;
use App\Models\School;
use App\Models\StoryQuestion;
use App\Models\StudentStoryTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StoryTestController extends Controller
{
    protected $storyTestRepository;
    public function __construct(StoryTestRepositoryInterface $storyTestRepository)
    {
        $this->storyTestRepository = $storyTestRepository;
        $this->middleware('permission:show story tests')->only(['index']);
        $this->middleware('permission:correcting story tests')->only(['correctingView','correcting']);
        $this->middleware('permission:delete story tests')->only(['destroy']);
        $this->middleware('permission:story tests certificate')->only(['certificate']);
        $this->middleware('permission:export story tests')->only('export');
    }

    public function index(Request $request)
    {
        return $this->storyTestRepository->index($request);
    }

    public function correctingView(Request $request,$id){
        return $this->storyTestRepository->correctingView($request,$id);
    }
    public function correcting(Request $request,$id){
        return $this->storyTestRepository->correcting($request,$id);
    }

    public function certificate(Request $request,$id)
    {
        return $this->storyTestRepository->certificate($request,$id);
    }

    public function destroy(Request $request)
    {
        return $this->storyTestRepository->destroy($request);
    }
    public function export(Request $request)
    {
        return $this->storyTestRepository->export($request);
    }
}
