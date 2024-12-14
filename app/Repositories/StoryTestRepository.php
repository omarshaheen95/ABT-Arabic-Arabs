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
use App\Models\StoryMatchResult;
use App\Models\StoryOptionResult;
use App\Models\StoryQuestion;
use App\Models\StorySortResult;
use App\Models\StoryTrueFalseResult;
use App\Models\StudentStoryTest;
use App\Models\Teacher;
use App\Models\UserStoryAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
//             dd($questions->toArray());

            $story = $student_test->story;
            $user = $student_test->user;

            return view('general.correcting_lesson_and_story_test.story',compact('questions','student_test','story','user'));
        }
        return redirect()->route(getGuard().'.home')->with('message', t('Not allowed to access for this test'))->with('m-class', 'error');

    }

    public function correcting(Request $request, $id)
    {
        $test = StudentStoryTest::find($id);
        $total = 0;

        DB::transaction(function () use ($request, $id, $test,&$total) {

            StoryMatchResult::where('student_story_test_id',$id)->delete();
            StorySortResult::where('student_story_test_id',$id)->delete();

            $questions = StoryQuestion::with(['trueFalse', 'matches', 'sort_words', 'options'])
                ->where('story_id', $test->story_id)
                ->get();



            // True/False Results and Total
            $tf_total = 0;
            foreach ($request->get('tf', []) as $key => $result) {
                $main_result = $questions->where('id', $key)->first()->trueFalse;
                $mark = $main_result->result == $result ? $questions->where('id', $key)->first()->mark : 0;
                $tf_total += $mark;
                StoryTrueFalseResult::updateOrCreate(
                    [
                        'story_question_id' => $key,
                        'student_story_test_id' => $test->id,
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

                StoryOptionResult::updateOrCreate(
                    [
                        'story_question_id' => $key,
                        'student_story_test_id' => $test->id,
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
                            'story_question_id' => $key,
                            'story_match_id' => $match_id,
                            'story_result_id' => $result_id,
                            'student_story_test_id' => $test->id,
                            'match_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            StoryMatchResult::insert($m_data);
            $total += $m_total;

            // Sorting Results and Total
            $s_total = 0;
            $s_data = [];
            foreach ($request->get('sorting', []) as $key => $sort) {
                $sort_words = $questions->where('id',$key)->first()->sort_words->pluck('uid')->toArray();
                $student_sort_words = collect($sort)->keys()->toArray();

                if ($student_sort_words === $sort_words) {
                    $mark = $questions->where('id',$key)->first()->mark;
                    $s_total += $mark;
                }

                foreach ($sort as $uid => $value) {
                    if (!is_null($value)) {
                        $result_id = $questions->where('id',$key)->first()->sort_words->where('uid', $uid)->first()->id;
                        $s_data[] = [
                            'story_question_id' => $key,
                            'story_sort_word_id' => $result_id,
                            'student_story_test_id' => $test->id,
                            'story_sort_answer_uid' => $uid,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
            StorySortResult::insert($s_data);
            $total += $s_total;

            // Update Test Total and Status

            $mark = 25; // Passing mark
            $test->update([
                'total' => $total,
                'status' => $total >= $mark ? 'Pass' : 'Fail',
                'corrected' => 1
            ]);

            // Update Approved Tests
            $student_tests = StudentStoryTest::where('total', '>=', $mark)
                ->where('user_id', $test->user_id)
                ->where('story_id', $id)
                ->orderByDesc('total')
                ->get();

            if (optional($student_tests->first())->total >= $mark) {
                $approved_test = $student_tests->first();

                StudentStoryTest::where('user_id', $test->user_id)
                    ->where('story_id', $id)
                    ->where('id', '<>', $approved_test->id)
                    ->update(['approved' => 0]);

                $approved_test->update(['approved' => 1]);
            }


//            dd([$tf_total,$o_total,$m_total,$s_total]);

        });
        return redirect()->route(getGuard().'.stories_tests.index', $test->id)
            ->with('message', "تم تعديل و حفظ الاختبار بنجاح , العلامة ".' : '.$total)
            ->with('m-class', 'success');
    }
    public function autoCorrectingTests(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        $story_tests = StudentStoryTest::query()
            ->with(['story', 'user','storyMatchResults', 'storyOptionResults', 'storyTrueFalseResults', 'storySortResults'])
            ->filter()->get();

        foreach ($story_tests as $test) {
            $total = 0;
            $tf_total = 0;
            $o_total = 0;
            $m_total = 0;
            $s_total = 0;

            $questions = StoryQuestion::with([
                'trueFalse', 'options', 'matches', 'sort_words',
                'true_false_results' => function ($query) use ($test) {
                    $query->where('student_story_test_id', $test->id);
                }, 'option_results' => function ($query) use ($test) {
                    $query->where('student_story_test_id', $test->id);
                }, 'match_results' => function ($query) use ($test) {
                    $query->where('student_story_test_id', $test->id);
                }, 'sort_results' => function ($query) use ($test) {
                    $query->where('student_story_test_id', $test->id);
                },
            ])->where('story_id', $test->story_id)->get();


            //True False Questions
            foreach ($test->storyTrueFalseResults as $tf) {
                $question = $questions->where('id', $tf->story_question_id)->first();
                if ($question->trueFalse->result == $tf->result) {
                    $tf_total += $question->mark;
                    $total += $question->mark;
                }
            }

            //Multiple Choice Questions
            foreach ($test->storyOptionResults as $option) {
                $question = $questions->where('id', $option->story_question_id)->first();
                $correct = $question->options->where('id', $option->story_option_id)->first();
                if ($correct && $correct->result == 1) {
                    $o_total += $question->mark;
                    $total += $question->mark;
                }
            }

            //Matching Questions
            foreach ($test->storyMatchResults as $match) {
                $matchMark = $questions->where('id', $match->story_question_id)->first()->mark / $questions->where('id', $match->story_question_id)->first()->matches->count();
                $result_id = optional($questions->where('id', $match->story_question_id)->first()->matches->where('uid', $match->match_answer_uid)->first())->id;
                $mark = $match->story_match_id == $result_id ? $matchMark : 0;
                $m_total += $mark;
                $total += $mark;
            }

            //Sorting Questions
            foreach ($test->storySortResults->pluck('story_question_id')->unique() as $question_id) {

                $sort_words = $questions->where('id',$question_id)->first()->sort_words->pluck('uid')->toArray();
                $student_sort_words = $questions->where('id',$question_id)->first()->sort_results->pluck('story_sort_answer_uid')->toArray();

                if ($student_sort_words == $sort_words) {
                    $mark = $questions->where('id',$question_id)->first()->mark;
                    $s_total += $mark;
                    $total += $mark;
                }

            }
            // Update Test Total and Status
            $mark = 25; // Passing mark
            $test->update([
                'total' => $total,
                'status' => $total >= $mark ? 'Pass' : 'Fail',
                'corrected' => 1
            ]);

            // Update Approved Tests
            $student_tests = StudentStoryTest::where('total', '>=', $mark)
                ->where('user_id', $test->user_id)
                ->where('story_id', $test->story_id)
                ->orderByDesc('total')
                ->get();

            if (optional($student_tests->first())->total >= $mark) {
                $approved_test = $student_tests->first();

                StudentStoryTest::where('user_id', $test->user_id)
                    ->where('story_id', $test->story_id)
                    ->where('id', '<>', $approved_test->id)
                    ->update(['approved' => 0]);

                $approved_test->update(['approved' => 1]);
            }

            //dd(['total'=>$total, 'tf_total'=>$tf_total, 'o_total'=>$o_total, 'm_total'=>$m_total, 's_total'=>$s_total]);

        }
        return Response::response(t('Tests Correcting Successfully').' : '.$story_tests->count());
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
