@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/lessons-grid.css')}}"/>
@endpush
@section('page-name', 'dashboard')

@section('content')
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
            <h1 class="lessons-page-title">{{$title}}</h1>
        </header>
        @if(isset($levels) && count($levels)>0)
            <div class="lessons-grid" id="lessons-grid">

                @foreach($levels as $level)
                @php
                    // Get progress data for this level
                    $isCompleted = $level['isCompleted'] ?? false;
                    $levelProgress = $level['progress'] ?? 0;
                    $completedLessons = $level['completed_lessons'] ?? 0;
                    $totalLessons = $level['total_lessons'] ?? 0;
                @endphp
                    <article class="lesson-card-grid {{ $isCompleted ? 'lesson-card--completed' : '' }}" style="animation-delay: 0.1s;">
                            @if($isCompleted)
                                <div class="lesson-completed-badge">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 7L5.5 10.5L12 3.5" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>{{t('Completed')}}</span>
                                </div>
                            @endif
                            <div class="lesson-icon-container">
                                <img class="lesson-icon-grid" src="{{asset("web_assets/img/levels/".$level["level"].".svg")}}"
                                     alt="{{$level["level"]}}"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid"> المستوى {{$level["level"]}}</h5>
{{--                                <p class="lesson-subtitle-grid">{{$completedLessons}} / {{$totalLessons}} {{t('Lessons Completed')}}</p>--}}
                                <div class="lesson-level-progress-container">
                                    @if($isCompleted)
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--completed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: 100%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--completed" style="width: 100%;"></div>
                                        </div>
                                        <div class="lesson-level-check-icon">
                                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 5L4.5 8.5L11 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="{{$levelProgress}}" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: {{$levelProgress}}%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: {{$levelProgress}}%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage">{{$levelProgress}}%</span>
                                    @endif
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="{{route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$type,'level'=>$level["level"]])}}">
                                    <span>دخول</span>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 7H2M2 7L6 3M2 7L6 11"
                                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </article>
                @endforeach



        </div>
        @endif

    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/lessons-grid.js')}}"></script>
@endpush
