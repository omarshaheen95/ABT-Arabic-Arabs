@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/stories-grid.css')}}" />
@endpush
@section('page-name', 'library')

@section('content')
    <div class="library-grid-container">
        <header class="page-header">
            <h1 class="page-title">{{t('My Library')}}</h1>
{{--            <p class="page-subtitle">{{t('Browse all available stories')}}</p>--}}
        </header>

        <div class="courses-grid" id="courses-grid">
            @foreach($stories as $story)
                @php
                    $isCompleted = isset($completedStoryIds) && in_array($story->id, $completedStoryIds);
                @endphp
                <article class="course-card-grid {{ $isCompleted ? 'story-card--completed' : '' }}" style="animation-delay: 0.1s;">
                    @if($isCompleted)
                        <div class="story-completed-badge">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 7L5.5 10.5L12 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{t('Completed')}}</span>
                        </div>
                    @endif
                    <div class="course-icon-container">
                        <img class="course-icon-grid" src="{{$story->image}}" alt="{{$story->name}}" />
                    </div>
                    <div class="course-content-grid">
{{--                        <h5 class="course-title-grid">{{$story->getTranslation('name', 'ar')}}</h5>--}}
                        <h5 class="course-title-grid">{{$story->name}}</h5>
                    </div>
                    <div class="story-actions">
                        <button class="story-action-btn story-action-btn--primary" data-action="watch" data-url="{{route('story.story-index',['id'=>$story->id,'key'=>'watch'])}}">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 3.5L10.5 7L5 10.5V3.5Z" fill="currentColor"/>
                            </svg>
                            <span>{{t('Listen & Watch')}}</span>
                        </button>
                        <div class="story-actions-row">
                            <button class="story-action-btn story-action-btn--secondary" data-action="read" data-url="{{route('story.story-index',['id'=>$story->id,'key'=>'read'])}}">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 2H11C11.55 2 12 2.45 12 3V11C12 11.55 11.55 12 11 12H3C2.45 12 2 11.55 2 11V3C2 2.45 2.45 2 3 2Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <line x1="4.5" y1="5" x2="9.5" y2="5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <line x1="4.5" y1="7.5" x2="9.5" y2="7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <line x1="4.5" y1="10" x2="7.5" y2="10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                <span>{{t('Read Story')}}</span>
                            </button>
                            <button class="story-action-btn story-action-btn--accent" data-action="assess" data-url="{{route('story.story-index',['id'=>$story->id,'key'=>'test'])}}">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 1L8.5 5H12.5L9.5 7.5L10.5 11.5L7 9L3.5 11.5L4.5 7.5L1.5 5H5.5L7 1Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span>{{t('Assess Yourself')}}</span>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach

        </div>
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/library-grid.js')}}"></script>
@endpush
