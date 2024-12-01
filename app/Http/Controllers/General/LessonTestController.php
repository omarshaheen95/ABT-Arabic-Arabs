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
        $this->middleware('permission:show lesson tests')->only(['index']);
        $this->middleware('permission:correcting lesson tests')->only(['correctingAndFeedbackView','correctingAndFeedback','correctingUserTestView','correctingUserTest']);
        $this->middleware('permission:delete lesson tests')->only(['destroy']);
        $this->middleware('permission:lesson tests certificate')->only(['certificate']);
        $this->middleware('permission:export lesson tests')->only('export');
    }

    public function index(Request $request)
    {
        return $this->lessonTestRepository->index($request);
    }

    public function correctingAndFeedbackView(Request $request, $id)
    {
        return $this->lessonTestRepository->correctingAndFeedbackView($request, $id);
    }
    public function correctingAndFeedback(Request $request, $id)
    {
        return $this->lessonTestRepository->correctingAndFeedback($request, $id);
    }

    public function certificate(Request $request, $id)
    {
        return $this->lessonTestRepository->certificate($request, $id);
    }

    public function destroy(Request $request)
    {
        return $this->lessonTestRepository->destroy($request);
    }

    public function export(Request $request)
    {
        return $this->lessonTestRepository->export($request);
    }


    public function preview(Request $request, $id)
    {
        return $this->lessonTestRepository->preview($request, $id);
    }

    public function correctingUserTestView(Request $request, $id)
    {
        return $this->lessonTestRepository->correctingUserTestView($request, $id);
    }

    public function correctingUserTest(Request $request, $id)
    {
        return $this->lessonTestRepository->correctingUserTest($request, $id);
    }
}
