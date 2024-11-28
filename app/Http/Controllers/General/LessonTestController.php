<?php

namespace App\Http\Controllers\General;

use App\Exports\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Interfaces\LessonTestRepositoryInterface;
use App\Models\Question;
use App\Models\School;
use App\Models\StudentTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LessonTestController extends Controller
{

    protected $lessonTestRepository;
    public function __construct(LessonTestRepositoryInterface $lessonTestRepository)
    {
        $this->lessonTestRepository = $lessonTestRepository;
        $this->middleware('permission:show lesson tests')->only(['index', 'show']);
        $this->middleware('permission:delete lesson tests')->only(['destroy']);
        $this->middleware('permission:lesson tests certificate')->only(['certificate']);
        $this->middleware('permission:export lesson tests')->only('export');
    }
    public function index(Request $request)
    {
      return $this->lessonTestRepository->index($request);
    }

    public function show(Request $request,$id){
        return $this->lessonTestRepository->show($request,$id);
    }

    public function certificate(Request $request,$id)
    {
        return $this->lessonTestRepository->certificate($request,$id);
    }

    public function destroy(Request $request)
    {
        return $this->lessonTestRepository->destroy($request);
    }
    public function export(Request $request)
    {
        return $this->lessonTestRepository->export($request);
    }
}
