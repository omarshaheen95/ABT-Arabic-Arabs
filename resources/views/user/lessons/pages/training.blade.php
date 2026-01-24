@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/training-quiz.css')}}?v=1" />
@endpush

@section('page-name', 'lesson-training')

@section('content')
    <div class="training-quiz-container">
        <div id="trainingQuizContent">
            <h1 class="training-quiz-title">{{$lesson->name}}</h1>
            <div class="training-quiz-content-wrapper">


                @foreach($questions as $question)
                    @switch($question->type)
                        @case(1)
                            @include('user.lessons.pages.questions.true_false')
                            @break
                        @case(2)
                            @include('user.lessons.pages.questions.multiple_choice')
                            @break
                        @case(3)
                            @include('user.lessons.pages.questions.matching')
                            @break
                        @case(4)
                            @include('user.lessons.pages.questions.sorting')
                            @break
                    @endswitch
                @endforeach

                <!-- Navigation -->
                <div class="training-quiz-navigation">
                    <button class="training-quiz-nav-btn" id="prevBtn" disabled>
                        السابق
                    </button>
                    <button class="training-quiz-nav-btn primary" id="nextBtn">
                        التالي
                    </button>
                </div>

                <!-- Question Numbers -->
                <div class="question-numbers">

                    @foreach($questions as $question)
                        <button class="question-number-btn @if($loop->index==0) active @endif" data-question-index="{{$loop->index}}">{{$loop->iteration}}</button>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    @include('user.general.components.loading_dialog')
    <!-- Training Completion Dialog -->
    <div class="completion-dialog-overlay" id="completionDialog" style="display: none;">
        <div class="completion-dialog">
            <div class="completion-content">
                <div class="completion-icon">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="40" fill="#39D670" fill-opacity="0.1"/>
                        <path d="M55 30L35 50L25 40" stroke="#39D670" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="completion-title">أحسنت! لقد أكملت التدريب</h3>
                <div class="completion-buttons">
                    <button class="completion-btn primary" id="goToTestBtn">
                        <span>انتقل للاختبار</span>
                    </button>
                    <button class="completion-btn secondary" id="goToLessonsBtn">
                        <span>العودة للدروس</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // Quiz data from backend
        var QUIZ_DATA = @json($quizData);
        var LESSON_TRACKING_URL = '{{route('lesson.tracking', [$lesson->id, 'practise'])}}';

        // Navigation URLs
        var GO_TO_TEST_URL = '{{route('lesson.lesson-index', [$lesson->id, 'test'])}}';
        var GO_TO_LESSONS_URL = '{{route('lesson.lessons-by-level', ['id'=>$lesson->grade_id,'type'=>$lesson->lesson_type])}}';
    </script>

    <script src="{{asset('user_assets/js/pages/training-quiz.js')}}?v=2"></script>
@endpush
