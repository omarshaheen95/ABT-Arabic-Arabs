@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/quiz.css')}}?v=1" />
@endpush

@section('page-name', 'quiz')

@section('content')
    <div class="quiz-container">
        <div id="quizContent">
            <h1 class="quiz-title">{{$lesson->name}}</h1>
            <div class="quiz-content-wrapper">

                <!-- Quiz Progress Bar and Timer -->
                <div class="quiz-progress-container">
                    <div class="quiz-progress">
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progressBar" style="width: 20%"></div>
                        </div>
                    </div>
                    <div class="quiz-timer" id="quizTimer">
                        <span class="timer-text">15:00</span>
                    </div>
                </div>

                <form id="testForm" method="POST" action="{{route('lesson.save-test', $lesson->id)}}">
                    @csrf
                    <input type="hidden" name="start_at" id="startTimeInput" value="{{\Carbon\Carbon::now()}}">

                @foreach($questions as $question)
                    @switch($question->type)
                        @case(1)
                            @include('user.lessons.pages.questions.true_false', ['type' => 'test'])
                            @break
                        @case(2)
                            @include('user.lessons.pages.questions.multiple_choice', ['type' => 'test'])
                            @break
                        @case(3)
                            @include('user.lessons.pages.questions.matching', ['type' => 'test'])
                            @break
                        @case(4)
                            @include('user.lessons.pages.questions.sorting', ['type' => 'test'])
                            @break
                    @endswitch
                @endforeach

                <!-- Navigation -->
                <div class="quiz-navigation">
                    <button type="button" class="quiz-nav-btn" id="prevBtn" disabled>
                        Previous
                    </button>
                    <button type="button" class="quiz-nav-btn primary" id="nextBtn">
                        Next
                    </button>
                    <button type="submit" class="quiz-nav-btn submit-btn" id="submitBtn" style="display: none;">
                        <span>إنهاء الاختبار</span>
                        <span class="btn-subtitle">Submit Test</span>
                    </button>
                </div>

                </form>

            </div>
        </div>
    </div>

    @include('user.general.components.loading_dialog')

    <!-- Submit Confirmation Dialog -->
    <div class="submit-confirmation-dialog-overlay" id="submitConfirmDialog" style="display: none;">
        <div class="submit-confirmation-dialog">
            <div class="submit-dialog-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#FF9800"/>
                </svg>
            </div>
            <h3 class="submit-dialog-title">تأكيد إنهاء الاختبار</h3>
            <p class="submit-dialog-subtitle">Confirm Test Submission</p>
            <p class="submit-dialog-message">
                هل أنت متأكد من إنهاء الاختبار؟<br>. لن تتمكن من تغيير إجاباتك بعد التقديم
            </p>
            <p class="submit-dialog-message-en">
                Are you sure you want to submit the test? <br> You won't be able to change your answers after submission.
            </p>
            <div class="submit-dialog-actions">
                <button class="submit-dialog-btn cancel-btn" id="cancelSubmitBtn">
                    <span>إلغاء</span>
                    <span class="btn-subtitle">Cancel</span>
                </button>
                <button class="submit-dialog-btn confirm-btn" id="confirmSubmitBtn">
                    <span>تأكيد التقديم</span>
                    <span class="btn-subtitle">Confirm Submit</span>
                </button>
            </div>
        </div>
    </div>

@endsection
@push('script')


@endpush
