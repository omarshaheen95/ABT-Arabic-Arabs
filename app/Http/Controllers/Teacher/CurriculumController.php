<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Question;
use App\Models\Story;
use App\Models\StoryQuestion;
use App\Models\StoryUserRecord;
use App\Models\TQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculumController extends Controller
{
    public function curriculum($grade)
    {
        $title = "المنهاج";
        return view('teacher.user_curriculum.home', compact('grade', 'title'));
    }

    public function lessonsLevels($grade)
    {
        $title = 'المهارات والدروس';
        $grade_steps = Grade::query()->where('id', $grade)->first();
        return view('teacher.user_curriculum.levels', compact('title', 'grade', 'grade_steps'));
    }
    public function storiesLevels($grade)
    {
        $title = "مستويات القصص";
        $levels = [
            1, 2, 3, 4, 5, 6, 7, 8, 9
        ];

        return view('teacher.user_curriculum.stories_levels', compact('title', 'levels', 'grade'));
    }

    public function stories($grade)
    {
        $title = "قائمة القصص";
        $stories = Story::query()->where('grade', $grade)->where('active', 1)->get();
        return view('teacher.user_curriculum.stories_list', compact('title', 'stories', 'grade'));
    }

    public function story( $id, $key, $grade)
    {
        $story = Story::query()->findOrFail($id);
        switch ($key) {
            case 'watch':
                return view('teacher.user_curriculum.story.learn', compact('story', 'grade'));
            case 'read':
                $users_story = StoryUserRecord::query()
                    ->where('story_id', $story->id)->latest()
                    ->where('status', 'corrected')
                    ->where('approved', 1)
                    ->has('user')
                    ->limit(10)
                    ->get();
                return view('teacher.user_curriculum.story.training', compact('story', 'users_story' ,'grade'));
            case 'test':
                $questions = StoryQuestion::query()->where('story_id', $id)->get();
                return view('teacher.user_curriculum.story.test', compact('questions', 'story', 'grade'));
            default:
                return redirect()->route('home');
        }
    }

    public function lessons($grade, $type)
    {
        $lessons = Lesson::query()->where('lesson_type', $type)->where('grade_id', $grade)->get();
        return view('teacher.user_curriculum.lessons', compact('grade', 'lessons'));
    }

    public function subLevels($grade, $type)
    {
        if ($type == 'grammar')
        {
            $type_name = 'القواعد النحوية';
        }elseif ($type == 'dictation')
        {
            $type_name = 'الإملاء';
        }else{
            $type_name = 'البلاغة';
        }
        $title = 'مستويات الدروس -  ' . $type_name;
        $level_grade = Grade::query()->where('id',$grade)->first();
        $levels = Lesson::query()
            ->whereNotNull('level')
            ->where('lesson_type', $type)
            ->where('grade_id',$level_grade->id)
            ->select('level', 'grade_id', \DB::raw('COUNT(*) as c'))
            ->groupBy('level', 'grade_id')
            ->havingRaw('c > 0')
            ->get()->values()->toArray();


        return view('teacher.user_curriculum.sub_levels', compact('title', 'type', 'grade', 'levels'));
    }

    public function lesson($id, $key)
    {
        $lesson = Lesson::query()->with(['grade', 'media'])->findOrFail($id);
        $grade = $lesson->grade_id;
        switch ($key) {
            case 'learn':
                return view('teacher.user_curriculum.lesson.learn', compact('lesson', 'grade'));
            case 'training':
                $tf_questions = TQuestion::query()->where('type', 1)->where('lesson_id', $lesson->id)->get();
                $c_questions = TQuestion::query()->where('type', 2)->where('lesson_id', $lesson->id)->get();
                $m_questions = TQuestion::query()->where('type', 3)->where('lesson_id', $lesson->id)->get();
                $s_questions = TQuestion::query()->where('type', 4)->where('lesson_id', $lesson->id)->get();
                return view('teacher.user_curriculum.lesson.training', compact('lesson', 'tf_questions', 'c_questions', 'm_questions', 's_questions', 'grade'));
            case 'test':
                $questions = Question::query()->where('lesson_id', $id)->get();
                if ($lesson->lesson_type == 'writing')
                {
                    return view('teacher.user_curriculum.lesson.writing_test', compact('questions', 'lesson', 'grade'));

                }
                if ($lesson->lesson_type == 'speaking')
                {
                    return view('teacher.user_curriculum.lesson.speaking_test', compact('questions', 'lesson', 'grade'));

                }
                return view('teacher.user_curriculum.lesson.test', compact('questions', 'lesson', 'grade'));
            default:
                return redirect()->route('home');
        }
    }

    public function subLessons($id, $type, $level = null)
    {
        $user = Auth::guard('web')->user();
        $grade = Grade::query()->findOrFail($id);
        $lessons = Lesson::query()->where('lesson_type', $type)->where('grade_id', $grade->id)->when($level, function (Builder $query) use ($level) {
            $query->where('level', $level);
        })->get();
        $grade = $id;
        return view('teacher.user_curriculum.lessons', compact('grade', 'lessons'));
    }




}
