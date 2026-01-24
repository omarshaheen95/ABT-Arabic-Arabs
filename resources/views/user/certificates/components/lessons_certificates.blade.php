@if(isset($student_tests) && $student_tests->count()>0)
    <!-- Table View -->
    <div class="certificates-table-container show" id="tableView">
        <div class="certificates-table-wraper">

            <table class="certificates-table">
                <thead>
                <tr>
                    <th>اسم الدرس</th>
                    <th>المستوى</th>
                    <th>الدرجة</th>
                    <th>الحالة</th>
                    <th>الشهادة</th>
                </tr>
                </thead>
                <tbody>
                @foreach($student_tests as $student_test)
                    <tr>
                        <td class="lesson-name">{{$student_test->lesson->name}}</td>
                        <td> {{$student_test->lesson->grade_name}} </td>
                        <td class="score"> {{$student_test->total_per}} </td>

                        <td><span class="status-badge {{$student_test->status=='Pass'?'success':'failed'}}">{{$student_test->status_name}}</span></td>
                        <td>
                            <div class="action-menu-wrapper">
                                <button class="action-btn" aria-label="More actions" onclick="toggleActionMenu(event, this)">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                        <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                    </svg>
                                </button>
                                <div class="action-menu" style="display: none;">
                                    @if($student_test->status == 'Pass')
                                        <button class="action-menu-item" onclick="previewCertificate('{{route('certificate.get-certificate',['type' => $type,'id'=>$student_test->id])}}')">
                                            الشهادة
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button class="action-menu-item" onclick="checkAnswers('{{route('certificates.answers',['type' => $type,'id'=>$student_test->id])}}')">
                                        عرض الإجابات
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach



                </tbody>
            </table>
        </div>

        <!-- Pagination for Table View -->
        @include('user.general.components.pagination', ['paginator' => $student_tests])
    </div>

    <!-- Grid View (Hidden by default) -->
    <div class="certificates-grid-container" id="gridView" style="display: none;">
        @foreach($student_tests as $student_test)
            <div class="certificate-card">
                <div class="card-header">
                    <h3 class="card-title">{{$student_test->lesson->name}}</h3>
                    <span class="status-badge {{$student_test->status=='Pass'?'success':'failed'}}">{{$student_test->status_name}}</span>
                </div>
                <p class="card-level">{{$student_test->lesson->grade_name}}</p>
                <div class="card-content">
                    <div class="card-progress">
                        <div class="progress-linear-container">
                            <div class="progress-linear-bg"></div>
                            <div class="progress-linear-bar" style="width: {{$student_test->total_per}}; background: {{$student_test->total >= 90 ? 'linear-gradient(90deg, #39D670, #B3E69E)' : 'linear-gradient(90deg, #FFC107, #FFE082)'}};"></div>
                            <span class="progress-text">{{$student_test->total_per}}</span>
                        </div>
                    </div>
{{--                    <p class="card-grade">{{$student_test->lesson->grade->name}}</p>--}}
                </div>
                <div class="card-divider"></div>
                <div class="card-actions">
                    <button class="btn-outline glass-button-component" onclick="previewCertificate('{{route('certificate.get-certificate',['type' => $type,'id'=>$student_test->id])}}')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                        </svg>
                        الشهادة
                    </button>
                    <button class="btn-check glass-button-component" onclick="checkAnswers('{{route('certificates.answers',['type' => $type,'id'=>$student_test->id])}}')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="currentColor"/>
                        </svg>
                        عرض الإجابات
                    </button>
                </div>
            </div>
        @endforeach

        <!-- Pagination for Grid View -->
        <div style="grid-column: 1 / -1;">
            @include('user.general.components.pagination', ['paginator' => $student_tests])
        </div>
    </div>
@else
    <h1 style="text-align: center;font-weight: bold">. . . لا يوجد شهادات . . .</h1>
@endif
