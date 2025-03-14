<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QMatch;
use App\Models\Option;
use App\Models\Question;
use App\Models\SortWord;
use App\Models\TrueFalse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit lesson assessment')->only('index');
        $this->middleware('permission:edit lesson assessment')
            ->only(['edit','update','deleteAQuestionAttachment','deleteAMatchImage','removeASortWord']);
        $this->middleware('permission:lesson review')->only('preview');
        $this->middleware('permission:delete lesson assessment')->only('destroy');
    }
    public function index(Request $request, $lesson)
    {
        if (request()->ajax())
        {
            $request->merge(['lesson_id' => $lesson]);
            $rows = Question::query()->filter($request)->latest();
            return DataTables::make($rows)
                ->escapeColumns([])
                ->addColumn('type_name', function ($row) {
                    return $row->type_name;
                })
                ->addColumn('created_at', function ($row){
                    return Carbon::parse($row->created_at)->toDateString();
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        $lesson = Lesson::query()->findOrFail($lesson);
        $title = t('Show Assessment Questions');
        return view('manager.lesson.assessment.index', compact('title', 'lesson'));
    }

    public function edit($id,$question_id=null)
    {
        $title = t('Assessment');
        $lesson = Lesson::query()->with(['media'])->findOrFail($id);
        if (!in_array($lesson->lesson_type, ['reading', 'listening', 'grammar', 'dictation', 'rhetoric'])) {
            if ($lesson->lesson_type == 'writing')
            {
                $questions = Question::query()->where('type', 5)->where('lesson_id', $lesson->id)->with(['media'])->get();
                return view('manager.lesson.assessment.assessment_listening_speaking', compact('title', 'lesson', 'questions'));
            }
            if ($lesson->lesson_type == 'speaking')
            {
                $questions = Question::query()->where('type', 6)->where('lesson_id', $lesson->id)->with(['media'])->get();
                return view('manager.lesson.assessment.assessment_listening_speaking', compact('title', 'lesson', 'questions'));
            }
            return $this->redirectWith(false, 'manager.lesson.index', 'لا يمكن اضافة اختبار لهذا الدرس', 'error');
        }

        if (in_array($lesson->lesson_type, [ 'grammar', 'dictation', 'rhetoric']))
        {
            if ($lesson->grade->grade_number <= 3)
            {
                $questions = Question::with(['trueFalse','options','matches.media','media'])
                    ->where('lesson_id', $lesson->id)
                    ->when($question_id,function ($query) use ($question_id){
                        $query->where('id', $question_id);
                    })
                    ->whereIn('type',[1,2,3])
                    ->get();
                $t_f_questions = $questions->where('type', 1);//->sum('mark');
                $c_questions = $questions->where('type', 2);//->sum('mark');
                $m_questions = $questions->where('type', 3);//->sum('mark');
                $total_count = $t_f_questions->count() + $c_questions->count() + $m_questions->count();
                $data_count = [
                    'choose' =>   $total_count ? $c_questions->count() :6,
                    'match' =>  $total_count ? $m_questions->count() :4,
                    'true_false' =>  $total_count ? $t_f_questions->count() :5
                ];
                $compact = compact('title', 'lesson', 'm_questions', 't_f_questions', 'c_questions', 'data_count');
                if($question_id){
                    return view('manager.lesson.assessment.edit_specific_question', $compact);
                }
                return view('manager.lesson.assessment.new_skills_assessment', $compact);
            }else{
                $questions = Question::with(['trueFalse','options','media','matches', 'matches.media'])
                    ->where('lesson_id', $lesson->id)
                    ->when($question_id,function ($query) use ($question_id){
                        $query->where('id', $question_id);
                    })
                    ->whereIn('type',[1,2,3])
                    ->get();
                $t_f_questions = $questions->where('type', 1);//->sum('mark');
                $c_questions = $questions->where('type', 2);//->sum('mark');
                $m_questions = $questions->where('type', 3);//->sum('mark');
                $total_count = $t_f_questions->count() + $c_questions->count() + $m_questions->count();
                $data_count = [
                    'choose' =>   $total_count ? $c_questions->count() :8,
                    'match' =>  $total_count ? $m_questions->count() :0,
                    'true_false' =>  $total_count ? $t_f_questions->count() :7
                ];
                $compact = compact('title', 'lesson', 't_f_questions', 'c_questions', 'data_count');
                if($question_id){
                    return view('manager.lesson.assessment.edit_specific_question', $compact);
                }
                return view('manager.lesson.assessment.new_skills_assessment', $compact);
            }
        }

        $questions = Question::with(['trueFalse','options','matches', 'matches.media','sortWords','media'])
            ->where('lesson_id', $lesson->id)
            ->when($question_id,function ($query) use ($question_id){
                $query->where('id', $question_id);
            })
            ->get();
        $t_f_questions = $questions->where('type', 1);//->sum('mark');
        $c_questions = $questions->where('type', 2);//->sum('mark');
        $m_questions = $questions->where('type', 3);//->sum('mark');
        $s_questions = $questions->where('type', 4);//->sum('mark');
        $compact = compact('title', 'lesson', 'm_questions', 't_f_questions', 'c_questions', 's_questions');
        if($question_id){
            return view('manager.lesson.assessment.edit_specific_question', $compact);
        }
        return view('manager.lesson.assessment.assessment', $compact);
    }

    public function update(Request $request, $id, $type)
    {
        $lesson = Lesson::query()->findOrFail($id);
        switch ($type) {
            case 1:
//                dd($request->all());
                $this->trueFalseAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
            case 2:
                $this->optionsAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
            case 3:
//                dd($request->all());
                $this->matchAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
            case 4:
//                dd($request->all());
                $this->sortAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
            case 'writing':
//                dd($request->all());
                $this->writingAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
            case 'speaking':
//                dd($request->all());
                $this->speakingAssessment($request, $lesson);
                return redirect()->route('manager.lesson.assessment.edit', $lesson->id)->with('message', t('Successfully Updated'));
                return $this->sendResponse(null,t('Successfully Updated'));
        }
    }

    public function destroy(Request $request)
    {
        $request->validate(['row_id'=>'required']);
        Question::destroy($request->get('row_id'));
        return $this->sendResponse(null,t('Successfully Deleted'));
    }
    public function deleteAQuestionAttachment($id)
    {
        $question = Question::query()->findOrFail($id);
        $question->clearMediaCollection('imageQuestion');
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function deleteAMatchImage($id)
    {
        $match = QMatch::query()->findOrFail($id);
        $match->clearMediaCollection('match');
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function removeASortWord($id)
    {
        $sort_word = SortWord::query()->findOrFail($id);
        $sort_word->delete();
        return $this->sendResponse(null,t('Successfully Deleted'));
    }

    public function matchAssessment(Request $request, $lesson)
    {
        $m_questions = $request->get('m_question', []);
        $m_question_options = $request->get('m_q_option', []);
        $m_q_answer = $request->get('m_q_answer', []);
        $marks = $request->get("mark", []);

        foreach ($m_questions as $key => $m_question) {
            $question = Question::query()->create([
                'content' => $m_question ? $m_question : 'no question',
                'type' => 3,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 8,
            ]);
            if ($request->hasFile("m_q_attachment.$key")) {
                $question->addMediaFromRequest("m_q_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }


            $m_q_options = isset($m_question_options[$key]) ? $m_question_options[$key] : [];
            foreach ($m_q_options as $m_a_key => $m_q_option) {
                $result = $m_q_answer[$key][$m_a_key];
//                        dd($m_q_option);
                $match = QMatch::query()->create([
                    'question_id' => $question->id,
                    'uid' => \Str::uuid(),
                    'content' => $m_q_option,
                    'result' => $result,
                ]);
                if ($request->hasFile("m_q_image.$key.$m_a_key")) {
                    $match->addMediaFromRequest("m_q_image.$key.$m_a_key")
                        ->toMediaCollection('match');
                }
            }
        }

        $m_questions = $request->get('old_m_question', []);
        $m_question_options = $request->get('old_m_q_option', []);
        $m_q_answer = $request->get('old_m_q_answer', []);
        $marks = $request->get("mark", []);

        foreach ($m_questions as $key => $m_question) {
            $question = Question::query()->find($key);
            if ($question) {
                $question->update([
                    'content' => $m_question ? $m_question : 'no question',
                    'mark' => $marks[$key] ?? 8,
                ]);
                if ($request->hasFile("old_m_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_m_q_attachment.$key")
                        ->toMediaCollection('imageQuestion');
                }
            }
        }
        foreach ($m_question_options as $m_a_key => $m_q_option) {
            $result = $m_q_answer[$m_a_key];

            $match = QMatch::query()->find($m_a_key);
            if ($match) {
                $match->update([
                    'content' => $m_q_option,
                    'result' => $result,
                ]);
                if ($request->hasFile("old_m_q_image.$m_a_key")) {
                    $match->addMediaFromRequest("old_m_q_image.$m_a_key")
                        ->toMediaCollection('match');
                }
            }

        }

        return true;
    }

    public function trueFalseAssessment(Request $request, $lesson)
    {
        $true_false_questions = $request->get('t_f_question', []);
        $true_false_answers = $request->get('t_f', []);
        $marks = $request->get("mark", []);
        foreach ($true_false_questions as $key => $true_false_question) {
            $question = Question::query()->create([
                'content' => isset($true_false_question) ? $true_false_question : 'no question',
                'type' => 1,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 6,
            ]);
            if ($request->hasFile("t_f_q_attachment.$key")) {
                $question->addMediaFromRequest("t_f_q_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }
            TrueFalse::query()->create([
                'result' => isset($true_false_answers[$key]) && $true_false_answers[$key] == 1 ? 1 : 0,
                'question_id' => $question->id,
            ]);

        }


        $true_false_questions = $request->get('old_t_f_question', []);
        $true_false_answers = $request->get('old_t_f', []);
        foreach ($true_false_questions as $key => $true_false_question) {
            $question = Question::query()->find($key);
            if ($question)
            {
                $question->update([
                    'content' => isset($true_false_question) ? $true_false_question : 'no question',
                    'mark' => $marks[$key] ?? 6,
                ]);
                if ($request->hasFile("old_t_f_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_t_f_q_attachment.$key")
                        ->toMediaCollection('imageQuestion');
                }
            }



        }
        foreach($true_false_answers as $key => $true_false_answer)
        {
            $trueFalse = TrueFalse::query()->where('question_id', $key)->first();
            if ($trueFalse)
            {
                $trueFalse->update([
                    'result' => $true_false_answer == 1 ? 1 : 0,
                ]);
            }
        }
        return true;
    }

    public function optionsAssessment(Request $request, $lesson)
    {
        $c_questions = $request->get('c_question', []);
        $marks = $request->get("mark", []);

        foreach ($c_questions as $key => $c_question) {
            $question = Question::query()->create([
                'content' => $c_question ? $c_question : 'no question',
                'type' => 2,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 7,
            ]);
            if ($request->hasFile("c_q_attachment.$key")) {
                $question->addMediaFromRequest("c_q_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }

            $options = $request->get('c_q_option')[$key];
            foreach ($options as $o_key => $option) {
                Option::query()->create([
                    'content' => $option,
                    'result' => $o_key + 1 == $request->get('c_q_a')[$key] ? 1 : 0,
                    'question_id' => $question->id,
                ]);
            }
        }

        $c_questions = $request->get('old_c_question', []);

        foreach ($c_questions as $key => $c_question) {
            $question = Question::query()->find($key);
            if ($question)
            {

                $question->update([
                    'content' => $c_question ? $c_question : 'no question',
                    'mark' => $marks[$key] ?? 7,
                ]);
                if ($request->hasFile("old_c_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_c_q_attachment.$key")
                        ->toMediaCollection('imageQuestion');
                }
            }
        }
        $options = $request->get('old_c_q_option', []);
        $options_results = $request->get('old_c_q_a', []);
        foreach ($options as $key => $c_options) {
            foreach ($c_options as $o_key => $option) {
                $answer_option = Option::query()->find($o_key);
                if ($answer_option) {
                    $answer_option->update([
                        'content' => $option,
                        'result' => $o_key == $options_results[$key] ? 1 : 0,
                    ]);
                }
            }
        }

        return true;
    }

    public function sortAssessment(Request $request, $lesson)
    {
        $s_questions = $request->get('s_question', []);
        $s_q_options = $request->get('s_q_option', []);
        $marks = $request->get("mark", []);
        $counter = 1;
        foreach ($s_questions as $key => $s_question) {
            $question = Question::query()->create([
                'content' => $s_question ? $s_question : 'no question',
                'type' => 4,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key] ?? 8,
            ]);
            if ($request->hasFile("s_q_attachment.$key")) {
                $question->addMediaFromRequest("s_q_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }

            $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];

            foreach ($question_option as $m_a_key => $option) {
                SortWord::query()->create([
                    'question_id' => $question->id,
                    'content' => $option,
                    'uid' => \Str::uuid(),
                    'ordered' => $counter,
                ]);
                $counter++;
            }
            $counter = 1;
        }

        $s_questions = $request->get('old_s_question', []);
        $s_q_options = $request->get('s_q_option', []);
        $old_s_q_options = $request->get('old_s_q_option', []);

        foreach ($s_questions as $key => $s_question) {
            $question = Question::query()->where('lesson_id', $lesson->id)->find($key);
            if ($question) {
                $question->update([
                    'content' => $s_question ? $s_question : 'no question',
                    'mark' => $marks[$key] ?? 8,
                ]);

                if ($request->hasFile("old_s_q_attachment.$key")) {
                    $question->addMediaFromRequest("old_s_q_attachment.$key")
                        ->toMediaCollection('imageQuestion');
                }

                $question_option = isset($s_q_options[$key]) ? $s_q_options[$key] : [];
                $counter = $question->sortWords()->count();
                foreach ($question_option as $m_a_key => $option) {
                    $counter++;
                    SortWord::query()->create([
                        'question_id' => $question->id,
                        'content' => $option,
                        'uid' => \Str::uuid(),
                        'ordered' => $counter,
                    ]);
                }
            }
        }
        foreach ($old_s_q_options as $key => $old_s_q_option) {
            foreach ($old_s_q_option as $o_key => $s_q_option) {
                $option = SortWord::query()->find($o_key);
                if ($option) {
                    $option->update(['content' => $s_q_option]);
                }
            }
        }
    }

    public function writingAssessment(Request $request, $lesson)
    {
        $lesson->update([
            'content' => $request->get('content', null),
        ]);

        $questions = $request->get('old_questions', []);
        $marks = $request->get("old_mark", []);

        Question::query()->where('lesson_id', $lesson->id)->where('type', 5)->whereNotIn('id', array_keys($questions))->delete();
        foreach ($questions as $key => $question) {
            $old_question = Question::query()->find($key);
            if ($old_question)
            {
                $old_question->update([
                    'content' => isset($question) ? $question : 'no question',
                    'lesson_id' => $lesson->id,
                    'mark' => $marks[$key],
                ]);
            }
            if ($request->hasFile("old_attachment.$key")) {
                $question->addMediaFromRequest("old_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }
        }

        $questions = $request->get('questions', []);
        $marks = $request->get("mark", []);
        foreach ($questions as $key => $question) {
            $question = Question::query()->create([
                'content' => isset($question) ? $question : 'no question',
                'type' => 5,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key],
            ]);
            if ($request->hasFile("attachment.$key")) {
                $question->addMediaFromRequest("attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }
        }



        return true;

    }

    public function speakingAssessment(Request $request, $lesson)
    {
        $lesson->update([
            'content' => $request->get('content', null),
        ]);

        $questions = $request->get('old_questions', []);
        $marks = $request->get("old_mark", []);

        Question::query()->where('lesson_id', $lesson->id)->where('type', 6)->whereNotIn('id', array_keys($questions))->delete();
        foreach ($questions as $key => $question) {
            $old_question = Question::query()->find($key);
            if ($old_question)
            {
                $old_question->update([
                    'content' => isset($question) ? $question : 'no question',
                    'lesson_id' => $lesson->id,
                    'mark' => $marks[$key],
                ]);
            }
            if ($request->hasFile("old_attachment.$key")) {
                $old_question->addMediaFromRequest("old_attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }
        }

        $questions = $request->get('questions', []);
        $marks = $request->get("mark", []);
        foreach ($questions as $key => $question) {
            $question = Question::query()->create([
                'content' => isset($question) ? $question : 'no question',
                'type' => 6,
                'lesson_id' => $lesson->id,
                'mark' => $marks[$key],
            ]);
            if ($request->hasFile("attachment.$key")) {
                $question->addMediaFromRequest("attachment.$key")
                    ->toMediaCollection('imageQuestion');
            }
        }



        return true;
    }


}
