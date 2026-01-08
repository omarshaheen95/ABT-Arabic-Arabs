@extends('user.layout')
@push('style')
        <link rel="stylesheet" href="{{asset('user_assets/css/pages/stories-levels-grid.css')}}" />
@endpush
@section('page-name', 'library')

@section('content')
    <div class="stories-levels-container">
        <header class="stories-levels-header">
            <h1 class="stories-levels-title">{{t('My Library')}}</h1>
            <p class="stories-levels-subtitle">{{t('Browse all available stories levels')}}</p>
        </header>

        <div class="stories-levels-grid" id="stories-levels-grid">
               @foreach($levels as $level)
                @php
                    $levelGrade = $level->grade;
                    $levelProgress = $level->progress;
                    $levelStarted = $level->progress > 0;
                    $isCompleted = isset($completed_levels) && in_array($level->id, $completed_levels);
                @endphp
                <!-- Story Level Card -->
            @if($isCompleted)
                <article class="story-level-card story-level-card--completed" data-url="{{route('story.stories-by-level', $levelGrade)}}" style="animation-delay: 0s;">
                    <div class="story-level-icon-container">
                        <img class="story-level-icon" src="{{asset("web_assets/img/levels/new/completed_$levelGrade.svg")}}" alt="@if(app()->getLocale()=='ar')
                                        المستوى {{$levelGrade == 15 ? 'التأسيسي':$levelGrade}}
                                    @else
                                        Level {{$levelGrade == 15 ? 'Foundation':$levelGrade}}
                                    @endif" />
                    </div>
                    <div class="story-level-content">
                        <h3 class="story-level-title">@if(app()->getLocale()=='ar')
                                المستوى {{$levelGrade == 15 ? 'التأسيسي':$levelGrade}}
                            @else
                                Level {{$levelGrade == 15 ? 'Foundation':$levelGrade}}
                            @endif</h3>
                        <p class="story-level-description">

                        </p>
                        <div class="story-level-progress-container">
                            <div class="story-level-progress-bar story-level-progress-bar--completed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="Story progress: 100%">
                                <div class="story-level-progress-bar__fill story-level-progress-bar__fill--completed" style="width: 100%;"></div>
                            </div>
                            <div class="story-level-check-icon">
                                <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5L4.5 8.5L11 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </article>
            @else
                        <article class="story-level-card story-level-card--current" data-url="{{route('story.stories-by-level', $levelGrade)}}" style="animation-delay: 0.1s;">
                            <div class="story-level-icon-container">
                                <img class="story-level-icon" src="{{$levelStarted ? asset("web_assets/img/levels/new/started_$levelGrade.svg"):asset("web_assets/img/levels/new/not_started_$levelGrade.svg")}}" alt="@if(app()->getLocale()=='ar')
                                        المستوى {{$levelGrade == 15 ? 'التأسيسي':$levelGrade}}
                                    @else
                                        Level {{$levelGrade == 15 ? 'Foundation':$levelGrade}}
                                    @endif" />
                            </div>
                            <div class="story-level-content">
                                <h3 class="story-level-title">@if(app()->getLocale()=='ar')
                                        المستوى {{$levelGrade == 15 ? 'التأسيسي':$levelGrade}}
                                    @else
                                        Level {{$levelGrade == 15 ? 'Foundation':$levelGrade}}
                                    @endif</h3>
                                <p class="story-level-description"></p>
                                <div class="story-level-progress-container">
                                    <div class="story-level-progress-bar story-level-progress-bar--current" role="progressbar" aria-valuenow="{{$levelProgress}}" aria-valuemin="0" aria-valuemax="100" aria-label="Story progress: {{$levelProgress}}% completed">
                                        <div class="story-level-progress-bar__fill story-level-progress-bar__fill--current" style="width: {{$levelProgress}}%;"></div>
                                    </div>
                                    <span class="story-level-progress-bar__percentage">{{$levelProgress}}%</span>
                                </div>
                            </div>
                        </article>
                    @endif
{{--                    <article class="course-card-grid " data-url="{{route('story.stories-by-level',$level)}}" style="animation-delay: 0.1s;">--}}
{{--                        <div class="course-icon-container">--}}
{{--                            <img class="course-icon-grid" src="{{asset("web_assets/img/levels/$level.svg")}}" alt="Foundation 2 course icon" />--}}
{{--                        </div>--}}
{{--                        <div class="course-content-grid">--}}
{{--                            <h3 class="course-title-grid">--}}
{{--                                @if(app()->getLocale()=='ar')--}}
{{--                                    المستوى {{$level == 15 ? 'التأسيسي':$level}}--}}
{{--                                @else--}}
{{--                                    Level {{$level == 15 ? 'Foundation':$level}}--}}
{{--                                @endif--}}
{{--                            </h3>--}}
{{--                            <p class="course-description-grid">بداية الرحلة: تعلّم الحروف، الأصوات، والكلمات الأولى.</p>--}}
{{--                        </div>--}}
{{--                    </article>--}}
               @endforeach

        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/stories-levels-grid.js')}}"></script>
@endpush
