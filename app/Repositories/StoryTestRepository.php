<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Repositories;

use App\Exports\StudentStoryTestExport;
use App\Helpers\Response;
use App\Interfaces\StoryTestRepositoryInterface;
use App\Models\Grade;
use App\Models\School;
use App\Models\StoryQuestion;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class StoryTestRepository implements StoryTestRepositoryInterface
{
    public function index(Request $request)
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
                    $html = '<div class="d-flex flex-column">';
                    if (guardIs('manager')) {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('School') . ':</span>' . $row->user->school->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '<span class="fw-bold ms-2 text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-success pe-1">' . t('Submitted At') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>';
                    $html .= '</div>';
                    return $html;
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
        $title = t('Students stories tests');
        $grades = Grade::all();
        $compact = compact('title','grades');

        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }elseif (guardIn(['school','supervisor'])){
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        }elseif (guardIs('teacher')){
            $compact['sections'] = teacherSections();
        }
        return view('general.story_test.index', $compact);
    }

    public function correctingView(Request $request,$id){
        $student_test = StudentStoryTest::query()->with(['story','user'])->where('id',$id)->filter()->first();
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

            return view('general.correcting_lesson_and_story_test.story',compact('questions','student_test','story','user'));
        }
        return redirect()->route(getGuard().'.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }

    public function correcting(Request $request,$id){

    }
    public function certificate(Request $request,$id)
    {
        $title = 'Student test result';
        $student_test = StudentStoryTest::query()->with(['story'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route(getGuard().'.home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('general.user.certificate.story_certificate', compact('student_test', 'title'));
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryTestExport($request))->download('Students tests.xlsx');
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        $tests = StudentStoryTest::query()->when($value = $request->get('school_id'), function (Builder $query) use ($value){
            $query-> whereRelation('user','school_id',$value);
        })->when($value = $request->get('teacher_id'), function (Builder $query) use ($value){
            $query-> whereRelation('user.teacher_student','teacher_id',$value);
        })->whereIn('id',$request->get('row_id'))->get();

        foreach ($tests as $test){
            $test->delete();
        }

        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }
}
