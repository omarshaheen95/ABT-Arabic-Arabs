<?php


namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Interfaces\UserLessonAssignmentRepositoryInterface;
use Illuminate\Http\Request;

class UserLessonAssignmentController extends Controller
{
    protected $userLessonAssignmentRepository;
    public function __construct(UserLessonAssignmentRepositoryInterface $userLessonAssignmentRepository)
    {
        $this->userLessonAssignmentRepository = $userLessonAssignmentRepository;
        $this->middleware('permission:show user lesson assignments')->only('index');
        $this->middleware('permission:delete user lesson assignments')->only('destroy');
        $this->middleware('permission:export user lesson assignments')->only('export');

    }
    public function index(Request $request)
    {
      return $this->userLessonAssignmentRepository->index($request);
    }

    public function destroy(Request $request)
    {
        return $this->userLessonAssignmentRepository->destroy($request);
    }

    public function export(Request $request)
    {
        return $this->userLessonAssignmentRepository->export($request);
    }

}
