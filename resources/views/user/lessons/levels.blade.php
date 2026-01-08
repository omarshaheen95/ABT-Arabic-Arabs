@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/lessons-grid.css')}}"/>
@endpush
@section('page-name', 'dashboard')

@section('content')
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
{{--            <h1 class="lessons-page-title">{{$level->name}}</h1>--}}
            {{--            <p class="lessons-page-subtitle">{{t('Browse all available lessons')}}</p>--}}
        </header>
        @if(isset($grades) && count($grades)>0)
            @foreach($grades as $grade)
                <header class="lessons-page-header">
                                <h2 class="lessons-page-title">الصف {{$grade->grade_name}}</h2>
                    {{--            <p class="lessons-page-subtitle">{{t('Browse all available lessons')}}</p>--}}
                </header>
{{--                <h2>--}}
{{--                    <span style="font-weight: bold; color: red">الصف {{$grade->grade_name}}:</span>--}}
{{--                </h2>--}}
        <div class="lessons-grid" id="lessons-grid">


                @foreach($grade->grade_skills as $data)
                        {{--                    <article class="lesson-card-grid {{ $isCompleted ? 'lesson-card--completed' : '' }}"--}}
                        <article class="lesson-card-grid" style="animation-delay: 0.1s;">
                            {{--                        @if($isCompleted)--}}
                            {{--                            <div class="lesson-completed-badge">--}}
                            {{--                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"--}}
                            {{--                                     xmlns="http://www.w3.org/2000/svg">--}}
                            {{--                                    <path d="M2 7L5.5 10.5L12 3.5" stroke="currentColor" stroke-width="2"--}}
                            {{--                                          stroke-linecap="round" stroke-linejoin="round"/>--}}
                            {{--                                </svg>--}}
                            {{--                                <span>{{t('Completed')}}</span>--}}
                            {{--                            </div>--}}
                            {{--                        @endif--}}
                            <div class="lesson-icon-container">
                                <img class="lesson-icon-grid" src="{{asset('steps/'.$data['skill'].'.svg')}}"
                                     alt="{{$data['skill']}}"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid">{{$data['title']}}</h5>
                            </div>
                            <div class="lesson-actions">
                                <button class="lesson-action-btn lesson-action-btn--accent" data-action="test"
                                        data-url="{{route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$data['skill']])}}">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 1L8.5 5H12.5L9.5 7.5L10.5 11.5L7 9L3.5 11.5L4.5 7.5L1.5 5H5.5L7 1Z"
                                              stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    </svg>
                                    <span>دخول</span>
                                </button>
                            </div>
                        </article>
                @endforeach



        </div>
            @endforeach
        @endif
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/lessons-grid.js')}}"></script>
@endpush
