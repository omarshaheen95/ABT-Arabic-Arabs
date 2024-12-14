@extends('user.layout.container_v2')

@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> {{$title}} </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> {{$title}} </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td style="font-weight: bold">الدرس</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">تعيين اختبار</td>
                                    <td style="font-weight: bold">موعد التسليم</td>
                                </tr>
                                @foreach($student_assignments as $student_assignment)
                                    <tr>
                                        <td>
<<<<<<< HEAD

=======
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
                                            <a href="{{route('lesson', [$student_assignment->lesson_id, 'learn'])}}">{{$student_assignment->lesson->name}}</a>
                                        </td>
                                        <td>الصف {{$student_assignment->lesson->grade_name}}</td>

                                        <td>@if($student_assignment->done_test_assignment)
                                                مكتمل
                                            @elseif($student_assignment->test_assignment != 0)
                                                <a href="{{route('lesson', [$student_assignment->lesson_id, 'test'])}}">الذهاب للاختبار</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {{$student_assignment->deadline}}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="table-footer">
                            {!! $student_assignments->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
