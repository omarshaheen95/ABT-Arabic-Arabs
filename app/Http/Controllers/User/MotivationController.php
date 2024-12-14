<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\MotivationalCertificate;
use App\Models\Story;
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
use App\Models\TeacherUserCertificate;
use App\Models\UserStoryAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotivationController extends Controller
{
    public function certificates()
    {
        $title = 'الشهادات التحفيزية  - Motivational Certificates';
        $student_tests = MotivationalCertificate::query()
            ->has('user')
            ->has('teacher')
            ->hasMorph('model', [Lesson::class, Story::class])
            ->where('user_id', Auth::user()->id)
            ->latest()->paginate(10);

        return view('user.motivational_certificates', compact('student_tests', 'title'));
    }

    public function certificate($id)
    {
        $title = 'الشهادة';
        $student = Auth::user();
        $certificate = MotivationalCertificate::query()->where('user_id', $student->id)->find($id);
        if (!$certificate)
            return redirect()->route('home')->with('message', 'certificate not found')->with('m-class', 'error');

        return view('general.user.motivational_certificate',compact('certificate', 'title'));
    }
}
