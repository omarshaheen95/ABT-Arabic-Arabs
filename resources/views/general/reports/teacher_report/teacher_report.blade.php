{{--Teacher Report--}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('assets_v1/web_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('report_assets/css/print.css') }}?v={{time()}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('report_assets/css/report.css') }}?v={{time()}}" rel="stylesheet" type="text/css"/>

    @if(isset(cached()->logo_min))
        <link rel="shortcut icon" href="{{ asset(cached()->logo_min) }}"/>
    @endif

    <style>
        .teacher-info-card {
            padding: 14px 20px;
            border: 1px solid #D3D3D3;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #f9f9f9;
            border-right: 5px solid #138944;
        }
        .teacher-info-card h4 {
            margin: 0 0 8px;
            color: #138944;
            font-size: 20px;
            font-weight: 700;
        }
        .teacher-info-card .info-row {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .teacher-info-card .info-item {
            font-size: 13px;
            color: #444;
        }
        .teacher-info-card .info-item span {
            font-weight: 600;
            color: #222;
        }
        .stat-group-header td {
            background-color: #138944 !important;
            color: #fff !important;
            font-weight: 600;
            font-size: 13px;
            text-align: center;
        }
        .badge-pass {
            background: #D4F4E2;
            color: #1a6b3b;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-fail {
            background: #FBDDDD;
            color: #8b2222;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-pending {
            background: #FCF8D7;
            color: #7a6500;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-corrected {
            background: #D4F4E2;
            color: #1a6b3b;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-returned {
            background: #FBDDDD;
            color: #8b2222;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>

</head>
<body dir="rtl">

{{-- ===== صفحة الغلاف ===== --}}
<div class="page">
    <div class="subpage-w">
        <div class="row justify-content-center align-items-center">
            <div class="col-8 justify-content-center text-center">
                <img src="{{!settingCache('logo')? asset('logo.svg?v=1'):asset(settingCache('logo'))}}" width="100%"
                     alt="">
            </div>
        </div>

        <div class="row text-center justify-content-center mt-5">
            <div class="col-6">
                <h3 class="main-color">تقرير إحصائيات المعلمين</h3>
            </div>
            <div class="col-6">
                <h3 class="main-color">Teacher Statistics Report</h3>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <img src="{{asset('report_assets/images/attainment_report_1_arabs_page.svg')}}"
                     style="max-height: 300px; width: 50%" alt="">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                @if($school)
                    <h3 class="main-color my-2">{{$school->name}}</h3>
                @endif
                @if($year)
                    <h4 class="main-color my-2">{{$year->name}}</h4>
                @endif
                @if($start_date && $end_date)
                    <h5>{{t('From')}} : {{$start_date}} &nbsp;&nbsp; {{t('To')}} : {{$end_date}}</h5>
                @endif
                <h5>{{t('Release Date')}} : {{now()->format('Y-m-d')}}</h5>
                <h5>www.abt-assessments.com</h5>
                <h5>support@abt-assessments.com</h5>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <img src="{{asset('report_assets/images/footer-logos.svg')}}?v=1" width="100%" alt="">
            </div>
        </div>
    </div>
</div>

{{-- ===== صفحة لكل معلم ===== --}}
@foreach($teachers as $teacher)
<div class="page">
    <div class="subpage-w">

        {{-- عنوان الصفحة --}}
        <div class="row text-center justify-content-center mb-3">
            <div class="col-11">
                <h5 class="section-title">{{t('Teacher Statistics')}}</h5>
            </div>
        </div>

        {{-- بيانات المعلم الشخصية --}}
        <div class="row justify-content-center mb-3">
            <div class="col-11">
                <div class="teacher-info-card">
                    <h4>{{ $teacher->name }}</h4>
                    <div class="info-row">
                        @if($teacher->email)
                            <div class="info-item">{{t('Email')}} : <span>{{ $teacher->email }}</span></div>
                        @endif
                        <div class="info-item">{{t('Registration Date')}} : <span>{{ $teacher->created_at ? $teacher->created_at->format('Y-m-d') : '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول الإحصائيات --}}
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0 font-14">
                        <tbody>

                        {{-- عدد الطلاب --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Students')}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="3">{{t('Total Students')}}</td>
                            <td><strong>{{ $teacher->total_students }}</strong></td>
                        </tr>

                        {{-- اختبارات الدروس --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Lesson Assessments')}} </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2" class="sub-td">{{t('Total')}}</td>
                            <td class="sub-td"><span class="badge-pass">{{t('Pass')}}</span></td>
                            <td class="sub-td"><span class="badge-fail">{{t('Fail')}}</span></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><strong>{{ $teacher->total_lesson_tests }}</strong></td>
                            <td><span class="badge-pass">{{ $teacher->pass_lesson_tests }}</span></td>
                            <td><span class="badge-fail">{{ $teacher->fail_lesson_tests }}</span></td>
                        </tr>

                        {{-- اختبارات القصص --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Story Assessments')}} </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2" class="sub-td">{{t('Total')}}</td>
                            <td class="sub-td"><span class="badge-pass">{{t('Pass')}}</span></td>
                            <td class="sub-td"><span class="badge-fail">{{t('Fail')}}</span></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><strong>{{ $teacher->total_story_tests }}</strong></td>
                            <td><span class="badge-pass">{{ $teacher->pass_story_tests }}</span></td>
                            <td><span class="badge-fail">{{ $teacher->fail_story_tests }}</span></td>
                        </tr>

                        {{-- مهام الدروس والقصص --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Assignments')}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2">{{t('Lesson Assignments')}} </td>
                            <td colspan="2"><strong>{{ $teacher->total_lesson_assignments }}</strong></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2">{{t('Story Assignments')}} </td>
                            <td colspan="2"><strong>{{ $teacher->total_story_assignments }}</strong></td>
                        </tr>

                        {{-- واجبات الطلاب للدروس --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Student Homework')}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2">{{t('Lesson Homework Total')}} </td>
                            <td><strong>{{ $teacher->total_lesson_hw }}</strong></td>
                            <td><span class="badge-corrected">{{t('Done')}}: {{ $teacher->completed_lesson_hw }}</span></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2">{{t('Story Homework Total')}} </td>
                            <td><strong>{{ $teacher->total_story_hw }}</strong></td>
                            <td><span class="badge-corrected">{{t('Done')}}: {{ $teacher->completed_story_hw }}</span></td>
                        </tr>

                        {{-- تسجيلات القصص --}}
                        <tr class="text-center stat-group-header">
                            <td colspan="4">{{t('Story Recordings')}} </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">{{t('Total')}}</td>
                            <td class="sub-td"><span class="badge-corrected">{{t('Corrected')}}</span></td>
                            <td class="sub-td"><span class="badge-pending">{{t('Pending')}}</span></td>
                            <td class="sub-td"><span class="badge-returned">{{t('Returned')}}</span></td>
                        </tr>
                        <tr class="text-center">
                            <td><strong>{{ $teacher->total_story_records }}</strong></td>
                            <td><span class="badge-corrected">{{ $teacher->corrected_story_records }}</span></td>
                            <td><span class="badge-pending">{{ $teacher->pending_story_records }}</span></td>
                            <td><span class="badge-returned">{{ $teacher->returned_story_records }}</span></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    {{-- Footer --}}
    <div class="footer text-center mt-3">
        <small>www.abt-assessments.com &nbsp;|&nbsp; support@abt-assessments.com</small>
    </div>
</div>
@endforeach


<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // window.print();
    });
</script>

</body>
