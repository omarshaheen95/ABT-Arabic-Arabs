
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Teacher Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="<?php echo e(asset('assets_v1/web_assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('report_assets/css/print.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('report_assets/css/report.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>

    <?php if(isset(cached()->logo_min)): ?>
        <link rel="shortcut icon" href="<?php echo e(asset(cached()->logo_min)); ?>"/>
    <?php endif; ?>

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


<div class="page">
    <div class="subpage-w">
        <div class="row justify-content-center align-items-center">
            <div class="col-8 justify-content-center text-center">
                <img src="<?php echo e(asset('report_assets/images/circle-logo.svg')); ?>" width="40%"
                     alt="">
            </div>
        </div>

        <div class="row text-center justify-content-center mt-5">
            <div class="col-12">
                <h3 class="main-color">تقرير إحصائيات المعلمين</h3>
            </div>
            <div class="col-12">
                <h3 class="main-color">Teacher Statistics Report</h3>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <img src="<?php echo e(asset('report_assets/images/attainment_report_1_arabs_page.svg')); ?>"
                     style="max-height: 300px; width: 50%" alt="">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <?php if($school): ?>
                    <h3 class="main-color my-2"><?php echo e($school->name); ?></h3>
                <?php endif; ?>
                <?php if($year): ?>
                    <h4 class="main-color my-2"><?php echo e($year->name); ?></h4>
                <?php endif; ?>
                <?php if($start_date && $end_date): ?>
                    <h5><?php echo e(t('From')); ?> : <?php echo e($start_date); ?> &nbsp;&nbsp; <?php echo e(t('To')); ?> : <?php echo e($end_date); ?></h5>
                <?php endif; ?>
                <?php if(!$include_archived): ?>
                    
                    <h5><?php echo e(t('Archived students are excluded')); ?></h5>
                <?php endif; ?>
                <h5><?php echo e(t('Release Date')); ?> : <?php echo e(now()->format('Y-m-d')); ?></h5>


            </div>
        </div>






    </div>
    
    <div class="footer text-center mt-3">
        <small>www.abt-assessments.com &nbsp;|&nbsp; support@abt-assessments.com</small>
    </div>
</div>


<?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="page">
    <div class="subpage-w">

        
        <div class="row text-center justify-content-center mb-3">
            <div class="col-11">
                <h5 class="section-title"><?php echo e(t('Teacher Statistics')); ?></h5>
            </div>
        </div>

        
        <div class="row justify-content-center mb-3">
            <div class="col-11">
                <div class="teacher-info-card">
                    <h4><?php echo e($teacher->name); ?></h4>
                    <div class="info-row">
                        <?php if($teacher->email): ?>
                            <div class="info-item"><?php echo e(t('Email')); ?> : <span><?php echo e($teacher->email); ?></span></div>
                        <?php endif; ?>
                        <div class="info-item"><?php echo e(t('Registration Date')); ?> : <span><?php echo e($teacher->created_at ? $teacher->created_at->format('Y-m-d') : '-'); ?></span></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="table-container">
                    <table class="table m-0 font-14">
                        <tbody>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Students')); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="3"><?php echo e(t('Total Students')); ?></td>
                            <td><strong><?php echo e($teacher->total_students); ?></strong></td>
                        </tr>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Lesson Assessments')); ?> </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td"><?php echo e(t('Total')); ?></td>
                            <td class="sub-td"><span class="badge-pass"><?php echo e(t('Pass')); ?></span></td>
                            <td class="sub-td"><span class="badge-fail"><?php echo e(t('Fail')); ?></span></td>
                            <td class="sub-td"><span class="badge-fail"><?php echo e(t('Correction Required')); ?></span></td>
                        </tr>
                        <tr class="text-center">
                            <td><strong><?php echo e($teacher->total_lesson_tests); ?></strong></td>
                            <td><span class="badge-pass"><?php echo e($teacher->pass_lesson_tests); ?></span></td>
                            <td><span class="badge-fail"><?php echo e($teacher->fail_lesson_tests); ?></span></td>
                            <td><span class="badge-fail"><?php echo e($teacher->correction_required); ?></span></td>
                        </tr>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Story Assessments')); ?> </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2" class="sub-td"><?php echo e(t('Total')); ?></td>
                            <td class="sub-td"><span class="badge-pass"><?php echo e(t('Pass')); ?></span></td>
                            <td class="sub-td"><span class="badge-fail"><?php echo e(t('Fail')); ?></span></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><strong><?php echo e($teacher->total_story_tests); ?></strong></td>
                            <td><span class="badge-pass"><?php echo e($teacher->pass_story_tests); ?></span></td>
                            <td><span class="badge-fail"><?php echo e($teacher->fail_story_tests); ?></span></td>
                        </tr>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Assignments')); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2"><?php echo e(t('Lesson Assignments')); ?> </td>
                            <td colspan="2"><strong><?php echo e($teacher->total_lesson_assignments); ?></strong></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2"><?php echo e(t('Story Assignments')); ?> </td>
                            <td colspan="2"><strong><?php echo e($teacher->total_story_assignments); ?></strong></td>
                        </tr>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Student Homework')); ?></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2"><?php echo e(t('Lesson Homework Total')); ?> </td>
                            <td><strong><?php echo e($teacher->total_lesson_hw); ?></strong></td>
                            <td><span class="badge-corrected"><?php echo e(t('Done')); ?>: <?php echo e($teacher->completed_lesson_hw); ?></span></td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td" colspan="2"><?php echo e(t('Story Homework Total')); ?> </td>
                            <td><strong><?php echo e($teacher->total_story_hw); ?></strong></td>
                            <td><span class="badge-corrected"><?php echo e(t('Done')); ?>: <?php echo e($teacher->completed_story_hw); ?></span></td>
                        </tr>

                        
                        <tr class="text-center stat-group-header">
                            <td colspan="4"><?php echo e(t('Story Recordings')); ?> </td>
                        </tr>
                        <tr class="text-center">
                            <td class="sub-td"><?php echo e(t('Total')); ?></td>
                            <td class="sub-td"><span class="badge-corrected"><?php echo e(t('Corrected')); ?></span></td>
                            <td class="sub-td"><span class="badge-pending"><?php echo e(t('Pending')); ?></span></td>
                            <td class="sub-td"><span class="badge-returned"><?php echo e(t('Returned')); ?></span></td>
                        </tr>
                        <tr class="text-center">
                            <td><strong><?php echo e($teacher->total_story_records); ?></strong></td>
                            <td><span class="badge-corrected"><?php echo e($teacher->corrected_story_records); ?></span></td>
                            <td><span class="badge-pending"><?php echo e($teacher->pending_story_records); ?></span></td>
                            <td><span class="badge-returned"><?php echo e($teacher->returned_story_records); ?></span></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    
    <div class="footer text-center mt-3">
        <small>www.abt-assessments.com &nbsp;|&nbsp; support@abt-assessments.com</small>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<script src="<?php echo e(asset('assets/vendors/general/jquery/dist/jquery.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js')); ?>" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // window.print();
    });
</script>

</body>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/reports/teacher_report/teacher_report.blade.php ENDPATH**/ ?>