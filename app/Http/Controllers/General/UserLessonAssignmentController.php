<?php


namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Interfaces\UserLessonAssignmentRepositoryInterface;
use App\Models\UserAssignment;
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

    public function updateStatus()
    {
        UserAssignment::query()->where('id',382465)->update(['completed_at'=>null,
            'completed' => 0,'tasks_assignment'=>0,'test_assignment'=>0,'done_tasks_assignment'=>0,'done_test_assignment'=>0,]);
        return response()->json(['status'=>'ok']);
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
