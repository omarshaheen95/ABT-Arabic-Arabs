<?php

namespace App\Http\Controllers\Manager;

use App\Exports\StudentInformation;
use App\Exports\TeacherExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ImportFileLogDataRequest;
use App\Http\Requests\Manager\ImportFileRequest;
use App\Imports\TeacherImport;
use App\Imports\UserImport;
use App\Models\Grade;
use App\Models\ImportFile;
use App\Models\ImportFileLog;
use App\Models\Package;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImportFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:import files')->except(['destroy']);
        $this->middleware('permission:delete import files')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = ImportFile::with(['school'])->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('original_file_name', function ($row) {
                    return '<a href="' . asset($row->file_path) . '" target="_blank"><span class="font-weight-bold">' . $row->original_file_name . '</span></a>';
                })
                ->addColumn('status', function ($row) {

                    if ($row->status == 'Errors') {
                        return '<a href="' . route('manager.import_files.show', [$row->id]) . '"><span class="badge badge-danger">' . t('Error') . '</span></a>';
                    } elseif ($row->status == 'Failures') {
                        return '<a href="' . route('manager.import_files.show', [$row->id]) . '"><span class="badge badge-danger">' . t('Failures') . '</span></a>';
                    } elseif ($row->status == 'New') {
                        return '<a><span class="badge badge-info">' . t('New') . '</span></a>';
                    } elseif ($row->status == 'Uploading') {
                        return '<a><span class="badge badge-warning">' . t('Uploading') . '</span></a>';
                    } elseif ($row->status == 'Completed') {
                        return '<a><span class="badge badge-success">' . t('Completed') . '</span></a>';
                    } else {
                        return '<a><span class="font-weight-bold">' . t($row->status) . '</span></a>';
                    }
                })
                ->addColumn('process_type', function ($row) {
                    if ($row->process_type == 'create') {
                        return '<a><span class="badge badge-success">' . t('Create') . '</span></a>';
                    } elseif ($row->process_type == 'update') {
                        return '<a><span class="badge badge-info">' . t('Update') . '</span></a>';
                    } elseif ($row->process_type == 'delete') {
                        return '<a><span class="badge badge-danger">' . t('Delete') . '</span></a>';
                    } else {
                        return '<a><span class="font-weight-bold">' . t($row->process_type) . '</span></a>';
                    }
                })
                ->addColumn('model_type', function ($row) {
                    if ($row->model_type == 'User') {
                        return '<a><span class="badge badge-success">' . t('User') . '</span></a>';
                    } elseif ($row->model_type == 'Teacher') {
                        return '<a><span class="badge badge-info">' . t('Teacher') . '</span></a>';
                    } else {
                        return '<a><span class="font-weight-bold">' . t($row->model_type) . '</span></a>';
                    }
                })
                ->addColumn('school', function ($row) {
                    return optional($row->school)->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('List Imported Files');
        $schools = School::query()->active()->get();
        $statuses = [
            'New',
            'Uploading',
            'Completed',
            'Failures',
            'Errors',
        ];
        return view('manager.import.index', compact('title', 'schools', 'statuses'));
    }

    public function create(Request $request)
    {
        $request->validate(['type' => 'required|string|in:Teacher,User']);
        $title = 'Import New File';
        $schools = School::query()->active()->get();
        $note = t('The file format must be one of these formats') . ' : xlsx';
        $type = $request->get('type');
        $packages = Package::query()->get();
        $years = Year::query()->get();
        return view('manager.import.edit', compact('title', 'schools', 'note', 'type', 'packages', 'years'));
    }

    public function store(ImportFileRequest $request)
    {
        $data = $request->validated();
        if ($data['type'] == 'Teacher') {
            return $this->importTeachersExcel($request);
        } else {
            return $this->importStudentsExcel($request);
        }
    }

    public function show($id)
    {
        $file = ImportFile::query()->findOrFail($id);
        $title = t('Show File Errors');
        return view('manager.import.show', compact('file', 'title'));
    }

    public function destroy(Request $request)
    {
        $id = $request->get('row_id');
        $files = ImportFile::query()->whereIn('id', $id)->get();
        $delete_with_rows = request('delete_rows', false);
        foreach ($files as $file) {
            if ($delete_with_rows) {
                $file->update([
                    'delete_with_rows' => 1,
                ]);
                if ($file->model_type == 'Teacher') {
                    Teacher::query()->where('import_file_id', $file->id)->delete();
                } else {
                    User::query()->where('import_file_id', $file->id)->delete();
                }
            } else {
                if ($file->model_type == 'Teacher') {
                    Teacher::query()->where('import_file_id', $file->id)->update([
                        'import_file_id' => null,
                    ]);
                } else {
                    User::query()->where('import_file_id', $file->id)->update([
                        'import_file_id' => null,
                    ]);
                }
            }
            $file->delete();
        }
        return $this->sendResponse($id, 'Deleted Successfully');
    }

    private function importTeachersExcel(Request $request)
    {
        $file = $request->file('import_file');
        //upload file
        $upload_file = uploadFile($file, '/teachers_imported_files');

        $create_file = ImportFile::query()->create([
            'school_id' => $request['school_id'],
            'original_file_name' => $file->getClientOriginalName(),
            'file_name' => $upload_file['name'],
            'file_path' => $upload_file['path'],
            'model_type' => class_basename(Teacher::class),
            'process_type' => $request->get('process_type', 'create'),
            'other_data' => [
                'active_to' => $request['active_to'],
                'last_of_email' => $request['last_of_email'],
            ],
        ]);
        $student_import = new TeacherImport($request, $create_file);
        Excel::import($student_import, public_path($create_file->file_path));
        $file_data = [
            'created_rows_count' => $student_import->getRowsCount(),
            'updated_rows_count' => $student_import->getUpdatedRowsCount(),
            'deleted_rows_count' => $student_import->getDeletedRowsCount(),
            'failed_rows_count' => $student_import->getFailedRowCount(),
        ];

        if ($student_import->getError()) {
            $file_data['status'] = 'Errors'; //Error
            $file_data['error'] = $student_import->getError();
            $create_file->update($file_data);
            return redirect()->route('manager.teacher.index')->withErrors([$student_import->getExceptionMessage()]);
        }

        if ($student_import->getFailures()) {
            $file_data['status'] = 'Failures'; //Failures
            $file_data['failures'] = $student_import->getFailures();
        } else {
            $file_data['status'] = 'Completed'; //Complete
        }
        $create_file->update($file_data);

        return redirect()->route('manager.import_files.index')->with('message', t('Teachers Successfully imported'))->with('m-class', 'success');
    }

    private function importStudentsExcel(Request $request)
    {
        $file = $request->file('import_file');
        //upload file
        $upload_file = uploadFile($file, '/students_imported_files');

        $create_file = ImportFile::query()->create([
            'school_id' => $request['school_id'],
            'original_file_name' => $file->getClientOriginalName(),
            'file_name' => $upload_file['name'],
            'file_path' => $upload_file['path'],
            'model_type' => class_basename(User::class),
            'process_type' => $request->get('process_type', 'create'),
            'other_data' => [
                'package_id' => $request['package_id'],
                'active_to' => $request['active_to'],
                'year_id' => $request['year_id'],
                'back_grade' => $request['back_grade'],
                'last_of_email' => $request['last_of_email'],
                'default_mobile' => $request->get('default_mobile', null),
                'country_code' => $request->get('country_code', null),
                'short_country' => $request->get('short_country', null),
            ],
        ]);
        $student_import = new UserImport($request, $create_file);
        Excel::import($student_import, public_path($create_file->file_path));
        $file_data = [
            'created_rows_count' => $student_import->getRowsCount(),
            'updated_rows_count' => $student_import->getUpdatedRowsCount(),
            'deleted_rows_count' => $student_import->getDeletedRowsCount(),
            'failed_rows_count' => $student_import->getFailedRowCount(),
        ];

        if ($student_import->getError()) {
            $file_data['status'] = 'Errors'; //Error
            $file_data['error'] = $student_import->getError();
            $create_file->update($file_data);
            return redirect()->route('manager.student.index')->withErrors([$student_import->getExceptionMessage()]);
        }

        if ($student_import->getFailures()) {
            $file_data['status'] = 'Failures'; //Failures
            $file_data['failures'] = $student_import->getFailures();
        } else {
            $file_data['status'] = 'Completed'; //Complete
        }
        $create_file->update($file_data);

        return redirect()->route('manager.import_files.index')->with('message', t('Students Successfully imported'))->with('m-class', 'success');
    }

    public function exportDataAsExcel(Request $request)
    {
        $request->validate([
            'import_file_id' => 'required|exists:import_files,id',
        ]);

        $imported_file = ImportFile::query()->findOrFail($request->import_file_id);
        if ($imported_file->model_type == 'User') {
            $file_name = "Students Information.xlsx";
            if ($imported_file->school_id) {
                $school = School::query()->findOrFail($imported_file->school_id);
                $file_name = $school->name . " Students Information.xlsx";
            }
            $request['orderBy'] = 'grade';
            $request['orderBy2'] = 'section';
            return (new StudentInformation($request))->download($file_name);
        } else {
            return (new TeacherExport($request))->download('Teachers Information.xlsx');
        }
    }

    public function usersCards($id)
    {
        $students = User::query()->with(['grade','school', 'teacher'])
            ->orderBy('grade_id')
            ->orderBy('section')
            ->where('import_file_id', $id)->get()->chunk(6);
        $imported_file = ImportFile::query()->findOrFail($id);
        $student_login_url = config('app.url') . '/login';
        $school = School::find($imported_file->school_id);
        $title = $school ? $school->name . ' | ' . t('Students Cards') : t('Students Cards');
        return view('general.cards_and_qr', compact('students', 'student_login_url', 'school', 'title'));
    }


    //Error Logs
    public function showFromErrors($id)
    {
        $studentDataFile = ImportFile::with('school')->findOrFail($id);
        if (request()->ajax()) {
            $rows = ImportFileLog::query()
                ->filter()
                ->where('import_file_id', $id)
                ->latest();
            $teachers = Teacher::query()->where('school_id', $studentDataFile->school_id)->get();
            $grades = Grade::query()->get();

            $datatable = DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('data', function ($row)use ($grades, $teachers) {
                    $inputs  = ['Name', 'Email', 'Mobile', 'Password', 'Grade', 'Alternative Grade', 'Section', 'Nationality', 'Gender', 'Active', 'Student ID', 'Teacher'];
                    $inputs_with_values = [];
                    foreach ($inputs as $input) {
                        $row_input_value = array_filter($row->data['inputs'], function ($item) use ($input) {
                            return $item['key'] === $input;
                        });
                        $row_input_value = collect($row_input_value)->first()['value']??null;
                        $inputs_with_values[$input] = ['id'=>$row->id,'key'=>$input,'value'=>$row_input_value];
                    }
                    return view('manager.import.logs.student_data_form', compact('row','grades','teachers','inputs_with_values'));
                })
                ->addColumn('errors', function ($row) {
                    $html = '';
                    foreach ($row->data['errors'] as $errors) {
                        $html .= '<ul><li class="text-danger">';
                        $html .= '<ul><li class="text-danger">' . implode('</li><li class="text-danger">', $errors) . '</li></ul>';
                        $html .= '</li></ul>';
                    }
                    return $html;
                })
                ->addColumn('row_number', function ($row) {
                    $num = $row->row_num;
                    return "<span data-num='$num' class='row_num'>" . $num . "</span>";
                });
            return $datatable->make();
        }


        $title = t('Show Student Data File');
        return view('manager.import.logs.show_errors', compact('title','studentDataFile'));
    }

    public function saveLogs(ImportFileLogDataRequest $request)
    {
        $data = $request->validated();
        $user_data_file = ImportFile::query()->findOrFail($data['student_data_file_id']);
        $counts = 0;
        $rows_num = [];
        foreach ($data['student'] as $std) {
            $user = new User($std);


            //check row if has teacher
            if (isset($std['teacher'])) {
                $teacher = Teacher::query()->where('email', $std['teacher'])->where('school_id', $user_data_file->school_id)->first();
                if ($teacher) {
                    $user->teacherUser()->create([
                        'teacher_id' => $teacher->id,
                    ]);
                }
            }


            //Alternate Grade
            $alternative_grade = isset($std['alternative_grade']) && !is_null($std['alternative_grade']) ? $std['alternative_grade'] : null;

            $grade = abs((int)filter_var($std['grade'], FILTER_SANITIZE_NUMBER_INT));// - 1;
            if ($user_data_file->other_data['back_grade'] > 0 ) {
                $grade -= $user_data_file->other_data['back_grade'];
            }
            if ($grade == 0)
            {
                $grade = 13;
            }


            $user->name = $std['name']?? null;
            $user->email = $std['email']?? null;
            $user->mobile = $std['mobile']?? null;
            $user->id_number = $std['student_id']?? null;
            $user->password =bcrypt('123456');
            $user->grade_id = $grade;
            $user->alternate_grade_id = $alternative_grade;
            $user->section = $std['section']?? null;
            $user->gender = $std['gender']?? null;
            $user->nationality = $std['nationality']?? null;
            $user->active_from = Carbon::now();
            $user->active = true;
            $user->active_to =$user_data_file->other_data['active_to'];
            $user->package_id = $user_data_file->other_data['package_id'];
            $user->year_id = $user_data_file->other_data['year_id'];
            $user->country_code= $user_data_file->other_data['country_code'];
            $user->short_country= $user_data_file->other_data['short_country'];

            $user->school_id = $user_data_file->school_id;
            $user->import_file_id = $user_data_file->id;

            $user->save();
            $rows_num[] = $std['row_num'];
            $counts++;
        }
        ImportFileLog::query()->where('import_file_id', $user_data_file->id)->whereIn('row_num', $rows_num)->delete();
        $user_data_file->update([
            'failed_rows_count' => $user_data_file->failed_rows_count - $counts,
            'created_rows_count' => $user_data_file->created_rows_count + $counts
        ]);
        return redirect()->route('manager.import_files.index')->with('message', t('Data Saved Successfully'));

    }

    public function deleteLogs(Request $request)
    {
        $id = $request->get('row_id', []);
        $logs = ImportFileLog::query()->whereIn('id', $id)->get();
        $file = $logs->first()->importFile;
        foreach ($logs as $log) {
            $log->delete();
        }
        $file->update([
            'failed_rows_count' => $file->failed_rows_count - count($logs),
        ]);
        return $this->sendResponse(null, 'Deleted Successfully');
    }

}
