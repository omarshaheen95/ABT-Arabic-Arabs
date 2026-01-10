@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/speaking-test.css')}}?v=1"/>
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.css')}}">
    <style>

    </style>
@endpush

@section('page-name', 'dashboard')

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
            </div>
            <a href="{{route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])}}" class="practice-btn">
                العودة إلى الدروس
            </a>
        </header>

        <!-- Questions Container -->
        <div class="lesson-tabs-wrapper">
            <div class="lesson-content show" id="lessonContent">
                <form action="{{route('lesson.save-lesson-speaking-test', $lesson->id)}}" enctype="multipart/form-data" id="speaking_test_form" method="post" data-redirect-url="{{route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])}}">
                    {{csrf_field()}}
                    <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">
                    <input type="hidden" name="total_questions" value="{{count($questions)}}">

                    @php
                        $counter = 1;
                    @endphp

                    @foreach($questions as $question)
                        <div id="question-{{$counter}}" class="section-content question-item @if($loop->first) active @else d-none @endif" style="background: #ffffff;border-radius: 15px;padding: 30px; margin-bottom: 20px;">
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
                                                <div class="w-100">
                                                    <img src="{{asset($question->getFirstMediaUrl('imageQuestion'))}}" style="max-width: 450px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" alt="Question Image">
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @php
                                        $hasExistingRecording = isset($existingResults[$question->id]) && !empty($existingResults[$question->id]['attachment']);
                                    @endphp

                                    <!-- Existing Recording Display -->
                                    @if($hasExistingRecording)
                                        <div class="existing-recording-container" id="existingRecording-{{$question->id}}" style="padding: 30px 0;">
                                            <div style="background: #f0fdf4; border-radius: 12px; padding: 25px; border: 2px solid #138944; margin-bottom: 20px;">
                                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#138944"/>
                                                    </svg>
                                                    <span style="color: #138944; font-weight: 600; font-size: 16px;">تم تسليم تسجيلك بنجاح</span>
                                                </div>
                                                <div class="audio-player-wrapper" style="display:flex;flex-direction:row;overflow-x:auto; margin: 0 auto;" dir="ltr">
                                                    <div class="audio-player existing-recording-player">
                                                        <audio preload="metadata">
                                                            <source src="{{asset($existingResults[$question->id]['attachment'])}}" type="audio/wav">
                                                        </audio>
                                                    </div>
                                                </div>
                                                <div style="text-align: center; margin-top: 20px;">
                                                    <button type="button" class="rerecord-btn" data-question-id="{{$question->id}}" >
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 5px;">
                                                            <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z" fill="white"/>
                                                        </svg>
                                                        إعادة التسجيل
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Recording Controls -->
                                    <div class="recording-controls" id="recordingInitial-{{$question->id}}" style="display: {{ $hasExistingRecording ? 'none' : 'flex' }}; flex-direction: column; align-items: center; gap: 15px; padding: 30px 0;">
                                        <button type="button" class="record-btn startRecordingBtn" data-question-id="{{$question->id}}" style="transition: all 0.3s ease;">
                                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_1837_2433)">
                                                    <path d="M28 13.3333C28 12.9797 27.8595 12.6406 27.6095 12.3905C27.3594 12.1405 27.0203 12 26.6667 12C26.313 12 25.9739 12.1405 25.7239 12.3905C25.4738 12.6406 25.3333 12.9797 25.3333 13.3333C25.3333 15.8087 24.35 18.1827 22.5997 19.933C20.8493 21.6833 18.4754 22.6667 16 22.6667C13.5246 22.6667 11.1507 21.6833 9.40034 19.933C7.65 18.1827 6.66667 15.8087 6.66667 13.3333C6.66667 12.9797 6.52619 12.6406 6.27614 12.3905C6.02609 12.1405 5.68696 12 5.33333 12C4.97971 12 4.64057 12.1405 4.39052 12.3905C4.14048 12.6406 4 12.9797 4 13.3333C4.004 16.2835 5.0934 19.129 7.06057 21.3276C9.02773 23.5261 11.7351 24.924 14.6667 25.2547V29.3333H12C11.6464 29.3333 11.3072 29.4738 11.0572 29.7239C10.8071 29.9739 10.6667 30.313 10.6667 30.6667C10.6667 31.0203 10.8071 31.3594 11.0572 31.6095C11.3072 31.8595 11.6464 32 12 32H20C20.3536 32 20.6928 31.8595 20.9428 31.6095C21.1929 31.3594 21.3333 31.0203 21.3333 30.6667C21.3333 30.313 21.1929 29.9739 20.9428 29.7239C20.6928 29.4738 20.3536 29.3333 20 29.3333H17.3333V25.2547C20.2649 24.924 22.9723 23.5261 24.9394 21.3276C26.9066 19.129 27.996 16.2835 28 13.3333Z" fill="white"/>
                                                    <path d="M15.9987 20C17.7662 19.9979 19.4606 19.2948 20.7104 18.045C21.9602 16.7953 22.6632 15.1008 22.6654 13.3333V6.66667C22.6654 4.89856 21.963 3.20286 20.7127 1.95262C19.4625 0.702379 17.7668 0 15.9987 0C14.2306 0 12.5349 0.702379 11.2847 1.95262C10.0344 3.20286 9.33203 4.89856 9.33203 6.66667V13.3333C9.33415 15.1008 10.0372 16.7953 11.287 18.045C12.5368 19.2948 14.2312 19.9979 15.9987 20Z" fill="white"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1837_2433">
                                                        <rect width="32" height="32" fill="white"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </button>
                                        <p class="recording-label" style="font-size: 16px; color: #666; margin: 0;">قم بتسجيل إجابتك</p>
                                    </div>

                                    <div class="recording-controls-container" id="recordingControls-{{$question->id}}" style="display: none; flex-direction: row; align-items: center; justify-content: center; gap: 15px; padding: 30px 0; flex-wrap: wrap;">
                                        <button type="button" class="recording-control-btn recording-stop-btn stopRecordingBtn" data-question-id="{{$question->id}}" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" class="bi bi-stop-fill" viewBox="0 0 16 16" height="22" width="22">
                                                <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1 -1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5" stroke-width="1"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="recording-control-btn recording-play-btn playPauseBtn" data-question-id="{{$question->id}}" style="display: none;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 5V19L19 12L8 5Z" fill="white"/>
                                            </svg>
                                        </button>
                                        <div class="recording-timer" id="timer-{{$question->id}}" style="font-size: 24px; font-weight: 600; color: #2ea45e; min-width: 80px; text-align: center; display: none;">0:00</div>
                                        <button type="button" class="recording-control-btn recording-remove-btn deleteRecordingBtn" data-question-id="{{$question->id}}" style="display: none;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 18L6 6M6 18L18 6" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="recording-actions" id="recordingActions-{{$question->id}}" style="display: none; flex-direction: row; align-items: center; justify-content: center; gap: 15px; padding: 20px 0;">
                                        <button type="button" class="save-recording-btn saveRecordingBtn" data-question-id="{{$question->id}}">
                                            <span class="btn-text">حفظ التسجيل</span>
                                            <span class="btn-spinner" style="display: none; align-items: center; gap: 8px;">
                                                <span class="spinner-icon" style="width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite;"></span>
                                                <span>جاري الحفظ ...</span>
                                            </span>
                                        </button>
{{--                                        <button type="button" class="delete-recording-btn deleteRecordingBtn" data-question-id="{{$question->id}}" style="padding: 12px 30px; background: #dc3545; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">{{t('Delete')}}</button>--}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $counter++;
                        @endphp
                    @endforeach

{{--                    <!-- Navigation Buttons -->--}}
{{--                    @if(count($questions) > 1)--}}
{{--                        <div class="d-flex justify-content-center gap-3 mt-4" style="padding: 0 20px;">--}}
{{--                            <button type="button" class="btn btn-light border d-none" id="previousQuestion" style="padding: 12px 24px; font-size: 16px; border-radius: 10px;">--}}
{{--                                <span>{{t('Previous')}}</span>--}}
{{--                            </button>--}}
{{--                            <button type="button" class="btn btn-primary" id="nextQuestion" style="padding: 12px 24px; font-size: 16px; border-radius: 10px; background: linear-gradient(135deg, #5984E5FF 0%, #1E4396FF 100%); border: none;">--}}
{{--                                <span>{{t('Next')}}</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </form>
            </div>
        </div>
    </div>

    <!-- Include Loading Dialog -->
    @include('user.general.components.loading_dialog')

    <!-- Custom Success Dialog -->
    <div class="success-dialog-overlay" id="successDialog" style="display: none;">
        <div class="success-dialog">
            <div class="success-content">
                <div class="success-icon">
                    <svg viewBox="0 0 80 80">
                        <circle class="success-circle" cx="40" cy="40" r="35" fill="none" stroke-width="4"></circle>
                        <path class="success-check" d="M25 40 L35 50 L55 30" fill="none" stroke-width="4"></path>
                    </svg>
                </div>
                <h3 class="success-title">نجاح العملية !</h3>
                <p class="success-text" id="successText">تم حفظ التسجيل بنجاح</p>
                <div class="success-buttons">
                    <button class="ok-btn" id="okBtn">متابعة</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.js')}}"></script>
    <script src="{{asset('user_assets/js/pages/speaking-test.js')}}"></script>
@endpush
