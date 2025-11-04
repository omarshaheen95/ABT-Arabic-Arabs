<?php

namespace App\Repositories;

use App\Exports\LessonAssignmentExport;
use App\Exports\StudentAssignmentExport;

use App\Helpers\Response;
use App\Http\Requests\General\LessonAssignmentRequest;
use App\Interfaces\LessonAssignmentRepositoryInterface;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonAssignment;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class LessonAssignmentRepository implements LessonAssignmentRepositoryInterface
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $lessons = Lesson::pluck('name', 'id'); // ✅ simple key-value map

            $rows = LessonAssignment::query()
                ->with(['teacher.school', 'grade'])
                ->withCount([
                    'userAssignments as completed_count' => function ($query) {
                        $query->where('completed', true);
                    }, 'userAssignments as uncompleted_count' => function ($query) {
                        $query->where('completed', false);
                    },
                ])
                ->filter($request)
                ->latest();

            return DataTables::of($rows)
                ->addColumn('school', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    if (guardIs('manager')) {
                        $html .= '<div><span class="fw-bold text-primary pe-1">'.t('School').':</span> '.$row->teacher->school->name.'</div>';
                    }
                    if (!guardIs('teacher')) {
                        $html .= '<div><span class="fw-bold text-primary pe-1">'.t('Teacher').':</span> '.$row->teacher->name.'</div>';
                    }
                    return $html.'</div>';
                })
                ->addColumn('lesson', function ($row) use ($lessons) {
                    $lessonNames = collect($row->lessons_ids)->map(function ($id) use ($lessons) {
                        return $lessons[$id] ?? null;
                    })->filter()->take(2);
                    return '<div><span class="fw-bold text-primary pe-1">'.t('Lessons').':</span>'
                        .implode(', ', $lessonNames).' ...</div>'
                        .'<div><span class="fw-bold text-primary pe-1">'.t('Completed').':</span><span class="badge badge-success">'.$row->completed_count.'</span></div>'
                        .'<div><span class="fw-bold text-primary pe-1">'.t('Uncompleted').':</span><span class="badge badge-danger">'.$row->uncompleted_count.'</span></div>';
                })
                ->make(true);
        }

        // Non-AJAX part unchanged
        $title = t('Lessons Assignments');
        $grades = Grade::all();
        $compact = compact('title', 'grades');
        if (guardIs('manager')) $compact['schools'] = School::all();
        elseif (guardIn(['school', 'supervisor'])) {
            $compact['teachers'] = Teacher::filter()->get();
            $compact['sections'] = schoolSections();
        } elseif (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.lesson_assignment.index', $compact);
    }

    public function create()
    {
        $title = t('Add New Assignment');
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
        $compact['years'] = Year::query()->latest()->get();

        return view('general.lesson_assignment.edit', $compact);
    }

    public function store(LessonAssignmentRequest $request)
    {
        $data = $request->validated();
        $students_array = $data['students'];
        $lessons = $data['lessons_ids'];
        $data['test_assignment'] = true;
        $data['exclude_students'] = $request->get('exclude_students')!=2;

        $lesson_assignment = LessonAssignment::query()->create($data);


        $students =  User::query()->with(['user_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })
            ->get();
        foreach ($students as $student)
        {
            foreach ($lessons as $lesson)
            {
                if ($request->get('exclude_students', 1))
                {
                    $pre_assignment = $student->user_assignments
                        ->where('lesson_assignment_id', $lesson_assignment->id)
                        ->where('lesson_id', $lesson)->first();

                    if ($pre_assignment)
                    {
                        continue; //skip and not create this lesson
                    }
                }

                $student->user_assignments()->create([
                    'lesson_assignment_id' => $lesson_assignment->id,
                    'lesson_id' => $lesson,
                    'deadline' => $request->get('deadline', null),
                    'test_assignment' => $data['test_assignment'],
                ]);
            }

        }

        return redirect()->route(getGuard().'.lesson_assignment.index')->with('message',t('Successfully Added'));

    }
    public function edit(Request $request, $id)
    {
        $assignment = LessonAssignment::query()->with('grade')->filter()->findOrFail($id);

        $assignment['students_ids'] = UserAssignment::query()->where('lesson_assignment_id',$id)
            ->get()->pluck('user_id')->toArray();

        $grades = Grade::all();
        $lessons = Lesson::query()->where('grade_id',$assignment->grade_id)->get();

        $students = User::query()
            ->whereRelation('teacherUser','teacher_id',$assignment->teacher_id)
            ->when(!is_null($assignment->sections), function (Builder $query) use ($assignment){
                $query->whereIn('section',$assignment->sections);
            })
            ->where('grade_id',$assignment->grade_id)
            ->get();

        $title = t('Edit Lesson Assignment');

        $compact = compact('title','assignment','grades','lessons','students');

        //get lesson type for this assignment
        $lesson_type = $lessons->whereIn('id',$assignment->lessons_ids)->groupBy('lesson_type')->keys();
        if ($lesson_type->count() == 1){
            $compact['lesson_type'] = $lesson_type->first();
        }

        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        }
        if (guardIn(['manager','school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = getSections(null,$assignment->teacher_id);
        }
        if (guardIs('teacher')) {
            $compact['sections'] = getSections(null,$assignment->teacher_id);
        }
        $compact['years'] = Year::query()->latest()->get();

        return view('general.lesson_assignment.edit',$compact);
    }

    public function update(LessonAssignmentRequest $request, $id)
    {
        $data = $request->validated();
        $assignment = LessonAssignment::find($id);
        $students_array = $data['students'];
        $lessons = $assignment->lessons_ids;
        $data['test_assignment'] = true;


        $update_data = [
            'teacher_id'=>$data['teacher_id'],
            'deadline'=>$data['deadline'],
            'exclude_students'=>$data['exclude_students']!=2,
            'test_assignment'=>$data['test_assignment'],
        ];
        if (isset($data['grade_id']) && $data['grade_id']){
            $update_data['grade_id']=$data['grade_id'];
        }
        if (isset($data['sections']) && $data['sections']){
            $update_data['sections']=$data['sections'];
        }
        LessonAssignment::query()->where('id',$id)->update($update_data);

        $students =  User::query()->with(['user_assignments'])
            ->when(count($students_array), function (Builder $query) use ($students_array){
                $query->whereIn('id', $students_array);
            })
            ->get();

        UserAssignment::query()
            ->where('lesson_assignment_id',$id)
            ->whereNotIn('user_id',$students_array)
            ->delete();

        foreach ($students as $student)
        {
            foreach ($lessons as $lesson)
            {
                $student->user_assignments()->updateOrCreate(
                    [
                        'lesson_assignment_id' => $id,
                        'lesson_id' => $lesson,
                    ]
                    ,
                    [
                        'deadline' => $request->get('deadline', null),
                        'test_assignment' => $data['test_assignment'],
                    ]);
            }

        }
        return redirect()->route(getGuard().'.lesson_assignment.index')->with('message',t('Successfully Updated'));


    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required|array']);
        $assignments = LessonAssignment::query()->with('userAssignments')->filter()->get();

        foreach ($assignments as $assignment){
            if ($request->get('with_user_assignments')){
                $assignment->userAssignments()->delete();
            }
            $assignment->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new LessonAssignmentExport($request))->download('Lessons assignments.xlsx');
    }


}
