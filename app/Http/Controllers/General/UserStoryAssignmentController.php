<?php


namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Interfaces\UserStoryAssignmentRepositoryInterface;
use Illuminate\Http\Request;

class UserStoryAssignmentController extends Controller
{
    protected $userStoryAssignmentRepository;
    public function __construct(UserStoryAssignmentRepositoryInterface $userStoryAssignmentRepository)
    {
        $this->userStoryAssignmentRepository = $userStoryAssignmentRepository;
        $this->middleware('permission:show user story assignments')->only('index');
        $this->middleware('permission:delete user story assignments')->only('destroy');
        $this->middleware('permission:export user story assignments')->only('export');

    }
    public function index(Request $request)
    {
        return $this->userStoryAssignmentRepository->index($request);
    }

    public function destroy(Request $request)
    {
        return $this->userStoryAssignmentRepository->destroy($request);
    }

    public function export(Request $request)
    {
        return $this->userStoryAssignmentRepository->export($request);
    }

}
