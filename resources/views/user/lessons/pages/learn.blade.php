@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/lesson.css')}}?v=3"/>
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/lesson-responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.css')}}">
@endpush

@section('page-name', 'lesson-learn')

@section('content')
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle">{{$lesson->name}}</h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{Redirect::back()}}" class="breadcrumb-item">{{t('Lessons')}}</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active">{{$lesson->name}}</span>
                </nav>
            </div>
            <a href="{{route('lesson.lesson-index',['id'=>$lesson->id,'key' => 'training'])}}" class="practice-btn" >
                اذهب إلى التدريب
            </a>
        </header>

        <!-- Lesson Content -->
        <div class="lesson-content">
            @if($lesson->lesson_type == 'reading')
                <div class="lesson-instruction">
                    <h2>اقرأ النَّص التالي للفَهم والاستيعاب</h2>
                </div>
            @elseif($lesson->lesson_type == 'listening')
                <div class="lesson-instruction">
                    <h2>استمع للنص التاليٍ</h2>
                </div>
            @endif

            <!-- Audio Player Section -->
            @if($lesson->getFirstMediaUrl('audioLessons'))
                <div class="lesson-audio-section">
                    <div class="audio-player">
                        <audio>
                            <source src="{{asset($lesson->getFirstMediaUrl('audioLessons'))}}" type="audio/mpeg"/>
                        </audio>
                    </div>
                </div>
            @endif

            <!-- Video Players Section -->
            @if($lesson->getMedia('videoLessons')->count() > 0)
                <div class="lesson-videos-section">
                    @foreach($lesson->getMedia('videoLessons') as $video)
                        <div class="lesson-video-player">
                            <div id="vid_player_{{$video->id}}"></div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Lesson Text Content -->
            @if($lesson->content)
                <div class="lesson-text-content">
                    {!! $lesson->content !!}
                </div>
            @endif
        </div>
    </div>

@endsection
@push('script')
    <script src="{{asset('user_assets/lib/green-audio-player/green-audio-player.min.js')}}"></script>
    <script src="{{asset('user_assets/lib/player-js.js')}}"></script>

    <script>
        // Initialize video players
        @foreach($lesson->getMedia('videoLessons') as $video)
        var player_{{$video->id}} = new Playerjs({
            id: "vid_player_{{$video->id}}",
            file: '{{asset($video->getUrl())}}',
        });
        @endforeach

        $(document).ready(function () {
            // Initialize audio player
            GreenAudioPlayer.init({
                selector: '.audio-player',
                stopOthersOnPlay: true
            });

            // Track lesson visit after 10 seconds
            setTimeout(function () {
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '{{route('lesson.tracking', [$lesson->id, 'learn'])}}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                    },
                    success: function (data) {
                        console.log('Lesson tracking recorded');
                    },
                    error: function (errMsg) {
                        console.error('Error tracking lesson:', errMsg);
                    }
                });
            }, 10000);
        });
    </script>
@endpush
