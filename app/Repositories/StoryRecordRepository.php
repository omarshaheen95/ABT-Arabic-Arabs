<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Repositories;

use App\Exports\StudentStoryRecordExport;
use App\Helpers\Response;
use App\Http\Requests\General\UpdateUserRecordRequest;
use App\Interfaces\StoryRecordRepositoryInterface;
use App\Models\Grade;
use App\Models\School;
use App\Models\StoryUserRecord;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class StoryRecordRepository implements StoryRecordRepositoryInterface
{
    public function index(Request $request)
    {
        if (request()->ajax())
        {
            $rows = StoryUserRecord::with(['user.school','story'])->filter($request)->latest();

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
                    $html .= '</div>';
                    return $html;
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
        $title = t('Students stories records');
        $grades = Grade::query()->get();
        $compact = compact('title','grades');

        if (guardIs('manager')){
            $compact['schools'] = School::query()->get();
        }elseif (guardIn(['school','supervisor'])){
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        }elseif (guardIs('teacher')){
            $compact['sections'] = teacherSections();
        }
        return view('general.stories_records.index', $compact);
    }

    public function show(Request $request,$id){
        $title = t('Show Student Story Record');
        $user_record = StoryUserRecord::query()->with(['story','user'])->filter()->findOrFail($id);
        return view('general.stories_records.show',compact('user_record', 'title'));
    }

    public function update(UpdateUserRecordRequest $request,$id){
        $student_record = StoryUserRecord::query()->with(['story','user'])->filter()->findOrFail($id);
        $data = $request->validated();
        $data['approved'] = $request->get('approved', 0);
        $student_record->update($data);
        return redirect()->route(getGuard().'.stories_records.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
       $records =  StoryUserRecord::query()->when($value = $request->get('school_id'), function (Builder $query) use ($value){
            $query-> whereRelation('user','school_id',$value);
        })->when($value = $request->get('teacher_id'), function (Builder $query) use ($value){
            $query-> whereRelation('user.teacherUser','teacher_id',$value);
        })->whereIn('id',$request->get('row_id'))->get();

        foreach ($records as $record){
            $record->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentStoryRecordExport($request))->download('Students stories records.xlsx');
    }


}
