@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/lessons-grid.css')}}"/>
@endpush
@section('page-name', 'dashboard')

@section('content')
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
{{--            <h1 class="lessons-page-title">{{$level->name}}</h1>--}}
        </header>
        @if(isset($grades) && count($grades)>0)
            @foreach($grades as $grade)
                @php
                    // Get the enriched grade data from $allGrades with skills_progress
                    $enrichedGrade = $allGrades->firstWhere('id', $grade->id);
                @endphp
                <header class="lessons-page-header">
                                <h2 class="lessons-page-title">الصف {{$grade->grade_name}}</h2>
                </header>

        <div class="lessons-grid" id="lessons-grid">

                @foreach($grade->grade_skills as $data)
                @php
                    // Get skill-specific progress data from enriched grade
                    $skillProgress = $enrichedGrade->skills_progress[$data['skill']] ?? ['progress' => 0, 'isCompleted' => false];
                    $isCompleted = $skillProgress['isCompleted'];
                    $gradeProgress = $skillProgress['progress'];
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
                                <img class="lesson-icon-grid" src="{{asset('steps/'.$data['skill'].'.svg')}}"
                                     alt="{{$data['skill']}}"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid">{{$data['title']}}</h5>
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
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="{{$gradeProgress}}" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: {{$gradeProgress}}%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: {{$gradeProgress}}%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage">{{$gradeProgress}}%</span>
                                    @endif
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="{{route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$data['skill']])}}">
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
            @endforeach
        @endif

        @if(isset($alternate_grades) && count($alternate_grades)>0)
            @foreach($alternate_grades as $grade)
                @php
                    // Get the enriched grade data from $allGrades with skills_progress
                    $enrichedGrade = $allGrades->firstWhere('id', $grade->id);
                @endphp
                <header class="lessons-page-header">
                                <h2 class="lessons-page-title">الصف {{$grade->grade_name}}</h2>
                </header>
        <div class="lessons-grid" id="lessons-grid">

                @foreach($grade->grade_skills as $data)
                @php
                    // Get skill-specific progress data from enriched grade
                    $skillProgress = $enrichedGrade->skills_progress[$data['skill']] ?? ['progress' => 0, 'isCompleted' => false];
                    $isCompleted = $skillProgress['isCompleted'];
                    $gradeProgress = $skillProgress['progress'];
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
                                <img class="lesson-icon-grid" src="{{asset('steps/'.$data['skill'].'.svg')}}"
                                     alt="{{$data['skill']}}"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid">{{$data['title']}}</h5>
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
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="{{$gradeProgress}}" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: {{$gradeProgress}}%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: {{$gradeProgress}}%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage">{{$gradeProgress}}%</span>
                                    @endif
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="{{route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$data['skill']])}}">
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
            @endforeach
        @endif
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/lessons-grid.js')}}"></script>
@endpush
