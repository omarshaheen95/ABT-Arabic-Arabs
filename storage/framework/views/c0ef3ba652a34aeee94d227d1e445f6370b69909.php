<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="<?php echo e(asset('assets_v1/lib/bootstrap-5.0.2/css/bootstrap.rtl.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('report_assets/css/print.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('report_assets/css/student_card.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))); ?>" />

    <title><?php echo e($title); ?></title>
    <style>

    </style>
</head>
<body>
<?php
    $count = 0;
?>
<?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="page">
        <div class="subpage-w">
            <div class="row">
                <?php $__currentLoopData = $student; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $std): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 p-1">
                        <div class="p-3 student-card">
                            <div class="d-flex flex-column bg-white p-2 h-100" style="border-radius: 8px">
                                <div class="row py-2">
                                    <div class="col-4 text-center">
                                        <div class="image-container-cards">
                                            <img class="logo" src="<?php echo e(asset(optional($std->school)->logo)); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-8 d-flex flex-column align-items-center mt-1 " style="font-size: 12px">
                                        <div class="fw-bold text-center"><?php echo e(optional($std->school)->name); ?></div>
                                        <div class="fw-bold"><?php echo e(t('Student Login')); ?></div>
                                    </div>
                                </div>
                                <hr class="my-1" style="border-top: 1px solid #00000040;">
                                <div class="row mt-1 px-1 pb-2" style="position: relative">
                                    <div class="col-7 d-flex flex-column pe-0">
                                        <div class="col-12 s-content"><span class="s-title"><?php echo e(t('Name')); ?> : <?php echo e($std->name); ?></span></div>
                                        <div class="col-12 s-content"><span class="s-title"><?php echo e(t('ID')); ?> : </span><?php echo e($std->id_number ?? '-'); ?></div>
                                        <div class="col-12 s-content"><span class="s-title"><?php echo e(t('Grade')); ?> : </span> <?php echo e($std->grade->name); ?></div>
                                        <div class="col-12 s-content"><span class="s-title"> <?php echo e(t('Section')); ?> : </span><?php echo e($std->section ?? '-'); ?></div>
                                        <div class="col-12 s-content"><span class="s-title"><?php echo e(t('Teacher')); ?>:</span><?php echo e(optional($std->teacher)->name); ?></div>
                                    </div>
                                    <ul class="ms-3 mt-1">
                                        <li>www.arabic-arabs.com</li>
                                        <li><?php echo e(t('Student login')); ?></li>
                                        <li><?php echo e(t('Username')); ?>: <br /><span class="username" dir="ltr"><?php echo e($std->email); ?></span></li>
                                        <li><?php echo e(t('Password')); ?>: <span class="username" dir="ltr">123456</span></li>
                                    </ul>
                                    <div class="col-5 mt-1 d-flex justify-content-end p-0 pe-1" style="position: absolute;right: 180px">
                                        <?php if($std->gender == 'Girl'): ?>
                                            <?php echo QrCode::color(255, 0, 194)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); ?>

                                        <?php elseif($std->gender == 'Boy'): ?>
                                            <?php echo QrCode::color(0, 166, 255)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); ?>

                                        <?php else: ?>
                                            <?php echo QrCode::color(19,137,68)->size(100)->generate(sysDomain()."/login?username=$std->email".'&password=123456'); ?>

                                        <?php endif; ?>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>


        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>

</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/cards_and_qr.blade.php ENDPATH**/ ?>