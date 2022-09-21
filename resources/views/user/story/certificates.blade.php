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
                        <h1 class="title"> شهادات القصص </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسية </a>
                            <span class="breadcrumb-item active" aria-current="page"> الشهادات </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title">نتائج الاختبارات </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th> القصة</th>
                                    <th> المستوى </th>
                                    <th> الدرجة </th>
                                    <th> الشهادة </th>
                                </tr>
                                @foreach($student_tests as $student_test)
                                    @if($student_test->status == "Pass")
                                <tr>
                                    <td>{{$student_test->story->name}}</td>
                                    <td> {{$student_test->story->grade}} </td>
                                    <td> {{$student_test->total_per}} </td>
                                    <td>
                                        <div class="btn-option">
                                            <a href="{{route('story.certificate', $student_test->id)}}" class="btn btn-review">
                                                <svg id="icon_02" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path id="Vector" d="M18,8.19H15.11a4.306,4.306,0,0,1-4.3-4.3V1a1,1,0,0,0-1-1H5.57A5.277,5.277,0,0,0,0,5.57v8.86A5.277,5.277,0,0,0,5.57,20h7.86A5.277,5.277,0,0,0,19,14.43V9.19A1,1,0,0,0,18,8.19Z" transform="translate(2.5 2)" fill="#830f8b" opacity="0.4"/>
                                                    <path id="Vector-2" data-name="Vector" d="M1.12.195A.654.654,0,0,0,0,.635v3.49a2.726,2.726,0,0,0,2.75,2.67c.95.01,2.27.01,3.4.01a.631.631,0,0,0,.47-1.07C5.18,4.285,2.6,1.675,1.12.195Z" transform="translate(14.68 2.015)" fill="#830f8b"/>
                                                    <path id="Vector-3" data-name="Vector" d="M6.75,1.5h-6A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h6A.755.755,0,0,1,7.5.75.755.755,0,0,1,6.75,1.5Z" transform="translate(6.75 12.25)" fill="#830f8b"/>
                                                    <path id="Vector-4" data-name="Vector" d="M4.75,1.5h-4A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h4A.755.755,0,0,1,5.5.75.755.755,0,0,1,4.75,1.5Z" transform="translate(6.75 16.25)" fill="#830f8b"/>
                                                    <path id="Vector-5" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
                                                </svg>
                                                <span>معاينة</span>
                                            </a>
                                            <a href="{{route('story.certificate.answers', $student_test->id)}}" class="btn btn-answer">
                                                <svg id="icon_02" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path id="Vector" d="M18,8.19H15.11a4.306,4.306,0,0,1-4.3-4.3V1a1,1,0,0,0-1-1H5.57A5.277,5.277,0,0,0,0,5.57v8.86A5.277,5.277,0,0,0,5.57,20h7.86A5.277,5.277,0,0,0,19,14.43V9.19A1,1,0,0,0,18,8.19Z" transform="translate(2.5 2)" fill="#830f8b" opacity="0.4"/>
                                                    <path id="Vector-2" data-name="Vector" d="M1.12.195A.654.654,0,0,0,0,.635v3.49a2.726,2.726,0,0,0,2.75,2.67c.95.01,2.27.01,3.4.01a.631.631,0,0,0,.47-1.07C5.18,4.285,2.6,1.675,1.12.195Z" transform="translate(14.68 2.015)" fill="#830f8b"/>
                                                    <path id="Vector-3" data-name="Vector" d="M6.75,1.5h-6A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h6A.755.755,0,0,1,7.5.75.755.755,0,0,1,6.75,1.5Z" transform="translate(6.75 12.25)" fill="#830f8b"/>
                                                    <path id="Vector-4" data-name="Vector" d="M4.75,1.5h-4A.755.755,0,0,1,0,.75.755.755,0,0,1,.75,0h4A.755.755,0,0,1,5.5.75.755.755,0,0,1,4.75,1.5Z" transform="translate(6.75 16.25)" fill="#830f8b"/>
                                                    <path id="Vector-5" data-name="Vector" d="M0,0H24V24H0Z" fill="none" opacity="0"/>
                                                </svg>
                                                <span>الإجابات</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                    @endif
                                @endforeach

                            </table>
                        </div>

                        <div class="table-footer">
                            {!! $student_tests->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
