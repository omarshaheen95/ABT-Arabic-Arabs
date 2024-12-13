<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Repositories;

use App\Exports\StudentTestExport;
use App\Helpers\Response;
use App\Interfaces\LessonTestRepositoryInterface;
use App\Models\Grade;
use App\Models\MatchResult;
use App\Models\Option;
use App\Models\OptionResult;
use App\Models\QMatch;
use App\Models\Question;
use App\Models\School;
use App\Models\SortResult;
use App\Models\StudentTest;
use App\Models\Teacher;
use App\Models\TrueFalse;
use App\Models\TrueFalseResult;
use App\Models\UserAssignment;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class LessonTestRepository implements LessonTestRepositoryInterface
{
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $rows = UserTest::with(['user.school', 'user.grade', 'lesson.grade'])->filter($request)->latest();

            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('student', function ($row) {
                    $student = '<div class="d-flex flex-column">' .
                        '<div class="d-flex fw-bold">' . $row->user->name . '</div>' .
                        '<div class="d-flex text-danger"><span style="direction: ltr">' . $row->user->email . '</span></div>' .
                        '</div>';
                    return $student;
                })
                ->addColumn('school', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    if (guardIs('manager')) {
                        $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('School') . ':</span>' . $row->user->school->name . '</div>';
                    }
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->user->grade->name . '<span class="fw-bold ms-2 text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Gender') . ':</span>' . $row->user->gender . '<span class="fw-bold ms-2 text-primary pe-1">' . t('Section') . ':</span>' . $row->user->section . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-success pe-1">' . t('Submitted At') . ':</span>' . $row->created_at->format('Y-m-d H:i') . '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('lesson', function ($row) {
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Grade') . ':</span>' . $row->lesson->grade->name . '</div>';
                    $html .= '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . t('Lesson') . ':</span>' . $row->lesson->name . '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('result', function ($row) {
                    $status = $row->status == 'Pass' ? '<span class="badge badge-primary">' . $row->status . '</span>' : '<span class="badge badge-danger">' . $row->status . '</span>';
                    $html = '<div class="d-flex flex-column justify-content-center">' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $row->total_per . '</span></div>' .
                        '<div class="d-flex"><span class="fw-bold text-primary pe-1">' . $status . '</span></div>' .
                        '</div>';
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    //add created_at to the action buttons
                    return $row->action_buttons;
                })
                ->make();
        }
        $title = t('Students lessons tests');
        $grades = Grade::all();
        $compact = compact('title', 'grades');

        if (guardIs('manager')) {
            $compact['schools'] = School::query()->get();
        } elseif (guardIn(['school', 'supervisor'])) {
            $compact['teachers'] = Teacher::query()->filter()->get();
            $compact['sections'] = schoolSections();
        } elseif (guardIs('teacher')) {
            $compact['sections'] = teacherSections();
        }
        return view('general.lesson_test.index', $compact);
    }

    public function preview(Request $request, $id)
    {
        $student_test = UserTest::query()->with(['lesson', 'user'])->filter($request)->findOrFail($id);
        $grade = $student_test->lesson->grade_id;
        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)->get();

        return view('general.lesson_test.preview_answers', compact('student_test', 'grade', 'questions'));
    }

    public function certificate(Request $request, $id)
    {
        $title = 'Student test result';
        $student_test = UserTest::query()->with(['lesson.grade'])->find($id);
        if ($student_test->status != 'Pass')
            return redirect()->route(getGuard() . '.home')->with('message', t('Test dose not has certificates'))->with('m-class', 'error');

        return view('general.user.certificate.lesson_certificate', compact('student_test', 'title'));
    }

    public function export(Request $request)
    {
        $request->validate(['school_id' => 'required']);
        return (new StudentTestExport($request))->download('Students tests.xlsx');
    }

    public function correctingUserTestView(Request $request, $id)
    {
        $student_test = UserTest::query()->with(['lesson', 'user'])->where('id', $id)->first();
        if ($student_test) {
            $questions = Question::with([
                'trueFalse', 'options', 'matches', 'sortWords',
                'true_false_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                }, 'option_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                }, 'match_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                }, 'sort_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                }, 'speaking_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                }, 'writing_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                },
            ])->where('lesson_id', $student_test->lesson_id)->get();
            // dd($questions->toArray());

            $lesson = $student_test->lesson;
            $user = $student_test->user;
            return view('general.correcting_lesson_and_story_test.lesson', compact('questions', 'student_test', 'lesson', 'user'));
        }
        return redirect()->route(getGuard().'.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }
    public function correctingUserTest(Request $request, $id)
    {
        $test = UserTest::find($id);
        $total = 0;

        DB::transaction(function () use ($request, $id, $test,&$total) {

            MatchResult::where('user_test_id',$id)->delete();
            SortResult::where('user_test_id',$id)->delete();

            $questions = Question::with(['trueFalse', 'matches', 'sortWords', 'options'])
                ->where('lesson_id', $test->lesson_id)
                ->get();



            // True/False Results and Total
            $tf_total = 0;
            foreach ($request->get('tf', []) as $key => $result) {
                $main_result = $questions->where('id', $key)->first()->trueFalse;
                $mark = $main_result->result == $result ? $questions->where('id', $key)->first()->mark : 0;
                $tf_total += $mark;
                TrueFalseResult::updateOrCreate(
                    [
                        'question_id' => $key,
                        'user_test_id' => $test->id,
                    ],
                    [
                        'result' => $result,
                    ]
                );
            }
            $total += $tf_total;

            // Option Results and Total
            $o_total = 0;
            foreach ($request->get('option', []) as $key => $option) {
                $main_result = $questions->where('id', $key)->first()->options->where('id', $option)->first();
                $mark = optional($main_result)->result == 1 ? $questions->where('id', $key)->first()->mark : 0;
                $o_total += $mark;

                OptionResult::updateOrCreate(
                    [
                        'question_id' => $key,
                        'user_test_id' => $test->id,
                    ],
                    [
                        'option_id' => $option,
                    ]
                );
            }
            $total += $o_total;
            // Matching Results and Total
            $m_total = 0;
            $m_data = [];
            foreach ($request->get('matching', []) as $key => $match) {
                $matchMark = $questions->where('id', $key)->first()->mark / $questions->where('id', $key)->first()->matches->count();

                foreach ($match as $uid => $match_id) {
                    if (!is_null($match_id)) {
                        $result_id = $questions->where('id', $key)->first()->matches->where('uid', $uid)->first()->id;
                        $m_total += $match_id == $result_id ? $matchMark : 0;

                        $m_data[] = [
                            'question_id' => $key,
                            'match_id' => $match_id,
                            'result_id' => $result_id,
                            'user_test_id' => $test->id,
                            'match_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            MatchResult::insert($m_data);
            $total += $m_total;

            // Sorting Results and Total
            $s_total = 0;
            $s_data = [];
            foreach ($request->get('sorting', []) as $key => $sort) {
                $sort_words = $questions->where('id',$key)->first()->sortWords->pluck('uid')->toArray();
                $student_sort_words = collect($sort)->keys()->toArray();

                if ($student_sort_words === $sort_words) {
                    $mark = $questions->where('id',$key)->first()->mark;
                    $s_total += $mark;
                }

                foreach ($sort as $uid => $value) {
                    if (!is_null($value)) {
                        $result_id = $questions->where('id',$key)->first()->sortWords->where('uid', $uid)->first()->id;
                        $s_data[] = [
                            'question_id' => $key,
                            'sort_word_id' => $result_id,
                            'user_test_id' => $test->id,
                            'sort_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            SortResult::insert($s_data);
            $total += $s_total;

            // Update Test Total and Status
            $mark = $test->lesson->success_mark;

            $test->update([
                'corrected' => 1,
                'approved' => 1,
                'total' => $total,
                'status' => $total >= $mark ? 'Pass' : 'Fail',
            ]);

            // Handle approved tests
            $student_tests = UserTest::query()
                ->where('user_id', $test->user_id)
                ->where('lesson_id', $test->lesson_id)
                ->orderByDesc('total')->get();

            if (optional($student_tests->first())->total >= $mark) {
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $id)
                    ->where('id', $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }

            if ($test->user->teacherUser) {
                updateTeacherStatistics($test->user->teacherUser->teacher_id);
            }

//            dd([$tf_total,$o_total,$m_total,$s_total]);

        });
        return redirect()->route(getGuard().'.lessons_tests.index', $test->id)
            ->with('message', "تم تعديل و حفظ الاختبار بنجاح , العلامة ".' : '.$total)
            ->with('m-class', 'success');
    }
    public function autoCorrectingUsersTests(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $lesson_tests = UserTest::query()->with(['lesson', 'user','matchResults', 'optionResults', 'sortResults', 'trueFalseResults'])
            ->whereDoesntHave('speakingResults')
            ->whereDoesntHave('writingResults')
            ->filter()->get();

        foreach ($lesson_tests as $test) {
            $total = 0;
            $tf_total = 0;
            $o_total = 0;
            $m_total = 0;
            $s_total = 0;

            $questions = Question::with([
                'trueFalse', 'options', 'matches', 'sortWords',
                'true_false_results' => function ($query) use ($test) {
                    $query->where('user_test_id', $test->id);
                }, 'option_results' => function ($query) use ($test) {
                    $query->where('user_test_id', $test->id);
                }, 'match_results' => function ($query) use ($test) {
                    $query->where('user_test_id', $test->id);
                }, 'sort_results' => function ($query) use ($test) {
                    $query->where('user_test_id', $test->id);
                },
            ])->where('lesson_id', $test->lesson_id)->get();


            //True False Questions
            foreach ($test->trueFalseResults as $tf) {
                $question = $questions->where('id', $tf->question_id)->first();
                if ($question->trueFalse->result == $tf->result) {
                    $tf_total += $question->mark;
                    $total += $question->mark;
                }
            }

            //Multiple Choice Questions
            foreach ($test->optionResults as $option) {
                $question = $questions->where('id', $option->question_id)->first();
                $correct = $question->options->where('id', $option->option_id)->first();
                if ($correct && $correct->result == 1) {
                    $o_total += $question->mark;
                    $total += $question->mark;
                }
            }

            //Matching Questions
            foreach ($test->matchResults as $match) {
                $matchMark = $questions->where('id', $match->question_id)->first()->mark / $questions->where('id', $match->question_id)->first()->matches->count();
                $result_id = optional($questions->where('id', $match->question_id)->first()->matches->where('uid', $match->match_answer_uid)->first())->id;
                $mark = $match->match_id == $result_id ? $matchMark : 0;
                $m_total += $mark;
                $total += $mark;
            }

            //Sorting Questions
            foreach ($test->sortResults->pluck('question_id')->unique() as $question_id) {

                $sort_words = $questions->where('id',$question_id)->first()->sortWords->pluck('uid')->toArray();
                $student_sort_words = $questions->where('id',$question_id)->first()->sort_results->pluck('sort_answer_uid')->toArray();
                if ($student_sort_words == $sort_words) {
                    $mark = $questions->where('id',$question_id)->first()->mark;
                    $s_total += $mark;
                    $total += $mark;
                }

            }
            // Update Test Total and Status
            $mark = $test->lesson->success_mark;

            $test->update([
                'corrected' => 1,
                'approved' => 1,
                'total' => $total,
                'status' => $total >= $mark ? 'Pass' : 'Fail',
            ]);

            // Handle approved tests
            $student_tests = UserTest::query()
                ->where('user_id', $test->user_id)
                ->where('lesson_id', $test->lesson_id)
                ->orderByDesc('total')->get();

            if (optional($student_tests->first())->total >= $mark) {
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $test->lesson_id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                UserTest::query()->where('user_id', $test->user_id)
                    ->where('lesson_id', $test->lesson_id)
                    ->where('id', $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }

            if ($test->user->teacherUser) {
                updateTeacherStatistics($test->user->teacherUser->teacher_id);
            }

            //dd(['total'=>$total, 'tf_total'=>$tf_total, 'o_total'=>$o_total, 'm_total'=>$m_total, 's_total'=>$s_total]);

        }
        return Response::response(t('Tests Correcting Successfully').' : '.$lesson_tests->count());
    }


    //For [writing,speaking]
    public function correctingAndFeedbackView(Request $request, $id)
    {
        $title = "عرض اختبار طالب";
        $user_test = UserTest::query()->with(['lesson', 'user'])->filter($request)->findOrFail($id);
        return view('general.lesson_test.correcting_feedback', compact('title', 'user_test'));
    }

    public function correctingAndFeedback(Request $request, $id)
    {
        //Just for[ writing,speaking] Lessons
        $request->validate([
            'mark' => 'required|max:100|min:0',
        ]);
//        dd($request->allFiles());
        $user_test = UserTest::query()->with(['lesson', 'user'])
            ->whereHas('lesson', function (Builder $query) {
                $query->whereIn('lesson_type', ['writing', 'speaking']);
            })
            ->filter($request)->findOrFail($id);

        $record = null;
        if ($request->file('feedback_audio_message')) {
           $record = uploadFile($request->file('feedback_audio_message'),'teachers_records_result')['path'];
        }
        $success_mark = $user_test->lesson->success_mark;
        $mark = $request->get('mark');
        $user_test->update([
            'approved' => 1,
            'corrected' => 1,
            'total' => $mark,
            'status' => $mark >= $success_mark ? 'Pass' : 'Fail',
            'feedback_message' => $request->get('teacher_message', null),
            'feedback_record' => $record,
        ]);

        $student_tests = UserTest::query()
            ->where('user_id', $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->orderByDesc('total')->get();


        if (optional($student_tests->first())->total >= $mark) {
            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id', '<>', $student_tests->first()->id)->update([
                    'approved' => 0,
                ]);

            UserTest::query()
                ->where('user_id', $user_test->user_id)
                ->where('lesson_id', $user_test->lesson_id)
                ->where('id', $student_tests->first()->id)->update([
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

        if ($user_test->user->teacherUser) {
            updateTeacherStatistics($user_test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()
            ->where('user_id', $user_test->user_id)
            ->where('lesson_id', $user_test->lesson_id)
            ->where('test_assignment', 1)
            ->where('done_test_assignment', 0)
            ->first();

        if ($user_assignment) {
            $user_assignment->update([
                'done_test_assignment' => 1,
            ]);

            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment) {
                $user_assignment->update([
                    'completed' => 1,
                ]);
            }
        }

        return Response::response(t('Test correcting successfully'));

    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id' => 'required']);
        $tests = UserTest::query()
            ->when($value = $request->get('school_id'), function (Builder $query) use ($value) {
                $query->whereRelation('user', 'school_id', $value);
            })->when($value = $request->get('teacher_id'), function (Builder $query) use ($value) {
                $query->whereRelation('user.teacherUser', 'teacher_id', $value);
            })->whereIn('id', $request->get('row_id'))->get();
        foreach ($tests as $test) {
            $test->delete();
        }
        return Response::response([Response::DELETED_SUCCESSFULLY]);
    }

}
