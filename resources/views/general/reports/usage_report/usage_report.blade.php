{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Usage Report</title>
    <meta name="description" content="{{ isset(cached()->name) ? cached()->name:'Non-Arabs student report' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="{{ asset('assets_v1/web_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('report_assets/css/print.css') }}?v={{time()}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('report_assets/css/report.css') }}?v={{time()}}" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('report_assets/js/print/new_highcharts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('report_assets/js/print/highcharts-more.js') }}" type="text/javascript"></script>
    <script src="{{ asset('report_assets/js/print/rounded-corners.js') }}" type="text/javascript"></script>

    <!-- Favicon -->
    @if(isset(cached()->logo_min))
        <link rel="shortcut icon" href="{{ asset(cached()->logo_min) }}"/>
    @endif

</head>
<body>


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
                <h3 class="main-color">Usage Report</h3>
            </div>
            <div class="col-6">
                <h3 class="main-color">تقرير الاستخدام</h3>
            </div>


        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <img src="{{asset('report_assets/images/attainment_report_1_arabs_page.svg')}}"
                     style="max-height: 350px; width: 50%" alt="">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h4 class="main-color my-2">{{$year->name}} </h4>
                @if($start_date && $end_date)
                    <h5>{{t('From')}} : {{$start_date}} {{t('To')}} : {{$end_date}}</h5>
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

<div class="page">
    <div class="subpage-w">
        <div class="row text-center justify-content-center">
            <div class="col-11">
                @if(count($schools) == 1)
                    <h5 class="section-title">{{t('Usage report - School overall') }}</h5>
                @else
                    <h5 class="section-title">{{t('Usage report - Schools overall') }}</h5>
                @endif
            </div>
        </div>
        <br>
        <br>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <tbody>
                        <tr class="text-center">
                            <td class="sub-td" width="45%">School Name</td>
                            <td>
                                @foreach($schools as $school)
                                    {{$school->name}} @if(!$loop->last && !$loop->first)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Total Student</td>
                            <td>{{$data['total_students']}}</td>
                        </tr>
                        @if(getGuard() != 'teacher')
                            <tr class="text-center">
                                <td class="sub-td">Total Teachers</td>
                                <td>{{$data['total_teachers']}}</td>
                            </tr>
                            <tr class="text-center">
                                <td class="sub-td">High teacher performance</td>
                                <td>{{$data['top_teacher'] ? $data['top_teacher']->name:null}}</td>
                            </tr>
                        @endif
                        <tr class="text-center">
                            <td class="sub-td">High student performance</td>
                            <td>{{$data['top_student'] ? $data['top_student']->name .' - '. $data['top_student']->grade_name .' - '. $data['top_student']->section:null}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Lessons assessments</td>
                            <td>{{$data['total_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Above Lessons assessments</td>
                            <td>{{$data['total_pass_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Below Lessons assessments</td>
                            <td>{{$data['total_fail_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Stories assessments</td>
                            <td>{{$data['total_story_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Above Stories assessments</td>
                            <td>{{$data['total_pass_story_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Below Stories assessments</td>
                            <td>{{$data['total_fail_story_tests']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Stories Records</td>
                            <td>{{$data['stories_recorde']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Marked Stories Records</td>
                            <td>{{$data['corrected_stories_recorde']}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Unmarked Stories Records</td>
                            <td>{{$data['uncorrected_stories_recorde']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page">
    <div class="subpage-w">
        <div class="row text-center justify-content-center">
            <div class="col-11">
                @if(count($schools) == 1)
                    <h5 class="section-title">{{t('Usage report - School overall') }}</h5>
                @else
                    <h5 class="section-title">{{t('Usage report - Schools overall') }}</h5>
                @endif
            </div>
        </div>
        <br>
        <br>
        <div class="row justify-content-center">
            <div class="col-11">
                <div id="general_tracker"></div>
            </div>
        </div>
    </div>
</div>
@if(getGuard() != 'teacher')
    @foreach($teachers as $key => $teachers_chunk)
        <div class="page">
            <div class="subpage-w">
                <div class="row text-center justify-content-center">
                    <div class="col-11">
                        <h5 class="section-title">{{t('Lessons Tests') }}</h5>
                    </div>
                </div>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div id="teacher_tests_{{$key}}" style="height: 650px"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($teachers as $key => $teachers_chunk)
        <div class="page">
            <div class="subpage-w">
                <div class="row text-center justify-content-center">
                    <div class="col-11">
                        <h5 class="section-title">{{t('Stories Tests') }}</h5>
                    </div>
                </div>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div id="teacher_stories_tests_{{$key}}" style="height: 650px"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endif
@foreach($grades_data as $key => $grade_data)
    @if($key >= 1 && $key <= 15)
        @if($grade_data['total_students'] != 0)
            <div class="page">
                <div class="subpage-w">
                    <div class="row text-center justify-content-center">
                        @if($key != 13 && is_int($key))
                            <div class="col-xs-12">
                                <h5 class="section-title">Grade {{$key}} / Year {{$key + 1}}</h5>
                            </div>
                        @elseif($key == 13)
                            <div class="col-xs-12">
                                <h5 class="section-title">Grade KG / Year 1</h5>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="col-11">
                                <div class="table-container ">
                                    <table class="table m-0 font-14">
                                        <tbody>
                                        <tr>
                                            <td class="sub-td">Total Student</td>
                                            <td>{{$grade_data['total_students']}}</td>
                                        </tr>
                                        @if(getGuard() != 'teacher')
                                            <tr>
                                                <td class="sub-td">Total Teachers</td>
                                                <td>{{$grade_data['total_teachers']}}</td>
                                            </tr>
                                            <tr>
                                                <td class="sub-td">High teacher performance</td>
                                                <td>{{$grade_data['top_teacher'] ? $grade_data['top_teacher']->name:null}}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="sub-td">Highest student performance on lesson</td>
                                            <td>{{$grade_data['top_student_lesson'] ? $grade_data['top_student_lesson']->name .' - '. $grade_data['top_student_lesson']->grade_name .' - '. $grade_data['top_student_lesson']->section:null}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Highest student performance on stories</td>
                                            <td>{{$grade_data['top_student_story'] ? $grade_data['top_student_story']->name .' - '. $grade_data['top_student_story']->grade_name .' - '. $grade_data['top_student_story']->section:null}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Submitted Lessons assessments</td>
                                            <td>{{$grade_data['total_tests']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Lessons Above assessments</td>
                                            <td>{{$grade_data['total_pass_tests']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Lessons Below assessments</td>
                                            <td>{{$grade_data['total_fail_tests']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Submitted Stories assessments</td>
                                            <td>{{$grade_data['total_story_tests']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Stories Above assessments</td>
                                            <td>{{$grade_data['total_story_pass_tests']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Stories Below assessments</td>
                                            <td>{{$grade_data['total_story_fail_tests']}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div id="grade_tracker_{{$key}}"
                                 style="height: 330px"></div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div id="grade_tracker_{{$key}}"></div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endforeach


<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!--begin:: Global Optional Vendors -->
<!--begin::Global Theme Bundle(used by all pages) -->
<!-- begin::Global Config(global config for global JS sciprts) -->


<script type="text/javascript">

    $(document).ready(function () {

        //window.print();
    });
</script>
<!-- End -->
<script type='text/javascript'>//<![CDATA[
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#374afb",
                "light": "#ffffff",
                "dark": "#282a3c",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
    var colors = ['#2525ef', '#0fee00', '#f16e31', '#c8018b',];
    Highcharts.setOptions({
        colors: ['#ec4102', '#47e51c', '#24CBE5', '#FFF263', '#FF9655', '#FFF263', '#6AF9C4']
    });

    Highcharts.chart("general_tracker", {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            color: "#F00",
            text: null,
            style: {
                font: 'bold 15px "Trebuchet MS", Verdana, sans-serif',
                color: '#000',
            }
        },
        legend: {
            align: 'center',
            itemStyle: {
                fontSize: '14px',
                color: '#000',
                align: 'center',
            },
        },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<br>{point.percentage:.1f} %',
                    style: {
                        font: 'bold 11px "Trebuchet MS", Verdana, sans-serif',
                        color: "#000",
                        textOutline: 0
                    }
                },
                showInLegend: true,
            },
            series: {
                animation: false
            }
        },


        series: [{
            type: 'pie',
            name: 'Rate',
            data: [
                ['Learn', {{ $data['learn'] }}],
                ['Practise', {{ $data['practise'] }}],
                ['Assess your self', {{ $data['test'] }}],
            ]
        }]
    });
    @if(getGuard() != 'teacher')
    @foreach($teachers as $key => $teachers_chunk)
    Highcharts.chart('teacher_tests_{{$key}}', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                @foreach($teachers_chunk as $teacher)
                    "{{trim($teacher->name)}}",
                @endforeach
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                pointWidth: 30,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}',
                    // rotation: -90,
                    x: 10,
                    color: '#000',
                    style: {
                        fontSize: '10px',

                    }

                }
            }
        },
        series: [{
            name: 'Below',
            data: [@foreach($teachers_chunk as $teacher)
                {{$teacher->failed_tests_statictics}},
                @endforeach]
        }, {
            name: 'Above',
            data: [
                @foreach($teachers_chunk as $teacher)
                    {{$teacher->passed_tests_statictics}},
                @endforeach
            ]
        }]
    });

    Highcharts.chart('teacher_stories_tests_{{$key}}', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                @foreach($teachers_chunk as $teacher)
                    "{{trim($teacher->name)}}",
                @endforeach
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                pointWidth: 30,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}',
                    // rotation: -90,
                    x: 10,
                    color: '#000',
                    style: {
                        fontSize: '10px',

                    }

                }
            }
        },
        series: [{
            name: 'Below',
            data: [@foreach($teachers_chunk as $teacher)
                {{$teacher->failed_story_tests_statictics}},
                @endforeach]
        }, {
            name: 'Above',
            data: [
                @foreach($teachers_chunk as $teacher)
                    {{$teacher->passed_story_tests_statictics}},
                @endforeach
            ]
        }]
    });
    @endforeach
    @endif
    @foreach($grades_data as $key => $grade_data)
    @if(is_int($key))
    @if($grade_data['total_students'] != 0)
    Highcharts.chart("grade_tracker_{{$key}}", {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            color: "#F00",
            text: null,
            style: {
                font: 'bold 15px "Trebuchet MS", Verdana, sans-serif',
                color: '#000',
            }
        },
        legend: {
            align: 'center',
            itemStyle: {
                fontSize: '14px',
                color: '#000',
                align: 'center',
            },
        },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<br>{point.percentage:.1f} %',
                    style: {
                        font: 'bold 11px "Trebuchet MS", Verdana, sans-serif',
                        color: "#000",
                        textOutline: 0
                    }
                },
                showInLegend: true,
            },
            series: {
                animation: false
            }
        },


        series: [{
            type: 'pie',
            name: 'Rate',
            data: [
                ['Learn', {{ $grade_data['learn'] }}],
                ['Practise', {{ $grade_data['practise'] }}],
                ['Assess your self', {{ $grade_data['test'] }}],
            ]
        }]
    });
    @endif
    @endif
    @endforeach
</script>
</body>
