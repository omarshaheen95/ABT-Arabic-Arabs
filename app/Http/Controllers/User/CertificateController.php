<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\MotivationalCertificate;
use App\Models\Question;
use App\Models\Story;
use App\Models\StoryQuestion;
use App\Models\StudentStoryTest;
use App\Models\UserTest;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index($type)
    {
        switch ($type) {
            case 'lessons':
                return $this->lessonsCertificates()->with('type',$type);
            case 'stories':
                return $this->storiesCertificates()->with('type',$type);
            case 'motivation':
                return $this->motivationCertificates()->with('type',$type);
            default:
                return redirect()->route('home');
        }
    }

    public function certificate($type,$id)
    {
        switch ($type) {
            case 'lessons':
                return $this->lessonCertificate($id);
            case 'stories':
                return $this->storyCertificate($id);
            case 'motivation':
                return $this->motivationCertificate($id);
            default:
                return redirect()->route('home');
        }
    }
    public function certificateAnswers($type,$id)
    {
        switch ($type) {
            case 'lessons':
                return $this->lessonCertificateAnswers($id);
            case 'stories':
                return $this->storyCertificateAnswers($id);
            default:
                return redirect()->route('home');
        }
    }


    // Lessons Certificates And Answers
    private function lessonsCertificates()
    {
        $title = 'Tests results';
        $student_tests = UserTest::query()
            ->where('user_id', $this->user->id)
            ->latest()->paginate(10);
        return view('user.certificates.index', compact('student_tests', 'title'));
    }

    private function lessonCertificate($id)
    {
        $title = 'Student test result';
        $student_test = UserTest::query()->where('user_id', $this->user->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');
        if ($student_test->status != 'Pass')
            return redirect()->route('home')->with('message', 'test dose not has certificates')->with('m-class', 'error');

        return view('user.certificates.lesson_certificate_template', compact('student_test', 'title'));
    }

    private function lessonCertificateAnswers($id)
    {
        $student_test = UserTest::query()->where('user_id', $this->user->id)->find($id);
        if (!$student_test)
            return response()->json(['success' => false, 'message' => t('Test not found')], 404);

        $questions = Question::query()->where('lesson_id', $student_test->lesson_id)
            ->with([
                'trueFalse',
                'options',
                'matches',
                'sortWords',
                'true_false_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                },
                'option_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                },
                'option_results.option',
                'match_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                },
                'match_results.match',
                'match_results.result',
                'sort_results' => function ($query) use ($id) {
                    $query->where('user_test_id', $id);
                },
                'sort_results.sort_word',
            ])->get();

        $answers = $questions->map(function ($question) {
            $userAnswer = '';
            $correctAnswer = '';

            switch ($question->type) {
                case 1: //true false
                    $result = $question->true_false_results->first();
                    if ($result) {
                        $userAnswer = $result->result == 1 ? t('True') : t('False');
                    } else {
                        $userAnswer = t('No answer');
                    }

                    if ($question->trueFalse) {
                        $correctAnswer = $question->trueFalse->result == 1 ? t('True') : t('False');
                    }
                    break;

                case 2: //options (multiple choice)
                    $result = $question->option_results->first();
                    if ($result && $result->option) {
                        $userAnswer = $result->option->content;
                    } else {
                        $userAnswer = t('No answer');
                    }

                    $correctOption = $question->options->where('result', 1)->first();
                    if ($correctOption) {
                        $correctAnswer = $correctOption->content;
                    }
                    break;

                case 3: //matching
                    $matchResults = $question->match_results;
                    $userAnswerParts = [];
                    $correctAnswerParts = [];

                    foreach ($question->matches as $match) {
                        $correctAnswerParts[] = $match->content . ' → ' . $match->result;
                    }
                    $correctAnswer = implode('<br>', $correctAnswerParts);

                    foreach ($matchResults as $matchResult) {
                        if ($matchResult->match && $matchResult->result) {
                            $userAnswerParts[] = $matchResult->match->content . ' → ' . $matchResult->result->result;
                        }
                    }
                    $userAnswer = count($userAnswerParts) > 0 ? implode('<br>', $userAnswerParts) : t('No answer');
                    break;

                case 4: //sorting (word arrangement)
                    $sortResults = $question->sort_results->sortBy('id');
                    $userAnswerParts = [];

                    foreach ($sortResults as $sortResult) {
                        if ($sortResult->sort_word) {
                            $userAnswerParts[] = $sortResult->sort_word->content;
                        }
                    }
                    $userAnswer = count($userAnswerParts) > 0 ? implode(' - ', $userAnswerParts) : t('No answer');

                    $correctSortWords = $question->sortWords->sortBy('ordered');
                    $correctAnswerParts = [];
                    foreach ($correctSortWords as $sortWord) {
                        $correctAnswerParts[] = $sortWord->content;
                    }
                    $correctAnswer = implode(' - ', $correctAnswerParts);
                    break;
            }

            return [
                'question' => $question->content,
                'questionType' => $question->type_name,
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctAnswer,
            ];
        });

        $response = [
            'success' => true,
            'data' => [
                'test_info' => [
                    'name' => $student_test->lesson->name,
                    'level' => $student_test->lesson->level,
                    'grade' => $student_test->lesson->grade->name,
                    'score' => $student_test->total_per,
                    'total' => $student_test->total,
                    'status' => $student_test->status,
                    'status_name' => $student_test->status_name,
                    'date' => $student_test->created_at->format('Y-m-d H:i:s')
                ],
                'answers' => $answers
            ]
        ];

        return response()->json($response);
    }


    //Stories Certificate And Answers
    private function storiesCertificates()
    {
        $title = 'نتائج الاختبارات  - Tests results';
        $student_tests = StudentStoryTest::query()
            ->where('user_id', $this->user->id)
            ->where('approved', 1)
            ->has('story')
            ->latest()->paginate(10);

        return view('user.certificates.index', compact('student_tests', 'title'));
    }

    private function storyCertificate($id)
    {
        $title = 'Student story test result';
        $student_test = StudentStoryTest::query()->where('user_id', $this->user->id)->find($id);
        if (!$student_test)
            return redirect()->route('home')->with('message', 'test not found')->with('m-class', 'error');
        return view('user.certificates.story_certificate_template',compact('student_test', 'title'));
    }

    private function storyCertificateAnswers($id)
    {
        $student_test = StudentStoryTest::query()->where('user_id', $this->user->id)->find($id);
        if (!$student_test)
            return response()->json(['success' => false, 'message' => t('Test not found')], 404);

        $questions = StoryQuestion::query()->where('story_id', $student_test->story_id)
            ->with([
                'trueFalse',
                'options',
                'matches',
                'sort_words',
                'true_false_results' => function ($query) use ($id) {
                    $query->where('student_story_test_id', $id);
                },
                'option_results' => function ($query) use ($id) {
                    $query->where('student_story_test_id', $id);
                },
                'option_results.option',
                'match_results' => function ($query) use ($id) {
                    $query->where('student_story_test_id', $id);
                },
                'match_results.match',
                'match_results.result',
                'sort_results' => function ($query) use ($id) {
                    $query->where('student_story_test_id', $id);
                },
                'sort_results.sort_word',
            ])->get();

        $answers = $questions->map(function ($question) {
            $userAnswer = '';
            $correctAnswer = '';

            switch ($question->type) {
                case 1: //true false
                    $result = $question->true_false_results->first();
                    if ($result) {
                        $userAnswer = $result->result == 1 ? t('True') : t('False');
                    } else {
                        $userAnswer = t('No answer');
                    }

                    if ($question->trueFalse) {
                        $correctAnswer = $question->trueFalse->result == 1 ? t('True') : t('False');
                    }
                    break;

                case 2: //options (multiple choice)
                    $result = $question->option_results->first();
                    if ($result && $result->option) {
                        $userAnswer = $result->option->content;
                    } else {
                        $userAnswer = t('No answer');
                    }

                    $correctOption = $question->options->where('result', 1)->first();
                    if ($correctOption) {
                        $correctAnswer = $correctOption->content;
                    }
                    break;

                case 3: //match
                    if ($question->match_results->count() > 0) {
                        $matchAnswers = [];
                        foreach ($question->match_results as $matchResult) {
                            if ($matchResult->match && $matchResult->result) {
                                $matchAnswers[] = $matchResult->match->content . ' - ' . $matchResult->result->result;
                            }
                        }
                        $userAnswer = implode(', ', $matchAnswers);
                    } else {
                        $userAnswer = t('No answer');
                    }

                    if ($question->matches->count() > 0) {
                        $correctMatches = [];
                        foreach ($question->matches as $match) {
                            $correctMatches[] = $match->content . ' - ' . $match->result;
                        }
                        $correctAnswer = implode(', ', $correctMatches);
                    }
                    break;

                case 4: //sort words
                    if ($question->sort_results->count() > 0) {
                        $sortedWords = [];
                        foreach ($question->sort_results as $sortResult) {
                            if ($sortResult->sort_word) {
                                $sortedWords[] = $sortResult->sort_word->content;
                            }
                        }
                        $userAnswer = implode(' - ', $sortedWords);
                    } else {
                        $userAnswer = t('No answer');
                    }

                    if ($question->sort_words->count() > 0) {
                        $correctWords = [];
                        foreach ($question->sort_words as $sortWord) {
                            $correctWords[] = $sortWord->content;
                        }
                        $correctAnswer = implode(' - ', $correctWords);
                    }
                    break;
            }

            return [
                'question' => $question->content,
                'questionType' => $question->type_name,
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctAnswer,
            ];
        });

        $response = [
            'success' => true,
            'data' => [
                'test_info' => [
                    'name' => $student_test->story->name,
                    'grade' => $student_test->story->grade_name,
                    'score' => $student_test->total_per,
                    'total' => $student_test->total,
                    'status' => $student_test->status,
                    'status_name' => $student_test->status_name,
                    'date' => $student_test->created_at->format('Y-m-d H:i:s')
                ],
                'answers' => $answers
            ]
        ];

        return response()->json($response);
    }


    //Motivation Certificates
    private function motivationCertificates()
    {
        $title = 'الشهادات التحفيزية  - Motivational Certificates';
        $student_tests = MotivationalCertificate::query()
            ->has('user')
            ->has('teacher')
            ->hasMorph('model', [Lesson::class, Story::class])
            ->where('user_id', $this->user->id)
            ->latest()->paginate(10);

        return view('user.certificates.index', compact('student_tests', 'title'));
    }

    private function motivationCertificate($id)
    {
        $title = 'Student story test result';
        $certificate = MotivationalCertificate::query()->where('user_id', $this->user->id)->find($id);
        if (!$certificate)
            return redirect()->route('home')->with('message', 'certificate not found')->with('m-class', 'error');

        return view('general.motivational_certificates.certificate',compact('certificate', 'title'));
    }
}
