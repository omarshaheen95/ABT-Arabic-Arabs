
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Usage Report</title>
    <meta name="description" content="<?php echo e(isset(cached()->name) ? cached()->name:'Non-Arabs student report'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="<?php echo e(asset('assets_v1/web_assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('report_assets/css/print.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('report_assets/css/report.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo e(asset('report_assets/js/print/new_highcharts.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('report_assets/js/print/highcharts-more.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('report_assets/js/print/rounded-corners.js')); ?>" type="text/javascript"></script>

    <!-- Favicon -->
    <?php if(isset(cached()->logo_min)): ?>
        <link rel="shortcut icon" href="<?php echo e(asset(cached()->logo_min)); ?>"/>
    <?php endif; ?>

</head>
<body>


<div class="page">
    <div class="subpage-w">
        <div class="row justify-content-center align-items-center">
            <div class="col-8 justify-content-center text-center">
                <img src="<?php echo e(!settingCache('logo')? asset('logo.svg?v=1'):asset(settingCache('logo'))); ?>" width="100%"
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
                <img src="<?php echo e(asset('report_assets/images/attainment_report_1_arabs_page.svg')); ?>"
                     style="max-height: 350px; width: 50%" alt="">
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h4 class="main-color my-2"><?php echo e($year->name); ?> </h4>
                <?php if(getGuard() == 'teacher'): ?>
                    <h3 class="main-color my-2">
                        <?php echo e(t('Teacher Name')); ?> : <?php echo e(Auth::guard('teacher')->user()->name); ?>

                    </h3>
                <?php endif; ?>
                <?php if($start_date && $end_date): ?>
                    <h5><?php echo e(t('From')); ?> : <?php echo e($start_date); ?> <?php echo e(t('To')); ?> : <?php echo e($end_date); ?></h5>
                <?php endif; ?>
                <h5><?php echo e(t('Release Date')); ?> : <?php echo e(now()->format('Y-m-d')); ?></h5>
                <h5>www.abt-assessments.com</h5>
                <h5>support@abt-assessments.com</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <img src="<?php echo e(asset('report_assets/images/footer-logos.svg')); ?>?v=1" width="100%" alt="">
            </div>
        </div>
    </div>
</div>

<div class="page">
    <div class="subpage-w">
        <div class="row text-center justify-content-center">
            <div class="col-11">
                <?php if(count($schools) == 1): ?>
                    <h5 class="section-title"><?php echo e(t('Usage report - School overall')); ?></h5>
                <?php else: ?>
                    <h5 class="section-title"><?php echo e(t('Usage report - Schools overall')); ?></h5>
                <?php endif; ?>
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
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($school->name); ?> <?php if(!$loop->last && !$loop->first): ?>
                                        ,
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Total Student</td>
                            <td><?php echo e($data['total_students']); ?></td>
                        </tr>
                        <?php if(getGuard() != 'teacher'): ?>
                            <tr class="text-center">
                                <td class="sub-td">Total Teachers</td>
                                <td><?php echo e($data['total_teachers']); ?></td>
                            </tr>
                            <tr class="text-center">
                                <td class="sub-td">High teacher performance</td>
                                <td><?php echo e($data['top_teacher'] ? $data['top_teacher']->name:null); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr class="text-center">
                            <td class="sub-td">High student performance</td>
                            <td><?php echo e($data['top_student'] ? $data['top_student']->name .' - '. $data['top_student']->grade_name .' - '. $data['top_student']->section:null); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Lessons assessments</td>
                            <td><?php echo e($data['total_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Above Lessons assessments</td>
                            <td><?php echo e($data['total_pass_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Below Lessons assessments</td>
                            <td><?php echo e($data['total_fail_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Stories assessments</td>
                            <td><?php echo e($data['total_story_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Above Stories assessments</td>
                            <td><?php echo e($data['total_pass_story_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Below Stories assessments</td>
                            <td><?php echo e($data['total_fail_story_tests']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Submitted Stories Records</td>
                            <td><?php echo e($data['stories_recorde']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Marked Stories Records</td>
                            <td><?php echo e($data['corrected_stories_recorde']); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td">Unmarked Stories Records</td>
                            <td><?php echo e($data['uncorrected_stories_recorde']); ?></td>
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
                <?php if(count($schools) == 1): ?>
                    <h5 class="section-title"><?php echo e(t('Usage report - School overall')); ?></h5>
                <?php else: ?>
                    <h5 class="section-title"><?php echo e(t('Usage report - Schools overall')); ?></h5>
                <?php endif; ?>
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
<?php if(getGuard() != 'teacher'): ?>
    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $teachers_chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="page">
            <div class="subpage-w">
                <div class="row text-center justify-content-center">
                    <div class="col-11">
                        <h5 class="section-title"><?php echo e(t('Lessons Tests')); ?></h5>
                    </div>
                </div>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div id="teacher_tests_<?php echo e($key); ?>" style="height: 650px"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $teachers_chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="page">
            <div class="subpage-w">
                <div class="row text-center justify-content-center">
                    <div class="col-11">
                        <h5 class="section-title"><?php echo e(t('Stories Tests')); ?></h5>
                    </div>
                </div>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-11">
                        <div id="teacher_stories_tests_<?php echo e($key); ?>" style="height: 650px"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?>
<?php $__currentLoopData = $grades_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($key >= 1 && $key <= 15): ?>
        <?php if($grade_data['total_students'] != 0): ?>
            <div class="page">
                <div class="subpage-w">
                    <div class="row text-center justify-content-center">
                        <?php if($key != 13 && is_int($key)): ?>
                            <div class="col-xs-12">
                                <h5 class="section-title">Grade <?php echo e($key); ?> / Year <?php echo e($key + 1); ?></h5>
                            </div>
                        <?php elseif($key == 13): ?>
                            <div class="col-xs-12">
                                <h5 class="section-title">Grade KG / Year 1</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="col-11">
                                <div class="table-container ">
                                    <table class="table m-0 font-14">
                                        <tbody>
                                        <tr>
                                            <td class="sub-td">Total Student</td>
                                            <td><?php echo e($grade_data['total_students']); ?></td>
                                        </tr>
                                        <?php if(getGuard() != 'teacher'): ?>
                                            <tr>
                                                <td class="sub-td">Total Teachers</td>
                                                <td><?php echo e($grade_data['total_teachers']); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="sub-td">High teacher performance</td>
                                                <td><?php echo e($grade_data['top_teacher'] ? $grade_data['top_teacher']->name:null); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="sub-td">Highest student performance on lesson</td>
                                            <td><?php echo e($grade_data['top_student_lesson'] ? $grade_data['top_student_lesson']->name .' - '. $grade_data['top_student_lesson']->grade_name .' - '. $grade_data['top_student_lesson']->section:null); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Highest student performance on stories</td>
                                            <td><?php echo e($grade_data['top_student_story'] ? $grade_data['top_student_story']->name .' - '. $grade_data['top_student_story']->grade_name .' - '. $grade_data['top_student_story']->section:null); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Submitted Lessons assessments</td>
                                            <td><?php echo e($grade_data['total_tests']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Lessons Above assessments</td>
                                            <td><?php echo e($grade_data['total_pass_tests']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Lessons Below assessments</td>
                                            <td><?php echo e($grade_data['total_fail_tests']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Submitted Stories assessments</td>
                                            <td><?php echo e($grade_data['total_story_tests']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Stories Above assessments</td>
                                            <td><?php echo e($grade_data['total_story_pass_tests']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="sub-td">Stories Below assessments</td>
                                            <td><?php echo e($grade_data['total_story_fail_tests']); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div id="grade_tracker_<?php echo e($key); ?>"
                                 style="height: 330px"></div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div id="grade_tracker_<?php echo e($key); ?>"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<script src="<?php echo e(asset('assets/vendors/general/jquery/dist/jquery.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
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
                ['Learn', <?php echo e($data['learn']); ?>],
                ['Practise', <?php echo e($data['practise']); ?>],
                ['Assess your self', <?php echo e($data['test']); ?>],
            ]
        }]
    });
    <?php if(getGuard() != 'teacher'): ?>
    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $teachers_chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    Highcharts.chart('teacher_tests_<?php echo e($key); ?>', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    "<?php echo e(trim($teacher->name)); ?>",
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            data: [<?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($teacher->failed_tests_statictics); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>]
        }, {
            name: 'Above',
            data: [
                <?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($teacher->passed_tests_statictics); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        }]
    });

    Highcharts.chart('teacher_stories_tests_<?php echo e($key); ?>', {
        chart: {
            type: 'bar'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    "<?php echo e(trim($teacher->name)); ?>",
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            data: [<?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($teacher->failed_story_tests_statictics); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>]
        }, {
            name: 'Above',
            data: [
                <?php $__currentLoopData = $teachers_chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($teacher->passed_story_tests_statictics); ?>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ]
        }]
    });
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php $__currentLoopData = $grades_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(is_int($key)): ?>
    <?php if($grade_data['total_students'] != 0): ?>
    Highcharts.chart("grade_tracker_<?php echo e($key); ?>", {
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
                ['Learn', <?php echo e($grade_data['learn']); ?>],
                ['Practise', <?php echo e($grade_data['practise']); ?>],
                ['Assess your self', <?php echo e($grade_data['test']); ?>],
            ]
        }]
    });
    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</script>
</body>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/reports/usage_report/usage_report.blade.php ENDPATH**/ ?>