@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/lessons-grid.css')}}"/>
@endpush
@section('page-name', 'dashboard')

@section('content')
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
            <h1 class="lessons-page-title">{{$grade->name}}</h1>
            {{--            <p class="lessons-page-subtitle">{{t('Browse all available lessons')}}</p>--}}
        </header>

        <div class="lessons-grid" id="lessons-grid">
            @foreach($lessons as $lesson)
                @php
                    $isCompleted = isset($completedLessonIds) && in_array($lesson->id, $completedLessonIds);
                @endphp
                <article class="lesson-card-grid {{ $isCompleted ? 'lesson-card--completed' : '' }}"
                         style="animation-delay: 0.1s;">
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
                        @if($lesson->lesson_type == 'writing' || $lesson->lesson_type == 'speaking')
                            <img class="lesson-icon-grid" src="{{asset('web_assets/img/'.$lesson->lesson_type . '.jpg')}}" alt="">
                        @elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric']))
                            <img class="lesson-icon-grid" src="{{asset('steps/'.$lesson->lesson_type.'.svg')}}" alt="">
                        @else
                            <img class="lesson-icon-grid" src="{{isset($lesson) ? $lesson->getFirstMediaUrl('imageLessons'):''}}" alt="">
                        @endif
                    </div>
                    <div class="lesson-content-grid">
                        <h5 class="lesson-title-grid">{{$lesson->name}}</h5>
                        <h5 class="lesson-title-grid">{{$lesson->section_type_name ? "الدَّرسُ  $lesson->section_type_name":null }}</h5>
                    </div>
                    <div class="lesson-actions">
                        @if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking')
                            <div class="lesson-actions-row">
                                <button class="lesson-action-btn lesson-action-btn--primary" data-action="learn"
                                        data-url="{{route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'learn'])}}"
                                        class="btn btn-soft-success">

                                    @if($lesson->lesson_type == 'reading')
                                        اقرأ
                                    @elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric']))
                                        تعلم
                                    @else
                                        استمع
                                    @endif
                                </button>
                                <button class="lesson-action-btn lesson-action-btn--secondary" data-action="training"
                                        data-url="{{route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'training'])}}">
                                    <span> تدرب</span>
                                </button>
                            </div>
                        @endif

                            <button class="lesson-action-btn lesson-action-btn--accent" data-action="test"
                                    data-url="{{route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'test'])}}">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 1L8.5 5H12.5L9.5 7.5L10.5 11.5L7 9L3.5 11.5L4.5 7.5L1.5 5H5.5L7 1Z"
                                          stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span>
                                     @if($lesson->lesson_type == 'writing')
                                        اكتب
                                    @elseif($lesson->lesson_type == 'speaking')
                                        تحدث
                                    @else
                                        اختبر نفسك
                                    @endif


                                    @if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking')
                                        @if($lesson->student_tested)- مكتمل   @endif
                                    @else
                                        @if($lesson->student_tested_task)- مكتمل   @endif
                                    @endif
                                </span>
                            </button>
                    </div>
                </article>
            @endforeach

        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/lessons-grid.js')}}"></script>
@endpush
