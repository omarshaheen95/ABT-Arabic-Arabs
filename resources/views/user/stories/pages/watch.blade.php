@extends('user.layout')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{asset('user_assets/css/pages/lesson.css')}}"/>
@endpush

@section('page-name', 'watch')

@section('content')
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle"> {{$story->name}}</h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{Redirect::back()}}" class="breadcrumb-item">{{t('Stories')}}</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active">{{$story->name}}</span>
                </nav>
            </div>
            <a href="{{route('story.story-index',['id'=>$story->id,'key' => 'read'])}}" class="practice-btn" >
                {{t('Go to Read')}}
            </a>
        </header>

        <!-- Tabs and Content Wrapper -->
        <div class="lesson-tabs-wrapper">
            <div id="video{{$story->id}}"></div>
        </div>
    </div>


@endsection
@push('script')
    <script src="{{asset('user_assets/lib/player-js.js')}}"></script>

    <script>
        // وظيفة لاكتشاف الشبكة
        function getQualityBasedOnNetwork() {
            if (navigator.connection) {
                const connection = navigator.connection;
                const speed = connection.effectiveType;

                switch (speed) {
                    case 'slow-2g':
                    case '2g':
                        return '360p';
                    case '3g':
                        return '720p';
                    case '4g':
                    default:
                        return '1080p';
                }
            }
            return '720p';
        }

        // تهيئة المشغل بعد تحميل LayoutManager
        function initializePlayer() {
            @if(!$story->media->count())
            var player_{{$story->id}} = new Playerjs({
                id: "video{{$story->id}}",
                file: '{{asset($story->video)}}',
            });
            @else
            const videoFiles = {
                @foreach($story->media as $media)
                "{{$media->quality}}": "{{$media->path}}",
                @endforeach
            };

            const initialQuality = getQualityBasedOnNetwork();
            const initialFile = videoFiles["360"];
            var thumbnail = '{{$story->media->first()->thumbnail}}';

            console.log(videoFiles, initialQuality);

            var player_{{$story->id}} = new Playerjs({
                id: "video{{$story->id}}",
                file: [
                        @foreach($story->media as $media)
                    {
                        file: "{{$media->path}}",
                        label: "{{$media->quality}}",
                        title: "{{$media->quality}}",
                    },
                    @endforeach
                ],
                poster: thumbnail,
                default_file: initialFile,
                default_quality: getQualityBasedOnNetwork(),
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
