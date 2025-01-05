{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Report | {{$student->name}}</title>
    <meta name="description" content="{{ isset(cached()->name) ? cached()->name:'Arabic-Arabs student report' }}">
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
                <h1 class="main-color">Student Report</h1>
            </div>
            <div class="col-6">
                <h1 class="main-color">تقرير الطالب</h1>
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
                <h4 class="main-color my-2">School : {{$student->school->name}} </h4>
                <h4 class="main-color my-2">Teacher : {{optional($student->teacher)->name}} </h4>
                <br />
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
                <h5 class="section-title">{{t('Student Report - Basic Data') }}</h5>
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
                            <td class="sub-td">Student</td>
                            <td>{{$student->name}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Email</td>
                            <td>{{$student->email}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Student ID</td>
                            <td>{{$student->id_number}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" width="45%">School</td>
                            <td>
                                {{$student->school->name}}
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Teacher</td>
                            <td>{{optional($student->teacher)->name}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Grade</td>
                            <td>{{$student->grade_name}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Section</td>
                            <td>{{$student->section}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Gender</td>
                            <td>{{$student->gender}}</td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Nationality</td>
                            <td>{{$student->nationality}}</td>
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
                <h5 class="section-title">{{t('Student Report - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row text-center justify-content-center">
            <div class="col-11">
                <h5>{{t('Lessons - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <thead>
                        <tr class="text-center">
                            <th class="above-td">Above</th>
                            <th class="below-td">Below</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{$passed_lessons}}</td>
                            <td>{{$failed_lessons}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row text-center justify-content-center mt-4">
            <div class="col-11">
                <h5>{{t('Stories - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <thead>
                        <tr class="text-center">
                            <th class="above-td">Above</th>
                            <th class="below-td">Below</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{$passed_stories}}</td>
                            <td>{{$failed_stories}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row text-center justify-content-center mt-4">
            <div class="col-11">
                <h5>{{t('Lessons Assignments - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <thead>
                        <tr class="text-center">
                            <th class="above-td">Completed</th>
                            <th class="below-td">Uncompleted</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{$completed_lessons}}</td>
                            <td>{{$uncompleted_lessons}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row text-center justify-content-center mt-4">
            <div class="col-11">
                <h5>{{t('Stories Assignments - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <thead>
                        <tr class="text-center">
                            <th class="above-td">Completed</th>
                            <th class="below-td">Uncompleted</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{$completed_stories}}</td>
                            <td>{{$uncompleted_stories}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row text-center justify-content-center mt-4">
            <div class="col-11">
                <h5>{{t('Stories Records - Overall Data') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0">
                        <thead>
                        <tr class="text-center">
                            <th class="inline-td">
                                Pending
                            </th>
                            <th class="above-td">
                                Corrected
                            </th>
                            <th class="below-td">
                                Returned
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td>{{$pending_stories}}</td>
                            <td>{{$corrected_stories}}</td>
                            <td>{{$returned_stories}}</td>
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
                <h5 class="section-title">{{t('Tracking Overall Data') }}</h5>
            </div>
        </div>
        <div class="row text-center justify-content-center">
            <div class="col-11">
                <h5>{{t('Tracking Lessons') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div id="lessons_tacking"></div>
            </div>
        </div>
        <hr />
        <div class="row text-center justify-content-center">
            <div class="col-11">
                <h5>{{t('Tracking Stories') }}</h5>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div id="stories_tacking"></div>
            </div>
        </div>
    </div>
</div>
@foreach($lessons_info as $lessons)
    <div class="page">
        <div class="subpage-w">
            <div class="row text-center justify-content-center">
                <h5 class="section-title">{{t('Student interaction with lessons') }}</h5>
            </div>
            <br>
            <br>
            <div class="row justify-content-center">
                <table class="table m-0">
                    <thead>
                    <tr class="text-center">
                        <th class="main-td">Lesson</th>
                        <th class="main-td small-2x">Assessment Score</th>
                        <th class="main-td small-2x">Time Consumed</th>
                        <th class="main-td small-2x">Assessment Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lessons as $lesson)
                        <tr class="text-center">
                            <td>{{$lesson['lesson']->name}}</td>
                            @if(isset($lesson['user_test']) && !is_null($lesson['user_test']))
                                <td class="small-2x">{{$lesson['user_test']->total_per}}</td>
                                <td class="small-2x">{{$lesson['time_consumed']}}</td>
                                <td class="small-2x">{{optional($lesson['user_test']->created_at)->format('d M Y')}}</td>

                            @else

                                <td class="small-2x"></td>
                                <td class="small-2x"></td>
                                <td class="small-2x"></td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach
@foreach($stories_info as $stories)
    <div class="page">
        <div class="subpage-w">
            <div class="row text-center justify-content-center">
                <h5 class="section-title">{{t('Student interaction with stories') }}</h5>
            </div>
            <br>
            <br>
            <div class="row justify-content-center">
                <table class="table m-0">
                    <thead>
                    <tr class="text-center">
                        <th class="main-td">Story</th>
                        <th class="main-td small-2x">Assessment Score</th>
                        <th class="main-td small-2x">Time Consumed</th>
                        <th class="main-td small-2x">Assessment Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stories as $story)
                        <tr class="text-center">
                            <td>{{$story['story']->name}}</td>
                            @if(isset($story['user_test']) && !is_null($story['user_test']))
                                <td class="small-2x">{{$story['user_test']->total_per}}</td>
                                <td class="small-2x">{{$story['time_consumed']}}</td>
                                <td class="small-2x">{{optional($story['user_test']->created_at)->format('d M Y')}}</td>

                            @else

                                <td class="small-2x"></td>
                                <td class="small-2x"></td>
                                <td class="small-2x"></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
    const userLessonsTrackers = <?php echo json_encode($user_lessons_trackers); ?>;
    // Extract categories (months) and series data
    const lessonsCategories = Object.keys(userLessonsTrackers);
    const learnData = [];
    const practiseData = [];
    const testData = [];
    const playData = [];
    lessonsCategories.forEach(month => {
        learnData.push(userLessonsTrackers[month]['learn']);
        practiseData.push(userLessonsTrackers[month]['practise']);
        testData.push(userLessonsTrackers[month]['test']);
        playData.push(userLessonsTrackers[month]['play']);
    });

    // Create the chart
    Highcharts.chart('lessons_tacking', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Monthly Activity Tracker'
        },
        xAxis: {
            categories: lessonsCategories // Months as categories
        },
        yAxis: {
            title: {
                text: 'Activity Count'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        },
        series: [
            {
                name: 'Learn',
                data: learnData
            },
            {
                name: 'Practise',
                data: practiseData
            },
            {
                name: 'Test',
                data: testData
            },
            {
                name: 'Play',
                data: playData
            }
        ]
    });

    const userStoriesTrackers = <?php echo json_encode($user_stories_trackers); ?>;
    // Extract categories (months) and series data
    const storiesCategories = Object.keys(userStoriesTrackers);
    const watchingData = [];
    const readingData = [];
    const testStoryData = [];
    storiesCategories.forEach(month => {
        watchingData.push(userStoriesTrackers[month]['watching']);
        readingData.push(userStoriesTrackers[month]['reading']);
        testStoryData.push(userStoriesTrackers[month]['test']);
    });

    // Create the chart
    Highcharts.chart('stories_tacking', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Monthly Activity Tracker'
        },
        xAxis: {
            categories: storiesCategories // Months as categories
        },
        yAxis: {
            title: {
                text: 'Activity Count'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        },
        series: [
            {
                name: 'Watching',
                data: watchingData
            },
            {
                name: 'Reading',
                data: readingData
            },
            {
                name: 'Test Story',
                data: testStoryData
            }
        ]
    });


</script>
</body>
