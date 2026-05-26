@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/lesson.css')}}"/>
@endpush

@section('page-name', 'story-watch')

@section('content')
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle"> {{$story->name}}</h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{Redirect::back()}}" class="breadcrumb-item">القصص</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active">{{$story->name}}</span>
                </nav>
            </div>
            <a href="{{route('story.story-index',['id'=>$story->id,'key' => 'read'])}}" class="practice-btn" >
                اذهب الى القراءة
            </a>
        </header>

        <!-- Tabs and Content Wrapper -->
        <div class="lesson-tabs-wrapper">
            <div id="video{{$story->id}}"></div>
            @if(!is_null($story->alternative_video))
                <div id="alternative_video{{$story->id}}" style="margin-top: 40px"></div>
            @endif
        </div>
    </div>


@endsection
@push('script')
    <script src="{{asset('user_assets/lib/player-js.js')}}"></script>

    <script>


        // تهيئة المشغل بعد تحميل LayoutManager
        function initializePlayer() {
            var player_{{$story->id}} = new Playerjs({
                id: "video{{$story->id}}",
                file: '{{asset($story->video)}}',
            });
            @if(!is_null($story->alternative_video))
            var player_alternative_video_{{$story->id}} = new Playerjs({
                id: "alternative_video{{$story->id}}",
                file: '{{asset($story->alternative_video)}}',
            });
            @endif
        }

        $(document).ready(function () {
            // تأجيل تهيئة المشغل حتى ينتهي LayoutManager من الرندر
            setTimeout(function () {
                initializePlayer();
            }, 200);

            // تتبع المشاهدة
            setTimeout(function () {
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '{{route('story.tracking', [$story->id, 'watching'])}}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function (errMsg) {
                    }
                });

            }, 10000);
        });

    </script>
@endpush
