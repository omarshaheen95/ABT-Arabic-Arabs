<?php

namespace App\Repositories;

use App\Exports\StoryAssignmentExport;
use App\Helpers\Response;
use App\Http\Requests\General\StoryAssignmentRequest;
use App\Interfaces\StoryAssignmentRepositoryInterface;
use App\Models\Grade;
use App\Models\Story;
use App\Models\UserStoryAssignment;
use App\Models\School;
use App\Models\StoryAssignment;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class StoryAssignmentRepository implements StoryAssignmentRepositoryInterface
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = StoryAssignment::query()
                ->with(['teacher.school'])
                ->withCount([
                    'userStoryAssignments as completed_count' => function ($query) {
                        $query->where('completed', true);
                    }, 'userStoryAssignments as uncompleted_count' => function ($query) {
                        $query->where('completed', false);
                    },
                ])
                ->filter($request)->latest();
            $stories = Story::query()->get();

            return DataTables::make($rows)
                ->escapeColumns([])

                ->addColumn('school', function ($row) {
                    $html = '<div class="d-flex flex-column">' ;
                    if (guardIs('manager')){
                        $html .='<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('School') . ':</span>' . $row->teacher->school->name . '</div>';
                    }
                    if (!guardIs('teacher')){
                        $html .='<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Teacher') . ':</span>' . $row->teacher->name . '</div>' ;
                    }
                    $html .= '</div>';
                    return $html;
                })

                ->addColumn('level', function ($row) {
                    $html = '<div class="d-flex flex-column">' ;
                    $html .='<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Students Grade') . ':</span>' .$row->grade->name . '</div>';
                    $html .='<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story Grade').' ('.t('Level').')' . ':</span>' . storyGradesSys()[$row->story_grade] . '</div>' ;

                    $sections = $row->sections && is_array($row->sections) ? implode(', ', array_slice($row->sections, 0, 2)). ' ...' : '-';

                    $html .= '<div class="d-flex"> <span class="fw-bold text-primary pe-1">' . t('Sections') . ':</span>'
                        . $sections  .'</div>';

                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('stories', function ($row) use ($stories){
                    $html = '<div class="d-flex flex-column gap-1">' ;

                    //Stories
                    $story = $stories->whereIn('id',$row->stories_ids)->pluck('name')->toArray();
                    $story = array_slice($story, 0, 2);

                    $html .= '<div class="d-flex"> <span class="fw-bold text-primary pe-1">' . t('Stories') . ':</span>'
                        . implode(', ', $story) . ' ...' .'</div>';

                    $html .= '<div class="d-flex"> <span class="fw-bold text-primary pe-1">' . t('Completed Count') . ':</span><span class="badge badge-success">'. $row->completed_count .'</span></div>';
                    $html .= '<div class="d-flex"> <span class="fw-bold text-primary pe-1">' . t('Uncompleted Count') . ':</span><span class="badge badge-danger">'. $row->uncompleted_count .'</span></div>';

                    $html .= '</div>';
                    return $html;
                })

                ->addColumn('dates', function ($row) {
                    $html = '<div class="d-flex flex-column">' ;
                    $deadlineColor = optional($row->deadline)->isPast() ? 'badge badge-danger' : 'badge badge-success';
                    $html .= '<div class="d-flex mt-1"><span class="fw-bold pe-1 ' . $deadlineColor . '">' .Carbon::parse($row->deadline)->format('Y-m-d').'</span></div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Stories Assignments');
        $grades = Grade::all();
        $compact = compact('title','grades');
        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        } elseif (guardIn(['school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        } elseif (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.story_assignment.index', $compact);
    }

    public function create()
    {
        $title = t('Add Story Assignment');
        $grades = Grade::all();
        $compact = compact('title','grades');
        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        }
        if (guardIn(['manager','school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        }
        if (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.story_assignment.edit', $compact);
    }

    public function store(StoryAssignmentRequest $request)
    {
        $data = $request->validated();
        $students_array = $data['students'];
        $stories = $data['stories_ids'];
        $data['exclude_students'] = $request->get('exclude_students')!=2;
        $story_assignment = StoryAssignment::query()->create($data);

        $students =  User::query()->with(['user_story_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })
            ->get();
        foreach ($students as $student)
        {
            foreach ($stories as $story_id)
            {
                if ($request->get('exclude_students', 1))
                {
                    $pre_assignment = $student->user_story_assignments
                        ->where('story_assignment_id', $story_assignment->id)
                        ->where('story_id', $story_id)->first();

                    if ($pre_assignment)
                    {
                        continue; //skip and not create this lesson
                    }
                }

                $student->user_story_assignments()->create([
                    'story_assignment_id' => $story_assignment->id,
                    'story_id' => $story_id,
                    'deadline' => $request->get('deadline', null),
                    'test_assignment' => 1
                ]);
            }

        }

        return redirect()->route(getGuard().'.story_assignment.index')->with('message',t('Successfully Added'));

    }
    public function edit(Request $request, $id)
    {
        $assignment = StoryAssignment::query()->filter()->findOrFail($id);

        $assignment['students_ids'] = UserStoryAssignment::query()->where('story_assignment_id',$id)
            ->get()->pluck('user_id')->toArray();

        $stories = Story::query()->where('grade',$assignment->story_grade)->get();

        $students = User::query()
            ->whereRelation('teacherUser','teacher_id',$assignment->teacher_id)
            ->when(!is_null($assignment->sections), function (Builder $query) use ($assignment){
                $query->whereIn('section',$assignment->sections);
            })
            ->where('grade_id',$assignment->students_grade)
            ->get();

        $title = t('Edit Story Assignment');
        $grades = Grade::all();
        $compact = compact('title','grades','assignment','stories','students');

        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        }
        if (guardIn(['manager','school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $request->merge(['teacher_id'=>$assignment->teacher_id]);
            $compact['sections'] = getSections(null,$assignment->teacher_id);
        }
        if (guardIs('teacher')) {
            $compact['sections'] = getSections(null,$assignment->teacher_id);
        }
        return view('general.story_assignment.edit',$compact);
    }

    public function update(StoryAssignmentRequest $request, $id)
    {
        $data = $request->validated();
        $assignment = StoryAssignment::find($id);
        $students_array = $data['students'];
        $stories = $assignment->stories_ids;

        $update_data = [
            'teacher_id'=>$data['teacher_id'],
            'students_grade'=>$data['students_grade'],
            'sections'=>$data['sections']??null,
            'deadline'=>$data['deadline'],
            'exclude_students'=>$data['exclude_students']!=2,
        ];
        if (isset($data['sections']) && $data['sections']){
            $update_data['sections']=$data['sections'];
        }if (isset($data['story_grade']) && $data['story_grade']){
            $update_data['story_grade']=$data['story_grade'];
        }
        StoryAssignment::query()->where('id',$id)->update($update_data);

        $students =  User::query()->with(['user_story_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })
            ->get();

        UserStoryAssignment::query()
            ->where('story_assignment_id',$id)
            ->whereNotIn('user_id',$students_array)
            ->delete();

        foreach ($students as $student)
        {
            foreach ($stories as $story_id)
            {
                $student->user_story_assignments()->updateOrCreate(
                    [
                        'story_assignment_id' => $id,
                        'story_id' => $story_id,
                    ]
                    ,
                    [
                        'deadline' => $request->get('deadline', null),
                        'test_assignment' => 1
                    ]);
            }

        }
        return redirect()->route(getGuard().'.story_assignment.index')->with('message',t('Successfully Updated'));


    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required|array']);
        $assignments = StoryAssignment::query()->with('userStoryAssignments')->filter()->get();

        foreach ($assignments as $assignment){
            if ($request->get('with_user_assignments')){
                $assignment->userStoryAssignments()->delete();
            }
            $assignment->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StoryAssignmentExport($request))->download('Stories assignments.xlsx');
    }


}
