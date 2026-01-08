@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/test-result.css')}}" />
@endpush

@section('page-name', 'quiz-result')

@section('content')
    <div class="quiz-result-container">
        <div class="quiz-result-content-wraper">
            <div class="quiz-result-content-container">
                <!-- Page title -->
                <div class="result-header">
                    <h1 class="result-title-ar" style="margin-bottom: 20px;!important;">{{$lesson->name}}</h1>
                    <h1 class="result-title-ar">نتيجة الاختبار</h1>
                    <p class="result-title-en">Test result</p>
                </div>

                <!-- Celebration illustration -->
                <div class="result-illustration">
                    <img src="{{asset('user_assets/images/illustrations/quiz-result.svg')}}" alt="Quiz result celebration" class="celebration-image" />
                </div>

                <!-- Result cards -->
                <div class="result-cards">
                    <!-- Total XP card -->
                    <div class="result-card xp-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="{{asset('user_assets/images/illustrations/diamond.svg')}}" alt="Diamond icon" />
                            </div>
                            <div class="card-value">{{$xpEarned}}</div>
                        </div>
                        <div class="card-label">Total xp</div>
                    </div>

                    <!-- Timing card -->
                    <div class="result-card timing-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="{{asset('user_assets/images/illustrations/clock.svg')}}" alt="Clock icon" />
                            </div>
                            <div class="card-value">{{$timingMinutes}} min</div>
                        </div>
                        <div class="card-label">Timing</div>
                    </div>

                    <!-- Score card -->
                    <div class="result-card score-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="{{asset('user_assets/images/illustrations/reward.svg')}}" alt="Reward icon" />
                            </div>
                            <div class="card-value">{{$percentage}}%</div>
                        </div>
                        <div class="card-label">Score</div>
                    </div>
                </div>

                <!-- Congratulations message -->
                <div class="congratulations-section">
                    @if($total >= $level->level_mark)
                        <p class="congrats-text-ar">
                            تهانينا، نتيجتك في هذا التقييم هي {{$percentage}}% انت مؤهل للحصول على الشهادة ولعب اللعبة
                        </p>
                        <p class="congrats-text-en">
                            Congratulations, your score in this assessment is {{$percentage}}%. You are eligible to receive the certificate and play the game
                        </p>
                    @else
                        <p class="congrats-text-ar">
                            درجتك في هذا التقييم هي {{$percentage}}%. تحتاج إلى {{$level->level_mark}}% للنجاح. يرجى المحاولة مرة أخرى.
                        </p>
                        <p class="congrats-text-en">
                            Your score in this assessment is {{$percentage}}%. You need {{$level->level_mark}}% to pass. Please try again.
                        </p>
                    @endif
                </div>

                <!-- Action buttons -->
                <div class="result-actions">
                    @if($total >= $level->level_mark)
                        <button class="result-btn certificate-btn" data-url="{{$certificate_url}}">Get Certificate</button>
                    @endif
                    <button class="result-btn next-lesson-btn" data-url="{{$next_lesson_url}}">Next Lesson</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
    </script>
    <script src="{{asset('user_assets/js/pages/test-result.js')}}"></script>

@endpush
