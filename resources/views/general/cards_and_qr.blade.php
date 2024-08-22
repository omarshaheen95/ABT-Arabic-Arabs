<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('assets_v1/lib/bootstrap-5.0.2/css/bootstrap.rtl.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets_v1/print/print.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets_v1/print/student_card.css') }}?v={{time()}}" rel="stylesheet" type="text/css" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))}}" />

    <title>{{$title}}</title>
    <style>

    </style>
</head>
<body>
@php
    $count = 0;
@endphp
@foreach($students as $student)
    <div class="page">
        <div class="subpage-w">
            <div class="row">
                @foreach($student as $std)
                    <div class="col-6 p-1">
                        <div class="p-3 student-card">
                            <div class="d-flex flex-column bg-white p-2 h-100" style="border-radius: 8px">
                                <div class="row py-2">
                                    <div class="col-4 text-center">
                                        <div class="image-container-cards">
                                            <img class="logo" src="{{ asset(optional($std->school)->logo) }}"/>
                                        </div>
                                    </div>
                                    <div class="col-8 d-flex flex-column align-items-center mt-1 " style="font-size: 12px">
                                        <div class="fw-bold text-center">{{ optional($std->school)->name }}</div>
                                        <div class="fw-bold">{{t('Student Login')}}</div>
                                    </div>
                                </div>
                                <hr class="my-1" style="border-top: 1px solid #00000040;">
                                <div class="row mt-1 px-1 pb-2" style="position: relative">
                                    <div class="col-7 d-flex flex-column pe-0">
                                        <div class="col-12 s-content"><span class="s-title">{{t('Name')}} : </span>{{ $std->name }}</div>
                                        <div class="col-12 s-content"><span class="s-title">{{t('ID')}} : </span>{{ $std->id_number ?? '-' }}</div>
                                        <div class="col-12 s-content"><span class="s-title"> {{t('Section')}} : </span>{{ $std->section ?? '-' }}</div>
                                        <div class="col-12 s-content"><span class="s-title">{{t('Grade')}} : </span> {{ $std->grade->name }}</div>

                                    </div>
                                    <ul class="ms-3 mt-1">
                                        <li>www.arabic-arabs.com</li>
                                        <li>{{t('Student login')}}</li>
                                        <li>{{t('Email')}}: <span class="username" >{{ $std->email}}</span></li>
                                        <li>{{t('Login')}}</li>
                                    </ul>
                                    <div class="col-5 mt-1 d-flex justify-content-end p-0 pe-1" style="position: absolute;right: 180px">
                                        @if($std->gender == 'Girl')
                                            {!! QrCode::color(255, 0, 194)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); !!}
                                        @elseif($std->gender == 'Boy')
                                            {!! QrCode::color(0, 166, 255)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); !!}
                                        @else
                                            {!! QrCode::color(19,137,68)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); !!}
                                        @endif
                                    </div>

                                </div>

                                <div class="row px-1">
                                    <div class="col-12 d-flex flex-column gap-1 pe-0">
                                        <div class="s-title">

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
@endforeach
</body>

</html>
