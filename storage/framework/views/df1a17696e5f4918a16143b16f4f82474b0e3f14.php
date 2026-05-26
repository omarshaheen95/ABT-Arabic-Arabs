<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale()!= 'ar'?'ltr':'rtl'); ?>">
<head>
    <title><?php echo e(t('My 1st Language Platform')); ?>|<?php echo $__env->yieldContent('title'); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="<?php echo e(asset('assets_v1/lib/bootstrap-5.0.2/css/bootstrap.rtl.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_v1/web_assets/css/custom.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets_v1/web_assets/css/responsive.css')); ?>">
    <link rel="shortcut icon" href="<?php echo e(!settingCache('logo_min')? asset('logo_m.svg'):asset(settingCache('logo_min'))); ?>" />
    <?php if(app()->getLocale() == "ar"): ?>
        <style>
            html, body ,.title{
                font-family: Almarai, "sans-serif";
            }
        </style>
    <?php endif; ?>
    <?php echo $__env->yieldContent('style'); ?>

</head>
<body>

<?php echo $__env->yieldContent('content'); ?>
<!-- Bootstrap JavaScript Libraries -->
<script src="<?php echo e(asset('assets_v1/web_assets/js/jquery-3.6.3.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/web_assets/js/popper-2.9.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/lib/bootstrap-5.0.2/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/web_assets/js/custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/js/browserInfo.js')); ?>"></script>
<?php echo $__env->yieldContent('script'); ?>

</body>
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/auth.blade.php ENDPATH**/ ?>