{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('user.layout.container_v2')
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> الشهادات </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="{{route('certificates')}}"> الشهادات </a>
                            <span class="breadcrumb-item active" aria-current="page"> إجابات الأسئلة </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title">إجابات الأسئلة</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                @if($student_test->lesson->lesson_type == 'writing' || $student_test->lesson->lesson_type == 'speaking')
                                    <tr>
                                        <td style="font-weight: bold">#</td>
                                        <td style="font-weight: bold">السؤال</td>
                                        <td style="font-weight: bold">إجابة الطالب</td>
                                    </tr>
                                    @if($student_test->lesson->lesson_type == 'writing')
                                        @foreach($student_test->writingResults as $key => $writingResult)
                                            <tr class="text-center">
                                                <td>{{$key + 1}}</td>
                                                <td>{{ $writingResult->question->content }}</td>
                                                <td>
                                                    {{$writingResult->result}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach($student_test->speakingResults as $key => $speakingResult)
                                            <tr class="text-center">
                                                <td>{{$key + 1}}</td>
                                                <td>{{ $speakingResult->question->content }}</td>
                                                <td>
                                                    <audio src="{{asset($speakingResult->attachment)}}" controls></audio>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @else
                                    <tr>
                                        <td style="font-weight: bold">#</td>
                                        <td style="font-weight: bold">السؤال</td>
                                        <td style="font-weight: bold">تصنيف السؤال</td>
                                        <td style="font-weight: bold">إجابة الطالب</td>

                                        <td style="font-weight: bold">الإجابة الصحيحة</td>
                                    </tr>
                                    @foreach($questions as $key => $question)
                                        <tr class="text-center">
                                            @php
                                                $data = $question->studentAnswer($student_test->id);
                                            @endphp
                                            <td>{{$key + 1}}</td>
                                            <td>{{$question->content}}</td>
                                            <td>{{$question->type_eng_name}}</td>
                                            <td class="{{isset($data['class']) ? $data['class']:''}}">
                                                {!! isset($data['student_answer']) ? $data['student_answer']:'' !!}
                                            </td>
                                            <td>
                                                {!! isset($data['question_answer']) ? $data['question_answer']:'' !!}
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
