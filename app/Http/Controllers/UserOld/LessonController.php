<?php

namespace App\Http\Controllers\UserOld;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use App\Models\Option;
use App\Models\OptionResult;
use App\Models\Question;
use App\Models\SortResult;
use App\Models\SpeakingResult;
use App\Models\TrueFalse;
use App\Models\TrueFalseResult;
use App\Models\UserAssignment;
use App\Models\UserTest;
use App\Models\WritingResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LessonController extends Controller
{
    public function lessonTest(Request $request, $id)
    {
        $student = Auth::user();
        $test = null;

        if ($student->demo) {
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        DB::transaction(function () use ($request,$id,$student,&$test) {
            $questions = Question::with(['trueFalse', 'matches', 'sortWords', 'options'])->where('lesson_id', $id)->get();

            $test = UserTest::create([
                'user_id' => $student->id,
                'lesson_id' => $id,
                'corrected' => 1,
                'total' => 0,
            ]);

            $total = 0;

            // True/False Results and Total
            $tf_total = 0;
            $tf_data = [];
            foreach ($request->get('tf', []) as $key => $result) {
                $main_result = $questions->where('id', $key)->first()->trueFalse;
                $mark = optional($main_result)->result == $result ? $questions->where('id', $key)->first()->mark : 0;
                $tf_total += $mark;
                $tf_data[] = [
                    'question_id' => $key,
                    'result' => $result,
                    'user_test_id' => $test->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TrueFalseResult::insert($tf_data);
            $total += $tf_total;

            // Option Results and Total
            $o_total = 0;
            $o_data = [];
            foreach ($request->get('option', []) as $key => $option) {
                $main_result = $questions->where('id', $key)->first()->options->where('id',$option)->first();
                $mark = optional($main_result)->result == 1 ? $questions->where('id', $key)->first()->mark : 0;
                $o_total += $mark;

                $o_data[] = [
                    'question_id' => $key,
                    'option_id' => $option,
                    'user_test_id' => $test->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            OptionResult::insert($o_data);
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
                'approved' => 1,
                'total' => $total,
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
                'status' => $total >= $mark ? 'Pass' : 'Fail',
            ]);


            $student_tests = UserTest::query()
                ->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->orderByDesc('total')->get();


            if (optional($student_tests->first())->total >= $mark) {
                UserTest::query()->where('user_id', $student->id)
                    ->where('lesson_id', $id)
                    ->where('id', '<>', $student_tests->first()->id)->update([
                        'approved' => 0,
                    ]);
                UserTest::query()->where('user_id', $student->id)
                    ->where('lesson_id', $id)
                    ->where('id', $student_tests->first()->id)->update([
                        'approved' => 1,
                    ]);
            }


            $student->user_tracker()->create([
                'lesson_id' => $id,
                'type' => 'test',
                'color' => 'danger',
                'start_at' => $request->get('start_at', now()),
                'end_at' => now(),
            ]);

            if ($test->user->teacherUser) {
                updateTeacherStatistics($test->user->teacherUser->teacher_id);
            }

            $user_assignment = UserAssignment::query()->where('user_id', $student->id)
                ->where('lesson_id', $id)
                ->where('test_assignment', 1)
                ->where('done_test_assignment', 0)
                ->first();

            if ($user_assignment) {
                $user_assignment->update([
                    'done_test_assignment' => 1,
                    'completed' => 1,
                ]);
            }


        });
        return redirect()->route('lesson_test_result', $test->id)->with('message', "تم حفظ الاختبار بنجاح")->with('m-class', 'success');
    }

//    public function lessonTest(Request $request, $id)
//    {
//
//        $student = Auth::user();
//
//        if ($student->demo){
//            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
//        }
////        $student_term = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $id)->first();
////        if ($student_term)
////        {
////            return redirect()->route('home')->with('message', 'You have obtained a test certificate for this lesson')->with('m-class', 'success');
////        }
//
//
//        $questions = Question::query()->with(['lesson','trueFalse','matches','sortWords','options'])->where('lesson_id', $id)->get();
//
//        $test = UserTest::query()->create([
//            'user_id' => $student->id,
//            'lesson_id' => $id,
//            'corrected' => 1,
//            'total' => 0,
//        ]);
//
////        if ($student_term)
////        {
////            foreach ($questions as $question) {
////                $student_tf_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
////                $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
////                $match_results = MatchResult::query()->where('user_id', $student->id)->where('question_id', $question->id)->delete();
////                $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_id', $student->id)->delete();
////            }
////            $student_term->delete();
////        }
//        foreach ($request->get('tf', []) as $key => $result)
//        {
//            TrueFalseResult::create([
//                'user_test_id' => $test->id,
//                'question_id' => $key,
//                'result' => $result,
//                'student_test_id' => $test->id
//            ]);
//        }
//        foreach ($request->get('option', []) as $key => $option)
//        {
//            OptionResult::create([
//                'user_test_id' => $test->id,
//                'question_id' => $key,
//                'option_id' => $option,
//                'student_test_id' => $test->id
//            ]);
//        }
//
//        $matching = $request->get('matching', []);
//        $sorting = $request->get('sorting', []);
//
//        foreach ($matching as $key => $match)
//        {
//            $match_answers = QMatch::query()->where('question_id', $key)->get();
//            foreach ($match as $uid => $value)
//            {
//                if (!is_null($value))
//                {
//                    $result_id = $match_answers->where('uid', $uid)->first()->id;
//                    MatchResult::create([
//                        'question_id' => $key,
//                        'match_id' => $value,
//                        'result_id' => $result_id,
//                        'user_test_id' => $test->id,
//                        'match_answer_uid' => $uid,
//                    ]);
//                }
//            }
//        }
//
//        foreach($sorting as $key => $sort)
//        {
//            $sort_words = SortWord::query()->where('question_id', $key)->get();
//            foreach ($sort as $uid => $value)
//            {
//                if (!is_null($value))
//                {
//                    $result_id = $sort_words->where('uid', $uid)->first()->id;
//                    SortResult::create([
//                        'user_id' => $student->id,
//                        'question_id' => $key,
//                        'sort_word_id' => $result_id,
//                        'student_test_id' => $test->id,
//                        'sort_answer_uid' => $uid,
//                    ]);
//                }
//            }
//        }
//
////        foreach ($request->get('re', []) as $question => $options)
////        {
////            $matches = QMatch::query()->where('question_id', $question)->get()->pluck('id')->all();
////            foreach ($options as $key => $value)
////            {
////                if (!is_null($value))
////                {
////                    MatchResult::create([
////                        'user_test_id' => $test->id,
////                        'question_id' => $question,
////                        'match_id' => $matches[$value - 1],
////                        'result_id' => $key,
////                        'student_test_id' => $test->id
////                    ]);
////                }
////            }
////        }
////        foreach ($request->get('sort', []) as $question => $words)
////        {
////            foreach ($words as $key => $value)
////            {
////                if (!is_null($value))
////                {
////                    SortResult::create([
////                        'user_test_id' => $test->id,
////                        'question_id' => $question,
////                        'sort_word_id' => $key,
////                        'student_test_id' => $test->id,
////                    ]);
////                }
////            }
////        }
//
//
//
//        $total = 0;
//        $tf_total = 0;
//        $o_total = 0;
//        $m_total = 0;
//        $s_total = 0;
//
//        foreach ($questions as $question)
//        {
//            if ($question->type == 1)
//            {
//                $student_result = TrueFalseResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
//                    ->first();
//                $main_result = TrueFalse::query()->where('question_id', $question->id)->first();
//                if(isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result){
//                    $total += $question->mark;
//                    $tf_total += $question->mark;
////                    Log::warning('TF-QM : '.$question->mark);
//                }
//            }
//
//            if ($question->type == 2)
//            {
//                $student_result = OptionResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
//                    ->first();
//                if($student_result)
//                {
//                    $main_result = Option::query()->find($student_result->option_id);
//                }
//
//                if(isset($student_result) && isset($main_result) && optional($main_result)->result == 1){
//                    $total += $question->mark;
//                    $o_total += $question->mark;
////                    Log::warning('C-QM : '.$question->mark);
//                }
//
//            }
//
//            $match_mark = 0;
//            if ($question->type == 3)
//            {
//                $match_results = MatchResult::query()->where('user_test_id', $test->id)->where('question_id', $question->id)
//                    ->get();
//                $main_mark = $question->mark / $question->matches()->count();
//                foreach ($match_results as $match_result)
//                {
//                    $match_mark += $match_result->match_id == $match_result->result_id ? $main_mark:0;
//                }
//                $total += $match_mark;
//                $m_total += $match_mark;
////                Log::warning('M-QM : '.$question->mark);
//            }
//
//            if ($question->type == 4)
//            {
//                $sort_words = SortWord::query()->where('question_id', $question->id)->get()->pluck('id')->all();
//                $student_sort_words = SortResult::query()->where('question_id', $question->id)->where('user_test_id', $test->id)
//                   ->get();
//                if (count($student_sort_words))
//                {
//                    $student_sort_words = $student_sort_words->pluck('sort_word_id')->all();
//                    if ($student_sort_words === $sort_words)
//                    {
//                        $total += $question->mark;
//                        $s_total += $question->mark;
////                        Log::warning('S-QM : '.$question->mark);
//                    }
//
//                }
//            }
//        }
//
//        $mark = $test->lesson->success_mark;
//
//
//        $test->update([
//            'approved' => 1,
//            'total' => $total,
//            'start_at' => $request->get('start_at', now()),
//            'end_at' => now(),
//            'status' => $total >= $mark ? 'Pass':'Fail',
//        ]);
//
//
//
//
//        $student_tests = UserTest::query()
////            ->where('total', '>=', $mark)
//            ->where('user_id',  $student->id)
////            ->where('total', '<=', $total)
//            ->where('lesson_id', $id)
//            ->orderByDesc('total')->get();
//
//
//
//        if (optional($student_tests->first())->total >= $mark)
//        {
//            UserTest::query()->where('user_id', $student->id)
//                ->where('lesson_id', $id)
//                ->where('id', '<>', $student_tests->first()->id)->update([
//                    'approved' => 0,
//                ]);
//            UserTest::query()->where('user_id', $student->id)
//                ->where('lesson_id', $id)
//                ->where('id',  $student_tests->first()->id)->update([
//                    'approved' => 1,
//                ]);
//        }
//
//
//
//
//        $student->user_tracker()->create([
//            'lesson_id' => $id,
//            'type' => 'test',
//            'color' => 'danger',
//            'start_at' => $request->get('start_at', now()),
//            'end_at' => now(),
//        ]);
//
//        if ($test->user->teacherUser)
//        {
//            updateTeacherStatistics($test->user->teacherUser->teacher_id);
//        }
//
//        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
//            ->where('lesson_id', $id)
//            ->where('test_assignment', 1)
//            ->where('done_test_assignment', 0)
//            ->first();
//
//        if ($user_assignment)
//        {
//            $user_assignment->update([
//                'done_test_assignment' => 1,
//                'completed' => 1,
//            ]);
//
////            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
////                $user_assignment->update([
////                    'completed' => 1,
////                ]);
////            }
//        }
////        dd($total);
//
//        return redirect()->route('lesson_test_result', $test->id)->with('message', "تم حفظ الاختبار بنجاح")->with('m-class', 'success');
//    }

    public function lessonWritingTest(Request $request, $id)
    {
        $student = Auth::user();
        if ($student->demo){
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }
//        $student_term = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $id)->first();
//        if ($student_term)
//        {
//            return redirect()->route('lessons', [$student_term->lesson->grade->grade_number, $student_term->lesson->lesson_type])->with('message','تم تقديم الاختبار مسبقا')->with('m-class', 'success');
//        }




        $test = UserTest::query()->create([
            'user_id' => $student->id,
            'lesson_id' => $id,
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
            'corrected' => 0,
            'total' => 0,
        ]);

        foreach ($request->get('writing_answer', []) as $key => $value)
        {
            if ($request->hasFile("writing_attachment.$key"))
            {
                $attachment = uploadFile($request->file("writing_attachment.$key"), 'writing_results')['path'];
            }else{
                $attachment = null;
            }
            WritingResult::query()->create([
                'user_test_id' => $test->id,
                'question_id' => $key,
                'result' => $value,
                'attachment' => $attachment,
            ]);
        }



        $student->user_tracker()->create([
            'lesson_id' => $id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
        ]);

        if ($test->user->teacherUser)
        {
            updateTeacherStatistics($test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
            ->where('lesson_id', $id)
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
//        dd($total);

        return redirect()->route('lessons', [$test->lesson->grade->grade_number, $test->lesson->lesson_type])->with('message', "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها")->with('m-class', 'success');
    }
//    public function lessonSpeakingTest(Request $request, $id)
//    {
//        $student = Auth::user();
//        if ($student->demo){
//            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
//        }
////        $student_term = UserTest::query()->where('user_id', $student->id)->where('lesson_id', $id)->first();
////        if ($student_term)
////        {
////            return $this->sendError(  'تم تقديم الاختبار مسبقا', 422);
////        }
//
//
//
//
//        $test = UserTest::query()->create([
//            'user_id' => $student->id,
//            'lesson_id' => $id,
//            'start_at' => $request->get('start_at', now()),
//            'end_at' => now(),
//            'corrected' => 0,
//            'total' => 0,
//        ]);
//
//        if ($request->hasFile('record') && $request->get('question_id', false)) {
//            $path = public_path().'/uploads/record_results';
//            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
//
//            $new_name = uniqid() . '.' . 'wav';
//                    $destination = public_path('uploads/'.date('Y').'/'.date('m').'/'.date('d').'/record_results');
//            move_uploaded_file($_FILES['record']['tmp_name'], $destination . '/' . $new_name);
//                    $record = 'uploads' . DIRECTORY_SEPARATOR.date('Y') .DIRECTORY_SEPARATOR .date('m').DIRECTORY_SEPARATOR.date('d') .DIRECTORY_SEPARATOR . 'record_results' . DIRECTORY_SEPARATOR . $new_name;
//            SpeakingResult::query()->create([
//                'question_id' => $request->get('question_id'),
//                'user_test_id' => $test->id,
//                'attachment' => $record,
//            ]);
////            return $this->sendResponse($record, "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها");
//        }
//
////        foreach ($request->get('writing_answer', []) as $key => $value)
////        {
////            if ($request->hasFile("writing_attachment.$key"))
////            {
////                $attachment = $this->uploadFile($request->file("writing_attachment.$key"), 'writing_results');
////            }else{
////                $attachment = null;
////            }
////            WritingResult::query()->create([
////                'user_test_id' => $test->id,
////                'question_id' => $key,
////                'result' => $value,
////                'attachment' => $attachment,
////            ]);
////        }
//
//
//
//        $student->user_tracker()->create([
//            'lesson_id' => $id,
//            'type' => 'test',
//            'color' => 'danger',
//            'start_at' => $request->get('start_at', now()),
//            'end_at' => now(),
//        ]);
//
//        if ($test->user->teacherUser)
//        {
//            updateTeacherStatistics($test->user->teacherUser->teacher_id);
//        }
//
//        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
//            ->where('lesson_id', $id)
//            ->where('test_assignment', 1)
//            ->where('done_test_assignment', 0)
//            ->first();
////        dd($user_assignment, $id, $student);
//
//        if ($user_assignment)
//        {
//            $user_assignment->update([
//                'done_test_assignment' => 1,
//            ]);
//
//            if (($user_assignment->tasks_assignment && $user_assignment->done_tasks_assignment) || !$user_assignment->tasks_assignment){
//                $user_assignment->update([
//                    'completed' => 1,
//                ]);
//            }
//        }
////        dd($total);
//        return $this->sendResponse($user_assignment, "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها");
//
////        return redirect()->route('lessons', [$test->lesson->grade->grade_number, $test->lesson->lesson_type])->with('message', "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها")->with('m-class', 'success');
//    }
    public function lessonSpeakingTest(Request $request, $id)
    {
        $student = Auth::user();
        if ($student->demo) {
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }

        $test = UserTest::query()->create([
            'user_id' => $student->id,
            'lesson_id' => $id,
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
            'corrected' => 0,
            'total' => 0,
        ]);

        if ($request->hasFile('record') && $request->get('question_id', false)) {
            // Create date-based directory structure
            $yearDir = date('Y');
            $monthDir = date('m');
            $dayDir = date('d');

            $relativePath = 'uploads/' . $yearDir . '/' . $monthDir . '/' . $dayDir . '/record_results';
            $fullPath = public_path($relativePath);

            // Create directory structure if it doesn't exist
            if (!File::isDirectory($fullPath)) {
                File::makeDirectory($fullPath, 0777, true, true);
            }

            $new_name = uniqid() . '.wav';
            $uploadedFile = $request->file('record');

            // Use Laravel's move method instead of move_uploaded_file
            if ($uploadedFile->move($fullPath, $new_name)) {
                $record = $relativePath . '/' . $new_name;

                SpeakingResult::query()->create([
                    'question_id' => $request->get('question_id'),
                    'user_test_id' => $test->id,
                    'attachment' => $record,
                ]);
            }
        }

        $student->user_tracker()->create([
            'lesson_id' => $id,
            'type' => 'test',
            'color' => 'danger',
            'start_at' => $request->get('start_at', now()),
            'end_at' => now(),
        ]);

        if ($test->user->teacherUser) {
            updateTeacherStatistics($test->user->teacherUser->teacher_id);
        }

        $user_assignment = UserAssignment::query()->where('user_id', $student->id)
            ->where('lesson_id', $id)
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

        return $this->sendResponse($user_assignment, "تم حفظ الإجابات بنجاح لدى المدرس ليتم تصحيحها");
    }
    public function lessonTestResult($id)
    {
        $title = "نتيجة الاختبار";
        $student = Auth::user();
        $student_test = UserTest::query()->where('user_id', $student->id)->findOrFail($id);
        $level = optional($student_test->lesson)->level;
        $lesson = $student_test->lesson;

        return view('user_old.lesson.lesson_test_result',compact('student_test', 'title', 'level', 'lesson'));
    }


}
