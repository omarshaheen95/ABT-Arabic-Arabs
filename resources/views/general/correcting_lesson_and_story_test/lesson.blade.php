@extends('general.correcting_lesson_and_story_test.layout')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h1 class="title"> {{$lesson->name}}</h1>

                        <nav class="breadcrumb">
{{--                            <a class="breadcrumb-item" href="{{route('lessons', $lesson->level_id)}}"> Lessons </a>--}}
                            <span class="breadcrumb-item active" aria-current="page"> Test </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form action="{{route(getGuard().'.lessons_tests.correcting', $student_test->id)}}" id="term_form" method="post">
                            @csrf
                            <input type="hidden" name="start_at" value="{{\Carbon\Carbon::now()}}">
                            <div class="justify-content-between align-items-center mb-4">

                            </div>

                            <div class="question-list">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach($questions as $question)
                                    @if($question->type == 1)
                                        @include('general.correcting_lesson_and_story_test.questions.true_false')
                                    @elseif($question->type == 2)
                                        @include('general.correcting_lesson_and_story_test.questions.options')
                                    @elseif($question->type == 3)
                                        @include('general.correcting_lesson_and_story_test.questions.match')
                                    @elseif($question->type == 4)
                                        @include('general.correcting_lesson_and_story_test.questions.sort')
                                    @elseif($question->type == 5)
                                        @include('general.correcting_lesson_and_story_test.questions.writing')
                                    @elseif($question->type == 6)
                                        @include('general.correcting_lesson_and_story_test.questions.speaking')
                                    @endif
                                    @php
                                        $counter ++;
                                    @endphp
                                @endforeach
                            </div>


                            <div class="d-flex justify-content-center question-control btn-wizard">
                                <div class="text-center">
                                    <button type="button" class="d-none btn btn-light border" id="previousQuestion">
                                        <span class="txt" style="font-size: 18px">  السابق </span>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-theme d-none endExam correcting_exam" data-bs-toggle="modal" data-bs-target="#correcting-exam-modal" style="font-weight: bold;background-color: #0043b3;">
                                        <span class="txt" style="font-size: 18px">تصحيح وحفظ التعديلات</span>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-theme" id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> التالي  </span></button>
                                </div>
                            </div>


                            <div class="table-footer font-weight-bold">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
                            </div>
                            <!-- Modal -->
                         @include('general.correcting_lesson_and_story_test.correcting_modal')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')


    <script>
        $(document).ready(function () {
            $('#btn-modal-correcting').click(function (e) {
                e.preventDefault();
                $(this).attr('disabled', true);
                $('#correcting-exam-modal').modal('hide');
                $('#term_form').submit();
            })
            $('.audio').click(function () {
                var elem = $(this);
                var data_id = $(this).attr('data-id');
                $('audio').each(function () {
                    this.pause(); // Stop playing
                    this.currentTime = 0; // Reset time
                    console.log('pause');
                });
                console.log('#audio' + data_id);
                $('#audio' + data_id)[0].currentTime = 0;
                $('#audio' + data_id)[0].play();
                console.log('play');

            });
        });

    </script>
@endsection


