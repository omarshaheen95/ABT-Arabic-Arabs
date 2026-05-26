<?php

namespace App\Http\Controllers\Manager;

use App\Exports\StudentStoryRecordExport;
use App\Exports\StudentStoryTestExport;
use App\Exports\StudentTestExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\UpdateUserRecordRequest;
use App\Models\Grade;
use App\Models\Question;
use App\Models\School;
use App\Models\StoryQuestion;
use App\Models\StoryUserRecord;
use App\Models\StudentStoryTest;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class StudentTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show lesson tests')->only(['lessonsIndex','show']);
        $this->middleware('permission:delete lesson tests')->only(['lessonsDestroy']);
        $this->middleware('permission:lesson tests certificate')->only(['lessonsCertificate']);
        $this->middleware('permission:export lesson tests')->only('lessonsExportStudentsTestsExcel');

        $this->middleware('permission:show story tests')->only(['storiesIndex','storiesShow']);
        $this->middleware('permission:delete story tests')->only(['storiesDestroy']);
        $this->middleware('permission:story tests certificate')->only(['storiesCertificate']);
        $this->middleware('permission:export story tests')->only('exportStoriesTestsExcel');


        $this->middleware('permission:show user records')->only(['storiesRecordsIndex','storiesRecordsShow']);
        $this->middleware('permission:marking user records')->only(['storiesRecordsUpdate']);
        $this->middleware('permission:delete user records')->only(['storiesRecordsDestroy']);
        $this->middleware('permission:export user records')->only('exportStoriesRecordsExcel');

    }
    public function lessonsIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = UserTest::with(['user.school','user.grade','lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('School').':</span>'.$row->user->school->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">'.t('Submitted At').':</span>'.$row->created_at->format('Y-m-d H:i').'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('lesson', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->lesson->grade->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Lesson').':</span>'.$row->lesson->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass'?'<span class="badge badge-primary">'.$row->status.'</span>':'<span class="badge badge-danger">'.$row->status.'</span>';
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->total_per.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$status.'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    //add created_at to the action buttons
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students lessons tests');
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        return view('manager.lessons_tests.index', compact('title', 'grades', 'schools'));
    }

    public function lessonsShow(Request $request,$id){
        $student_test = UserTest::query()->with(['lesson','user'])->where('id',$id)->first();
        if ($student_test){
            $questions = Question::with([
                'trueFalse','options','matches','sortWords',
                'true_false_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'option_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'match_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'sort_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'speaking_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },'writing_results'=>function($query) use($id){
                   $query->where('user_test_id',$id);
                },
            ])->where('lesson_id',$student_test->lesson_id)->get();
            // dd($questions->toArray());

            $lesson = $student_test->lesson;
            $user = $student_test->user;
            return view('general.user.test_preview.test',compact('questions','student_test','lesson','user'));
        }
        return redirect()->route('manager.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }
    public function show(Request $request,$id)
    {
        $title = "عرض اختبار طالب";
        $user_test = UserTest::query()->with(['lesson', 'user'])->filter($request)->findOrFail($id);
        return view('manager.lessons_tests.show',compact('title', 'user_test'));
    }

    public function preview(Request $request,$id)
    {
        $student_test = UserTest::query()->with(['lesson', 'user'])->filter($request)->findOrFail($id);
        $grade = $student_test->lesson->grade_id;
        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();

        return view('manager.lessons_tests.student_test_results', compact('student_test', 'grade', 'questions'));
    }
    public function correct(Request $request, $id)
    {
        $request->validate([
            'mark' => 'required|max:100|min:0',
        ]);
        $user_test = UserTest::query()->with(['lesson', 'user'])
            ->whereHas('lesson', function (Builder $query) {
                $query->whereIn('lesson_type', ['writing', 'speaking']);
            })
            ->filter($request)->findOrFail($id);

        $record = null;
        if(isset($_FILES['record1']) && $_FILES['record1']['type'] != 'text/plain' && $_FILES['record1']['error'] <= 0){
            $new_name = uniqid().'.'.'wav';
            $destination = public_path('uploads/teachers_records_result');
            move_uploaded_file($_FILES['record1']['tmp_name'], $destination .'/'. $new_name);
            $record = 'uploads'.DIRECTORY_SEPARATOR.'teachers_records_result'.DIRECTORY_SEPARATOR.$new_name;
        }
        $success_mark = $user_test->lesson->success_mark;
        $mark = $request->get('mark');
        $user_test->update([
            'approved' => 1,
            'corrected' => 1,
            'total' => $mark,
            'status' => $mark >= $success_mark ? 'Pass':'Fail',
            'feedback_message' => $request->get('teacher_message', null),
            'feedback_record' => $record,
        ]);

        $student_tests = UserTest::query()
            ->where('user_id',  $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->orderByDesc('total')->get();



        if (optional($student_tests->first())->total >= $mark)
        {
            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id', '<>', $student_tests->first()->id)->update([
                    'approved' => 0,
                ]);

            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id',  $student_tests->first()->id)->update([
                    'approved' => 1,
                ]);
        }

        $user_test->user->user_tracker()->create([
            'lesson_id' => $user_test->lesson_id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $user_test->start_at,
            'end_at' => $user_test->end_at,
        ]);

        if ($user_test->user->teacherUser)
        {
            updateTeacherStatistics($user_test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()
            ->where('user_id', $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment)
        {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }

        return $this->redirectWith(false, 'teacher.lessons_tests.index', 'تم اعتماد التصحيح بنجاح');

    }

    public function lessonsCertificate(Request $request,$id)
    {
        $title = 'Student test result';
        $student_test = UserTest::query()->with(['lesson.grade'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('manager.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.new_certificate', compact('student_test', 'title'));
    }

    public function lessonsExportStudentsTestsExcel(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentTestExport($request))->download('Students tests.xlsx');
    }

    public function lessonsDestroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        UserTest::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }


    public function storiesIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StudentStoryTest::with(['user.school','user.grade','story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                   $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('School').':</span>'.$row->user->school->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-success pe-1">'.t('Submitted At').':</span>'.$row->created_at->format('Y-m-d H:i').'</div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Level').':</span>'.$row->story->grade_name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Story').':</span>'.$row->story->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass'?'<span class="badge badge-primary">'.$row->status.'</span>':'<span class="badge badge-danger">'.$row->status.'</span>';
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->total_per.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$status.'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students stories tests');
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        return view('manager.students_tests.stories', compact('title','grades', 'schools'));
    }

    public function storiesShow(Request $request,$id){
        $student_test = StudentStoryTest::query()->with(['story','user'])->where('id',$id)->first();
        if ($student_test){
            $questions = StoryQuestion::with([
                'trueFalse','options','matches','sort_words',
                'true_false_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'option_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'match_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },'sort_results'=>function($query) use($id){
                   $query->where('student_story_test_id',$id);
                },
            ])->where('story_id',$student_test->story_id)->get();
            // dd($questions->toArray());

            $story = $student_test->story;
            $user = $student_test->user;
//            dd($questions);
            return view('general.user.test_preview.story_test',compact('questions','student_test','story','user'));
        }
        return redirect()->route('manager.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }

    public function storiesCertificate(Request $request,$id)
    {
        $title = 'Student test result';
        $student_test = StudentStoryTest::query()->with(['story'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route('manager.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.story.new_certificate', compact('student_test', 'title'));
    }

    public function exportStoriesTestsExcel(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryTestExport($request))->download('Students tests.xlsx');
    }

    public function storiesDestroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        StudentStoryTest::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }


    public function storiesRecordsIndex(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StoryUserRecord::with(['user.school','user.grade','story'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row){
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex fw-bold">'.$row->user->name.'</div>'.
                        '<div class="d-flex text-danger"><span style="direction: ltr">'.$row->user->email.'</span></div>'.
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row){
                    $gender = !is_null($row->user->gender) ? $row->user->gender : '<span class="text-danger">-</span>';
                    $student =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('School').':</span>'.$row->user->school->name.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary"> ' . t('Gender') . ' </span> : ' . '<span class="ps-1"> ' . $gender . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('story', function ($row) {
                    $html =  '<div class="d-flex flex-column">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->story->grade_name.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Story').':</span>'.$row->story->name.'</div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('status', function ($row) {
                    $html =  '<div class="d-flex flex-column justify-content-center">'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->status_name_class.'</span></div>'.
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.$row->created_at->format('Y-m-d H:i').'</span></div>'.
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })

                ->make();
        }
        $title = t('Show students stories records');
        $schools = School::query()->get();
        $grades = Grade::query()->get();
        return view('manager.stories_records.index', compact('title','grades', 'schools'));
    }

    public function storiesRecordsShow(Request $request,$id){
        $title = t('Show Student Story Record');
        $user_record = StoryUserRecord::query()->with(['story','user'])->findOrFail($id);
        return view('manager.stories_records.show',compact('user_record', 'title'));
    }

    public function storiesRecordsUpdate(UpdateUserRecordRequest $request,$id){
        $student_record = StoryUserRecord::query()->with(['story','user'])->findOrFail($id);
        $data = $request->validated();
        $data['approved'] = $request->get('approved', 0);
        $student_record->update($data);
        return $this->redirectWith(false, 'manager.stories_records.index', 'Record Updated Successfully ');
    }

    public function storiesRecordsDestroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        StoryUserRecord::destroy($request->get('row_id'));
        return $this->sendResponse(null, t('Deleted Successfully'));
    }

    public function exportStoriesRecordsExcel(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryRecordExport($request))->download('Students stories records.xlsx');
    }
}
