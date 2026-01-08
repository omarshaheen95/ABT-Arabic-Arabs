@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/story-training.css')}}?v=1"/>
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.css')}}">
@endpush

@section('page-name', 'dashboard')

@section('content')
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle">{{$story->name}}</h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{Redirect::back()}}" class="breadcrumb-item">{{t('Stories')}}</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active">{{$story->name}}</span>
                </nav>
            </div>
            <a href="{{route('story.story-index',['id'=>$story->id,'key' => 'test'])}}" class="practice-btn" >
                {{t('Go to Test')}}
            </a>
        </header>

        <!-- Tabs and Content Wrapper -->
        <div class="lesson-tabs-wrapper">
            <!-- Tab Navigation Container -->
            <div class="lesson-tabs-container">
                <nav class="lesson-tabs" id="lessonTabs" role="tablist" aria-label="Lesson sections">
                    <button class="lesson-tab active" role="tab" aria-selected="true" data-section-index="0">Read</button>
                    <button class="lesson-tab" role="tab" aria-selected="false" data-section-index="1">Certified Recording</button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="lesson-content show" id="lessonContent">
                <div class="section-content" id="readSection" style="background: #ffffff;border-radius: 15px;padding-bottom: 40px">
                    <div class="read-section">
                        <div class="section-header">
                            <div class="section-icon">📖</div>
                            <div class="section-text">
                                <h2 class="section-title">اقرأ القصة وسجل صوتك</h2>
                                <p class="section-subtitle">Read the story and record your voice</p>
                            </div>
                        </div>
                        <div class="read-text-container">
                            <div class="read-text">
                                {!! $story->content !!}
                            </div>
                            @if($user_story && !is_null($user_story->record))
                                <div class="user-recording-status-card">
                                    <!-- Status Badge -->
                                    <div class="status-header">
                                        @if($user_story->status == 'pending')
                                            <div class="status-badge status-pending">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
                                                </svg>
                                                <span>{{$user_story->status_name}}</span>
                                            </div>
                                        @endif
                                        @if($user_story->status == 'corrected')
                                            <div class="status-badge status-corrected">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="currentColor"/>
                                                </svg>
                                                <span>{{$user_story->status_name}}</span>
                                            </div>
                                            <div class="score-badge">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z" fill="currentColor"/>
                                                </svg>
                                                <span class="score-value">{{$user_story->mark}}</span>
                                                <span class="score-total">/10</span>
                                            </div>
                                        @endif
                                        @if($user_story->status == 'returned')
                                            <div class="status-badge status-returned">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="currentColor"/>
                                                </svg>
                                                <span>{{$user_story->status_name}}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Audio Player -->
                                    <div class="recording-playback-section">
                                        <div class="playback-label">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" fill="#6366F1"/>
                                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" fill="#6366F1"/>
                                            </svg>
                                            <span>{{t('Your Recording')}}</span>
                                        </div>
                                        <div class="audio-player-wrapper">
                                            <div class="audio-player student-audio-player-corrected">
                                                <audio preload="metadata">
                                                    <source src="{{asset($user_story->record)}}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Feedback Section (Only for Corrected) -->
                                    @if($user_story->status == 'corrected')
                                        <div class="teacher-feedback-section">
                                            <div class="feedback-header">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z" fill="#10B981"/>
                                                </svg>
                                                <h4>{{t('Teacher Feedback')}}</h4>
                                            </div>

                                            <div class="feedback-content-wrapper">
                                                <!-- Text Feedback -->
                                                <div class="feedback-item">
                                                    <div class="feedback-label">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z" fill="#059669"/>
                                                        </svg>
                                                        <span>{{t('Written Feedback')}}</span>
                                                    </div>
                                                    <div class="feedback-text">
                                                        {{$user_story->feedback_message ?? t('No Feedback')}}
                                                    </div>
                                                </div>

                                                <!-- Audio Feedback -->
                                                @if($user_story && !is_null($user_story->feedback_audio_message))
                                                    <div class="feedback-item">
                                                        <div class="feedback-label">
                                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" fill="#059669"/>
                                                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" fill="#059669"/>
                                                            </svg>
                                                            <span>{{t('Audio Feedback')}}</span>
                                                        </div>
                                                        <div class="audio-player-wrapper">
                                                            <div class="audio-player teacher-audio-player">
                                                                <audio preload="metadata">
                                                                    <source src="{{asset($user_story->feedback_audio_message)}}" type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(!$user_story || ($user_story && $user_story->status == 'pending') || ($user_story && $user_story->status == 'returned'))
                                <div class="recording-controls" id="readRecordingInitial">
                                    <button class="record-btn" id="recordBtn">
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
                                    <p class="recording-label">Record Your Answer</p>
                                </div>
                                <div class="recording-controls-container" id="readRecordingControls">
                                    <button class="recording-control-btn recording-stop-btn" id="recordingStartBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" class="bi bi-stop-fill" viewBox="0 0 16 16" id="Stop-Fill--Streamline-Bootstrap" height="22" width="22" >
                                            <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1 -1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5" stroke-width="1"></path>
                                        </svg>
                                    </button>
                                    <button class="recording-control-btn recording-play-btn" id="recordingPlayPauseBtn" style="display: none;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 5V19L19 12L8 5Z" fill="white"/>
                                        </svg>
                                    </button>
                                    <div class="recording-timer" id="readTimer">0:01</div>
                                    <button class="recording-control-btn recording-remove-btn" id="recordingRemoveBtn" style="display: none;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18 18L6 6M6 18L18 6" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="recording-actions" id="readRecordingActions" style="display: none;">
                                    <button class="save-recording-btn" id="saveReadRecordingBtn">
                                        <span class="btn-text">{{t('Save Recording')}}</span>
                                        <span class="btn-spinner" style="display: none;">
                                            <span class="spinner-icon"></span>
                                            <span>{{t('Saving...')}}</span>
                                        </span>
                                    </button>
                                    <button class="delete-recording-btn" id="deleteReadRecordingBtn">{{t('Delete')}}</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if(count($users_story))
                    <div class="section-content" id="certifiedSection" style="background: #ffffff;border-radius: 15px;padding-bottom: 40px; margin-top: 30px;display: none;">
                        <div class="read-section">
                            <div class="section-header">
                                <div class="section-icon">🎙️</div>
                                <div class="section-text">
                                    <h2 class="section-title">تسجيلات معتمدة</h2>
                                    <p class="section-subtitle">Certified Recordings</p>
                                </div>
                            </div>
                            <div class="read-text-container">
                                @foreach($users_story as $user_story_item)
                                    <div class="certified-recording-item">
                                        <div class="recording-user-info">
                                            <h4 class="user-name">{{$user_story_item->user->name}}</h4>
                                            @if($user_story_item->status == 'corrected')
                                                <div class="user-score">
                                                    <span class="score-label">{{t('Score')}}:</span>
                                                    <span class="score-value">{{$user_story_item->mark}}</span>
                                                    <span class="score-total">/ 10</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="recording-audio-player">
                                            <div class="audio-player certified-audio-player">
                                                <audio preload="metadata">
                                                    <source src="{{asset($user_story_item->record)}}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        </div>
                                        @if($user_story_item->status =='corrected')
                                            <div class="recording-feedback">
                                                <div class="feedback-item">
                                                    <div class="feedback-label">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#10B981"/>
                                                        </svg>
                                                        <span>{{ t('Feedback') }}</span>
                                                    </div>
                                                    <div class="feedback-content">
                                                        {{$user_story_item->feedback_message??t('No Feedback')}}
                                                    </div>
                                                </div>
                                                @if($user_story_item && !is_null($user_story_item->feedback_audio_message))
                                                    <div class="feedback-item">
                                                        <div class="feedback-label">
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" fill="#10B981"/>
                                                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" fill="#10B981"/>
                                                            </svg>
                                                            <span>{{ t('Feedback Recording') }}</span>
                                                        </div>
                                                        <div class="feedback-audio">
                                                            <div class="audio-player certified-feedback-audio-player">
                                                                <audio preload="metadata">
                                                                    <source src="{{asset($user_story_item->feedback_audio_message)}}" type="audio/mpeg">
                                                                    Your browser does not support the audio element.
                                                                </audio>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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
                <h3 class="success-title">{{t('Success')}}!</h3>
                <p class="success-text" id="successText">{{t('You have successfully submitted your tasks to your teacher')}}</p>
                <div class="success-buttons">
                    <a href="{{route('story.story-index',['id'=>$story->id,'key' => 'test'])}}" class="practice-btn-dialog">
                        <span>{{t('Go to Test')}}</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <button class="ok-btn" id="okBtn" onclick="location.reload()">{{t('OK')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.js')}}"></script>
    <script>
        // Pass URLs to JavaScript
        const SAVE_RECORDING_URL = '{{route('save-read-record-answer', $story->id)}}';
        const TRACKING_URL = '{{route('story.tracking', [$story->id, 'reading'])}}';
        const STORY_ID = {{$story->id}};
    </script>
    <script src="{{asset('user_assets/js/pages/story-training.js')}}?v=1"></script>
@endpush
