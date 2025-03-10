<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserStoryAssignment;
use App\Models\StoryMatch;
use App\Models\StoryMatchResult;
use App\Models\StoryOption;
use App\Models\StoryOptionResult;
use App\Models\StoryQuestion;
use App\Models\StorySortResult;
use App\Models\StorySortWord;
use App\Models\StoryTrueFalse;
use App\Models\StoryTrueFalseResult;
use App\Models\StudentStoryTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoryController extends Controller
{
    public function storyTest(Request $request, $id)
    {
        $student = Auth::user();
        $test = null;
        //dd($request->toArray());
        if ($student->demo) {
            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
        }


      DB::transaction(function () use ($request,$id,$student,&$test){
          $questions = StoryQuestion::with(['trueFalse', 'matches', 'sort_words', 'options'])->where('story_id', $id)->get();

          $test = StudentStoryTest::create([
              'user_id' => $student->id,
              'story_id' => $id,
              'corrected' => 1,
              'total' => 0,
          ]);

          $total = 0;

          // True/False Results and Total
          $tf_total = 0;
          $tf_data = [];
          foreach ($request->get('tf', []) as $key => $result) {
              $main_result = StoryTrueFalse::where('story_question_id', $key)->first();
              $mark = optional($main_result)->result == $result ? $questions->where('id',$key)->first()->mark : 0;
              $tf_total += $mark;

              $tf_data[] = [
                  'story_question_id' => $key,
                  'result' => $result,
                  'student_story_test_id' => $test->id,
                  'created_at' => now(),
                  'updated_at' => now(),
              ];
          }
          StoryTrueFalseResult::insert($tf_data);
          $total += $tf_total;

          // Option Results and Total
          $o_total = 0;
          $o_data = [];
          foreach ($request->get('option', []) as $key => $option) {
              $main_result = StoryOption::find($option);
              $mark = optional($main_result)->result == 1 ? $questions->where('id',$key)->first()->mark : 0;
              $o_total += $mark;

              $o_data[] = [
                  'story_question_id' => $key,
                  'story_option_id' => $option,
                  'student_story_test_id' => $test->id,
                  'created_at' => now(),
                  'updated_at' => now(),
              ];
          }
          StoryOptionResult::insert($o_data);
          $total += $o_total;

          // Matching Results and Total
          $m_total = 0;
          $m_data = [];
          foreach ($request->get('matching', []) as $key => $match) {
              $matchMark = $questions->where('id',$key)->first()->mark / $questions->where('id',$key)->first()->matches->count();

              foreach ($match as $uid => $match_id) {
                  if (!is_null($match_id)) {
                      $result_id = $questions->where('id',$key)->first()->matches->where('uid', $uid)->first()->id;
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
              'start_at' => $request->get('start_at', now()),
              'end_at' => now(),
              'status' => $total >= $mark ? 'Pass' : 'Fail',
          ]);

          $student->user_tracker_story()->create([
              'story_id' => $id,
              'type' => 'test',
              'color' => 'danger',
              'start_at' => $request->get('start_at', now()),
              'end_at' => now(),
          ]);

          // Update Approved Tests
          $student_tests = StudentStoryTest::where('total', '>=', $mark)
              ->where('user_id', $student->id)
              ->where('story_id', $id)
              ->orderByDesc('total')
              ->get();

          if (optional($student_tests->first())->total >= $mark) {
              $approved_test = $student_tests->first();

              StudentStoryTest::where('user_id', $student->id)
                  ->where('story_id', $id)
                  ->where('id', '<>', $approved_test->id)
                  ->update(['approved' => 0]);

              $approved_test->update(['approved' => 1]);
          }

          // Update User Assignment
          $user_assignment = UserStoryAssignment::where('user_id', $student->id)
              ->where('story_id', $id)
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
        return redirect()->route('story_test_result', $test->id)
            ->with('message', "تم تقديم الاختبار بنجاح")
            ->with('m-class', 'success');
    }

//    public function storyTest(Request $request, $id)
//    {
//
//        $student = Auth::user();
//        if ($student->demo){
//            return redirect()->route('home')->with('message', "(Demo)تمت العملية بنجاح")->with('m-class', 'success');
//        }
//        $questions = StoryQuestion::with(['trueFalse','matches','sort_words','options'])->where('story_id', $id)->get();
//
//        $test = StudentStoryTest::query()->create([
//            'user_id' => $student->id,
//            'story_id' => $id,
//            'corrected' => 1,
//            'total' => 0,
//        ]);
//
//        foreach ($request->get('tf', []) as $key => $result)
//        {
//            StoryTrueFalseResult::create([
//                'story_question_id' => $key,
//                'result' => $result,
//                'student_story_test_id' => $test->id
//            ]);
//        }
//        foreach ($request->get('option', []) as $key => $option)
//        {
//            StoryOptionResult::create([
//                'story_question_id' => $key,
//                'story_option_id' => $option,
//                'student_story_test_id' => $test->id
//            ]);
//        }
//
//
//        $matching = $request->get('matching', []);
//        $sorting = $request->get('sorting', []);
//
//        foreach ($matching as $key => $match)
//        {
//            $match_answers = StoryMatch::query()->where('story_question_id', $key)->get();
//            foreach ($match as $uid => $value)
//            {
//                if (!is_null($value))
//                {
//                    $result_id = $match_answers->where('uid', $uid)->first()->id;
//                    StoryMatchResult::create([
//                        'story_question_id' => $key,
//                        'story_match_id' => $value,
//                        'story_result_id' => $result_id,
//                        'student_story_test_id' => $test->id,
//                        'match_answer_uid' => $uid,
//                    ]);
//                }
//            }
//        }
//        foreach($sorting as $key => $sort)
//        {
//            $sort_words = StorySortWord::query()->where('story_question_id', $key)->get();
//            foreach ($sort as $uid => $value)
//            {
//                if (!is_null($value))
//                {
//                    $result_id = $sort_words->where('uid', $uid)->first()->id;
//                    StorySortResult::create([
//                        'story_question_id' => $key,
//                        'story_sort_word_id' => $result_id,
//                        'student_story_test_id' => $test->id,
//                        'story_sort_answer_uid' => $uid,
//                    ]);
//                }
//            }
//        }
//
//
////        foreach ($request->get('re', []) as $question => $options)
////        {
////            $matches = StoryMatch::query()->where('story_question_id', $question)->get()->pluck('id')->all();
////            foreach ($options as $key => $value)
////            {
////                if (!is_null($value))
////                {
////                    StoryMatchResult::create([
////                        'story_question_id' => $question,
////                        'story_match_id' => $matches[$value - 1],
////                        'story_result_id' => $key,
////                        'student_story_test_id' => $test->id
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
////                    StorySortResult::create([
////                        'story_question_id' => $question,
////                        'story_sort_word_id' => $key,
////                        'student_story_test_id' => $test->id,
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
//                $student_result = StoryTrueFalseResult::query()
//                    ->where('story_question_id', $question->id)
//                    ->where('student_story_test_id', $test->id)->first();
//                $main_result = StoryTrueFalse::query()->where('story_question_id', $question->id)->first();
//                if(isset($student_result) && isset($main_result) && optional($student_result)->result == optional($main_result)->result){
//                    $total += $question->mark;
//                    $tf_total += $question->mark;
//                }
//            }
//
//            if ($question->type == 2)
//            {
//                $student_result = StoryOptionResult::query()
//                    ->where('story_question_id', $question->id)
//                    ->where('student_story_test_id', $test->id)->first();
//                if($student_result)
//                {
//                    $main_result = StoryOption::query()->find($student_result->story_option_id);
//                }
//
//                if(isset($student_result) && isset($main_result) && optional($main_result)->result == 1){
//                    $total += $question->mark;
//                    $o_total += $question->mark;
//                }
//
//            }
//
//            $match_mark = 0;
//            if ($question->type == 3)
//            {
//                $matchMark = $question->mark / $question->matches()->count();
//                $match_results = StoryMatchResult::query()
//                    ->where('story_question_id', $question->id)
//                    ->where('student_story_test_id', $test->id)->get();
//                foreach ($match_results as $match_result)
//                {
//                    $match_mark += $match_result->story_match_id == $match_result->story_result_id ? $matchMark:0;
//                }
//                $total += $match_mark;
//                $m_total += $match_mark;
//            }
//
//            if ($question->type == 4)
//            {
//                $sort_words = StorySortWord::query()->where('story_question_id', $question->id)->get()->pluck('id')->all();
//                $student_sort_words = StorySortResult::query()
//                    ->where('story_question_id', $question->id)
//                    ->where('student_story_test_id', $test->id)->get();
//                if (count($student_sort_words))
//                {
//                    $student_sort_words = $student_sort_words->pluck('story_sort_word_id')->all();
//                    if ($student_sort_words === $sort_words)
//                    {
//                        $total += $question->mark;
//                        $s_total += $question->mark;
//                    }
//
//                }
//            }
//        }
//
//
//        $mark = 25;
//
//
//        $test->update([
//            'total' => $total,
//            'start_at' => $request->get('start_at', now()),
//            'end_at' => now(),
//            'status' => $total >= $mark ? 'Pass':'Fail',
//        ]);
//
//
//
//        $student_tests = StudentStoryTest::query()->where('total', '>=', $mark)
//            ->where('user_id',  $student->id)
//            ->where('total', '<=', $total)
//            ->where('story_id', $id)->orderByDesc('total')->get();
//
//
//
//        if (optional($student_tests->first())->total >= $mark)
//        {
//            StudentStoryTest::query()->where('user_id', $student->id)
//                ->where('story_id', $id)
//                ->where('id', '<>', $student_tests->first()->id)->update([
//                    'approved' => 0,
//                ]);
//            StudentStoryTest::query()->where('user_id', $student->id)
//                ->where('story_id', $id)
//                ->where('id',  $student_tests->first()->id)->update([
//                    'approved' => 1,
//                ]);
//        }
//
//        $user_assignment = UserStoryAssignment::query()->where('user_id', $student->id)
//            ->where('story_id', $id)
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
//        }
//
//
//        return redirect()->route('story_test_result', $test->id)->with('message', "تم تقديم الاختبار بنجاح")->with('m-class', 'success');
//    }

    public function storyTestResult($id)
    {
        $title = "نتيجة اختبار القصة";
        $student = Auth::user();
        $student_test = StudentStoryTest::query()->where('user_id', $student->id)->findOrFail($id);
        $level = optional($student_test->story)->level;
        $story = $student_test->story;

        return view('user.story.story_test_result',compact('student_test', 'title', 'level', 'story'));
    }

    public function certificates()
    {
        $title = 'نتائج الاختبارات';
        $student_tests = StudentStoryTest::query()
            ->where('user_id', Auth::user()->id)
            ->where('approved', 1)
            ->latest()->paginate(10);

        return view('user.story.certificates', compact('student_tests', 'title'));
    }

    public function certificate($id)
    {
        $title = 'نتيجة اختبار القصة';
        $student = Auth::user();
        $student_test = StudentStoryTest::query()->has('story')->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');
        return view('user.story.new_certificate',compact('student_test', 'title'));
    }

    public function certificateAnswers($id)
    {
        $title = 'إجابات اختبار الطالب';
        $student = Auth::user();
        $student_test = StudentStoryTest::query()->where('user_id', $student->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');

        $questions = StoryQuestion::query()->where('story_id', $student_test->story_id)->get();

        return view('user.story.certificate_result',compact('student_test', 'title', 'questions'));
    }
}
