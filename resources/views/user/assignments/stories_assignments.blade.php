<!-- Grid View (Default) -->
<div class="homework-grid-wrapper show" id="gridView">
    <div class="homework-grid-container">

        @foreach($student_assignments as $student_assignment)
            <!-- Assignment -->
            <div class="assignment-card" data-assignment-id="{{$student_assignment->id}}">
                <div class="card-header">
                    <div class="card-header-text">
                        <h3 class="card-title">{{$student_assignment->story->name}}</h3>
                        <p class="card-level">{{t('Grade')}} {{$student_assignment->story->grade}}</p>
                    </div>
                    @if((($student_assignment->test_assignment && $student_assignment->done_test_assignment) &&$student_assignment->completed) ||  $student_assignment->completed)
                        <span class="status-badge completed">{{t('Completed')}}</span>
                    @else
                        <div class="assignment-timer" data-deadline="{{$student_assignment->deadline ? $student_assignment->deadline->toIso8601String() : ''}}">
                            <span class="timer-text">--:--:--</span>
                        </div>
                    @endif
                </div>
                <div class="card-divider"></div>
                <div class="card-actions">
                    <button class="btn-outline glass-button-component" onclick="goToTest('{{route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'watch'])}}')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                        </svg>
                        {{t('Read the story')}}
                    </button>
                    @if($student_assignment->test_assignment)
                        <button class="btn-outline glass-button-component {{ $student_assignment->done_test_assignment ? 'completed-btn' : '' }}"
                                onclick="goToTest('{{route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'test'])}}')"
                                {{ $student_assignment->done_test_assignment ? 'disabled' : '' }}>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                            </svg>
                            {{ $student_assignment->done_test_assignment ? t('Test is completed') : t('Go to test') }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
    <!-- Pagination for Grid View -->
    <div style="grid-column: 1 / -1;">
        @include('user.general.components.pagination', ['paginator' => $student_assignments])
    </div>
</div>

<!-- Table View (Hidden by default) -->
<div class="homework-table-container" id="tableView" style="display: none;">
    <div class="homework-table-wraper">
        <table class="homework-table">
            <thead>
            <tr>
                <th>{{t('Story Name')}}</th>
                <th>{{t('Grade')}}</th>
                <th>{{t('Status')}}</th>
                <th>{{t('Actions')}}</th>
            </tr>
            </thead>
            <tbody id="homeworkTableBody">
            @foreach($student_assignments as $student_assignment)
                <!-- Assignment -->
                <tr data-assignment-id="tr-{{$student_assignment->id}}">
                    <td class="lesson-name">{{$student_assignment->story->name}}</td>
                    <td>{{$student_assignment->story->grade}}</td>
                    <td>
                        @if((($student_assignment->test_assignment && $student_assignment->done_test_assignment) && $student_assignment->completed) ||  $student_assignment->completed)
                            <span class="status-badge completed">{{t('Completed')}}</span>
                        @else
                            <div class="assignment-timer" data-deadline="{{$student_assignment->deadline ? $student_assignment->deadline->toIso8601String() : ''}}">
                                <span class="timer-text">--:--:--</span>
                            </div>
                        @endif

                    </td>
                    <td>
                        <div class="action-menu-wrapper">
                            <button class="action-btn" aria-label="More actions" onclick="toggleActionMenu(event, 1)">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                    <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                    <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                </svg>
                            </button>
                            <div class="action-menu" id="actionMenu-1">
                                <button class="action-menu-item" onclick="goToTasks('{{route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'watch'])}}')">
                                    {{t('Read the story')}}
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z" fill="currentColor"/>
                                    </svg>
                                </button>

                                @if($student_assignment->test_assignment)
                                    <button class="action-menu-item {{ $student_assignment->done_test_assignment ? 'completed-btn' : '' }}"
                                            onclick="goToTest('{{route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'test'])}}')"
                                            {{ $student_assignment->done_test_assignment ? 'disabled' : '' }}>
                                        {{ $student_assignment->done_test_assignment ? t('Test is completed') : t('Go to test') }}
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination for Grid View -->
    <div style="grid-column: 1 / -1;">
        @include('user.general.components.pagination', ['paginator' => $student_assignments])
    </div>
</div>
