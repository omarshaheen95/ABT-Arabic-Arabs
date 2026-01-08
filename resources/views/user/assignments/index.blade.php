@extends('user.layout')
@push('style')
        <link rel="stylesheet" href="{{asset('user_assets/css/pages/homework.css')}}" />
@endpush
@section('page-name', 'homework')

@section('content')
    <div class="homework-container">
        <!-- Header Section -->
        <header class="homework-header">
            <div class="header-text">
                <h1 class="homework-title">
                    {{t('Assigned homework')}}
                    @if($type=='lesson' && ($user_data['uncompletedLessons'] ?? 0) > 0)
                        <span class="assignment-badge">
                            <span class="badge-count">{{$user_data['uncompletedLessons']}}</span>
                        </span>
                    @elseif($type=='story' && ($user_data['uncompletedStories'] ?? 0) > 0)
                        <span class="assignment-badge">
                            <span class="badge-count">{{$user_data['uncompletedStories']}}</span>
                        </span>
                    @endif
                </h1>
                <p class="homework-subtitle">
                    @if($type=='lesson')
                        {{t('Lessons assignments')}}
                        @if(($user_data['uncompletedLessons'] ?? 0) > 0)
                            <span style="color: #EF4444; font-weight: 600;">
                                ({{$user_data['uncompletedLessons']}} {{t('uncompleted')}})
                            </span>
                        @else
                            <span style="color: #10B981; font-weight: 600;">
                                ({{t('All completed')}})
                            </span>
                        @endif
                    @else
                        {{t('Stories assignments')}}
                        @if(($user_data['uncompletedStories'] ?? 0) > 0)
                            <span style="color: #EF4444; font-weight: 600;">
                                ({{$user_data['uncompletedStories']}} {{t('uncompleted')}})
                            </span>
                        @else
                            <span style="color: #10B981; font-weight: 600;">
                                ({{t('All completed')}})
                            </span>
                        @endif
                    @endif
                </p>
            </div>
            <div class="header-actions">
                <button class="view-toggle-btn glass-button-component" id="viewToggleBtn" data-view="grid" aria-label="Switch to table view">
                    <img class="grid-icon" src="{{asset('user_assets/images/icons/grid.svg')}}" alt="Grid view" width="24" height="24">
                    <img class="table-icon" src="{{asset('user_assets/images/icons/list.svg')}}" alt="Table view" width="24" height="24" style="display: none;">
                </button>
                <select class="filter-dropdown" id="homeworkFilter">
                    <option value="{{route('lesson.assignments')}}" @if($type=='lesson') selected @endif>
                        {{t('Lessons assignments')}} @if(($user_data['uncompletedLessons'] ?? 0) > 0)({{$user_data['uncompletedLessons']}})@endif
                    </option>
                    <option value="{{route('story.assignments')}}" @if($type=='story') selected @endif>
                        {{t('Stories assignment')}} @if(($user_data['uncompletedStories'] ?? 0) > 0)({{$user_data['uncompletedStories']}})@endif
                    </option>
                </select>
            </div>
        </header>

        @if($type=='lesson' && $student_assignments->count()>0)
            @include('user.assignments.lessons_assignments')
        @elseif($type=='story'&& $student_assignments->count()>0)
            @include('user.assignments.stories_assignments')
        @else
            <h1 style="text-align: center;font-weight: bold">. . . {{t('No Assignment Found')}} . . .</h1>
        @endif
    </div>

@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/homework.js')}}"></script>
@endpush
