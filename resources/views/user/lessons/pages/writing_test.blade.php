@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/writing-test.css')}}?v=8"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets_v1/js/keyboard/keyboard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets_v1/js/keyboard/jquery-ui.css')}}">
@endpush

@section('page-name', 'lesson-writing-test')

@section('content')
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle">{{$lesson->name}}</h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])}}" class="breadcrumb-item">{{t('Lessons')}}</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active">{{$lesson->name}}</span>
                </nav>
                @if($isCorrected)
                    <div class="score-badge-container">
                        <div class="score-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#138944"/>
                            </svg>
                            <span class="score-text">النتيجة:</span>
                            <span class="score-value">{{$totalScore}}</span>
                            <span class="score-total">/{{$maxScore}}</span>
                        </div>
                    </div>
                @endif
            </div>
            <a href="{{route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])}}" class="practice-btn">
                العودة إلى الدروس
            </a>
        </header>

        <!-- Questions Container -->
        <div class="lesson-tabs-wrapper">
            <div class="lesson-content show" id="lessonContent">
                <form action="{{route('lesson.save-writing-test', $lesson->id)}}" enctype="multipart/form-data" id="writing_test_form" method="post" data-redirect-url="{{route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])}}">
                    {{csrf_field()}}
                    <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">

                    @php
                        $counter = 1;
                    @endphp

                    @foreach($questions as $question)
                        <div id="question-{{$counter}}" class="section-content question-item @if($loop->first) active @else hidden @endif" style="background: #ffffff;border-radius: 15px;padding: 15px; margin-bottom: 20px;">
                            <div class="read-section">
                                <div class="section-header" style="margin-bottom: 25px;" dir="rtl">
                                    <div class="section-text">
                                        <p class="section-title">{{$question->content}}</p>
                                    </div>
                                </div>

                                <div class="read-text-container">
                                    <!-- Lesson Content (Instructions) -->
                                    @if(!is_null($lesson->content))
                                        <div class="lesson-content-box" style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #5984E5;">
                                            <div style="margin-bottom: 10px;">
                                                <strong style="color: #1E4396; font-size: 16px;">{{t('Instructions')}}</strong>
                                            </div>
                                            <div style="color: #333; line-height: 1.8;">
                                                {!! $lesson->content !!}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Question Media -->
                                    @if($question->getFirstMediaUrl('imageQuestion'))
                                        <div class="question-media-container" style="margin-bottom: 30px; text-align: center;">
                                            @if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                <div class="audio-player-wrapper" style="max-width: 500px; margin: 0 auto;">
                                                    <div class="audio-player">
                                                        <audio preload="metadata">
                                                            <source src="{{asset($question->getFirstMediaUrl('imageQuestion'))}}" type="audio/mpeg">
                                                        </audio>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="full-width">
                                                    <img src="{{asset($question->getFirstMediaUrl('imageQuestion'))}}" style="max-width: 450px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" alt="Question Image">
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @php
                                        $hasExistingAnswer = isset($existingResults[$question->id]);
                                    @endphp

                                    <!-- Existing Answer Display -->
                                    @if($hasExistingAnswer)
                                        <div class="existing-answer-container" id="existingAnswer-{{$question->id}}">
                                            <div class="existing-answer-header">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#138944"/>
                                                </svg>
                                                <span class="existing-answer-text">
                                                    @if($isCorrected)
                                                        تم التصحيح
                                                    @else
                                                        تم حفظ إجابتك بنجاح
                                                    @endif
                                                </span>
                                            </div>
                                            @if(!empty($existingResults[$question->id]['result']))
                                                <div class="word-count">عدد الكلمات: <span class="word-count-value">{{ str_word_count($existingResults[$question->id]['result']) }}</span></div>
                                                <div style="position: relative;">
                                                    <textarea class="answer-textarea existing-answer-textarea" readonly>{{$existingResults[$question->id]['result']}}</textarea>
                                                </div>
                                            @endif
                                            @if(!empty($existingResults[$question->id]['attachment']))
                                                <div class="existing-attachment-link">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5-5 5 5M12 5v12" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <a href="{{asset($existingResults[$question->id]['attachment'])}}" target="_blank">عرض الملف المرفق</a>
                                                </div>
                                            @endif
                                            @if(!$isCorrected)
                                                <div class="edit-answer-section">
                                                    <button type="button" class="edit-answer-btn" data-question-id="{{$question->id}}">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        تعديل الإجابة
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Writing Answer Input (Always present, hidden if answer exists) -->
                                    <div class="write-answer-container @if($hasExistingAnswer) hidden @endif" id="answerContainer-{{$question->id}}">
                                        <div class="word-count">عدد الكلمات: <span class="word-count-value" id="wordCount-{{$question->id}}">@if($hasExistingAnswer && !empty($existingResults[$question->id]['result'])){{ str_word_count($existingResults[$question->id]['result']) }}@else 0 @endif</span></div>
                                        <div style="position: relative;">
                                            <textarea
                                                class="answer-textarea keyboard"
                                                id="writing_{{$question->id}}"
                                                name="writing_answer[{{$question->id}}]"
                                                placeholder="اكتب إجابتك هنا"
                                                onpaste="return false;"
                                                dir="rtl">@if($hasExistingAnswer && !empty($existingResults[$question->id]['result'])){{$existingResults[$question->id]['result']}}@endif</textarea>
                                        </div>
                                        <div class="upload-section">
                                            <label class="upload-btn" for="fileUpload-{{$question->id}}">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                إرفاق ملف
                                            </label>
                                            <input type="file" id="fileUpload-{{$question->id}}" name="writing_attachment[{{$question->id}}]" style="display: none;" accept="image/*,.pdf,.doc,.docx">
                                            <span class="file-name" id="fileName-{{$question->id}}"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $counter++;
                        @endphp
                    @endforeach

                    <!-- Navigation Buttons -->
                    @if(!$isCorrected)
                        @if(count($questions) > 1)
                            <div class="flex-center navigation-buttons">
                                <button type="button" class="hidden" id="previousQuestion">
                                    <span>{{t('Previous')}}</span>
                                </button>
                                <button type="button" class="@if(count($questions) == 1) hidden @endif" id="nextQuestion">
                                    <span>{{t('Next')}}</span>
                                </button>
                                <button type="button" class="@if(count($questions) == 1) @else hidden @endif endExam" id="confirmSaveBtn">
                                    <span>حفظ و إنهاء</span>
                                </button>
                            </div>

                            <!-- Question Navigation List -->
                            <div class="table-footer">
                                <ul class="nav-list" id="questionListLink"></ul>
                            </div>
                        @else
                            <div style="display: flex;justify-content: center;padding-bottom: 30px">
                                <button type="button" class="btn-save endExam @if($hasExistingAnswer) hidden @endif" id="confirmSaveBtn">
                                    <span>حفظ و إنهاء</span>
                                </button>
                            </div>
                        @endif
                    @else
                        <!-- Question Navigation List for viewing -->
                        @if(count($questions) > 1)
                            <div class="flex-center navigation-buttons">
                                <button type="button" class="hidden" id="previousQuestion">
                                    <span>{{t('Previous')}}</span>
                                </button>
                                <button type="button" id="nextQuestion">
                                    <span>{{t('Next')}}</span>
                                </button>
                            </div>
                            <div class="table-footer">
                                <ul class="nav-list" id="questionListLink"></ul>
                            </div>
                        @endif
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Include Loading Dialog -->
    @include('user.general.components.loading_dialog')

    <!-- Success Dialog -->
    <div class="success-dialog-overlay" id="successDialog" style="display: none;">
        <div class="success-dialog">
            <div class="success-content">
                <div class="success-icon">
                    <svg viewBox="0 0 80 80">
                        <circle class="success-circle" cx="40" cy="40" r="35" fill="none" stroke-width="4"></circle>
                        <path class="success-check" d="M25 40 L35 50 L55 30" fill="none" stroke-width="4"></path>
                    </svg>
                </div>
                <h3 class="success-title" id="successTitle">نجاح العملية !</h3>
                <p class="success-text" id="successText">تم حفظ إجاباتك بنجاح</p>
                <div class="success-buttons">
                    <button class="cancel-btn" id="cancelBtn" style="display: none;">إلغاء</button>
                    <button class="ok-btn" id="okBtn">متابعة</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets_v1/js/keyboard/jquery.keyboard.js')}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('assets_v1/js/keyboard/jquery.keyboard.extension-typing.js')}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('assets_v1/js/keyboard/keyboard-layouts-microsoft.min.js')}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('user_assets/js/pages/writing-test.js')}}?v=7"></script>
@endpush
