<?php

namespace App\Repositories;


use App\Exports\MotivationalCertificateExport;
use App\Helpers\Response;
use App\Http\Requests\General\MotivationalCertificateRequest;
use App\Interfaces\MotivationalCertificateRepositoryInterface;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\MotivationalCertificate;
use App\Models\School;
use App\Models\Story;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class MotivationalCertificateRepository implements MotivationalCertificateRepositoryInterface
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = MotivationalCertificate::query()
                ->with(['model', 'user.school', 'teacher'])
                ->filter($request)->with(['user', 'model'])
                ->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('student', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex fw-bold">' . $row->user->name . '</div>';
                    $html .= '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>';
                    if (guardIs('manager')) {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('School') . ':</span>' . optional($row->user)->school->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">'.t('Grade').':</span>'.$row->user->grade->name.'<span class="fw-bold ms-2 text-primary pe-1">'.t('Section').':</span>'.$row->user->section.'</div>';

                        $html .= '</div>';
                    return $html;
                })
                ->addColumn('dates', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    if (!guardIs('teacher')){
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Teacher') . ':</span>' . $row->teacher->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-success pe-1">' . t('Granted In') . ':</span>' . $row->granted_in . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Created At') . ':</span>' . Carbon::parse($row->created_at)->toDateString() . '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('model', function ($row) {
                    if ($row->model_type == Lesson::class) {
                        $html = '<div class="d-flex flex-column">' .
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . optional($row->model)->grade->name . '</div>' .
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . optional($row->model)->name . '</div>' .
                            '</div>';
                        return $html;
                    } else {
                        $html = '<div class="d-flex flex-column">' .
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Level') . ':</span>' . optional($row->model)->grade_name . '</div>' .
                            '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Story') . ':</span>' . optional($row->model)->name . '</div>' .
                            '</div>';
                        return $html;
                    }
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Motivational Certificates');
        $grades = Grade::query()->get();

        $compact = compact('title','grades');
        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        } elseif (guardIn(['school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        } elseif (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.motivational_certificates.index', $compact);
    }

    public function create()
    {
        $title = t('Add Certificate');
        $cer_type = request()->get('cer_type', 'lesson');
        $grades = Grade::query()->get();

        $compact = compact('title','cer_type','grades');

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
        return view('general.motivational_certificates.edit', $compact);
    }

    public function store(MotivationalCertificateRequest $request)
    {
        $data = $request->validated();
        if ($data['model_type'] == 'lesson') {
            $items = $data['lesson_id'];
            $data['model_type'] = Lesson::class;
        } else {
            $items = $data['story_id'];
            $data['model_type'] = Story::class;
        }
        //remove all from array
        if (in_array('all', $data['students'])) {
            //remove of all from array
            $data['students'] = User::query()->filter(request())->pluck('id')->toArray();
        }
        foreach ($data['students'] as $student) {
            foreach ($items as $item) {
                MotivationalCertificate::query()->updateOrCreate([
                    'teacher_id' => $data['teacher_id'],
                    'user_id' => $student,
                    'model_type' => $data['model_type'],
                    'model_id' => $item,
                ], [
                    'teacher_id' => $data['teacher_id'],
                    'user_id' => $student,
                    'model_type' => $data['model_type'],
                    'model_id' => $item,
                    'granted_in' => $data['granted_in'],
                ]);
            }

        }
        return redirect()->route(getGuard().'.motivational_certificates.index')->with('message', t('Successfully Added'));
    }

    public function show($id)
    {
        $title = t('Show Certificate');
        $certificate = MotivationalCertificate::query()->filter()->findOrFail($id);
        return view('general.motivational_certificates.certificate', compact('certificate', 'title'));
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        MotivationalCertificate::destroy($request->get('row_id'));
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'model_type' => 'required',
            'school_id' => 'required',
        ]);
        return (new MotivationalCertificateExport($request))->download('Motivational Certificates.xlsx');
    }


}
