<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale()=='ar'?'rtl':'ltr'); ?>">

<head>
    <title>My 1st Language Platform | <?php echo $__env->yieldContent('title'); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="SHORTCUT ICON"  type="image/x-icon" href="<?php echo e(!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))); ?>" />

    <?php if(app()->getLocale()=='ar'): ?>
        <link href="<?php echo e(asset('assets_v1/web_assets/css/bootstrap.rtl.min.css')); ?>" rel="stylesheet">
    <?php else: ?>
        <link href="<?php echo e(asset('assets_v1/web_assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="<?php echo e(asset('assets_v1/web_assets/css/auth.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets_v1/web_assets/css/responsive.css')); ?>" rel="stylesheet">
    <style>
        @font-face {
            font-family:AlmaraiBold;
            src: url(<?php echo e(asset('assets_v1/fonts/Almarai/Almarai-Bold.ttf')); ?>);
        }
        @font-face {
            font-family:Almarai;
            src: url(<?php echo e(asset('assets_v1/fonts/Almarai/Almarai-Regular.ttf')); ?>);
        }
        body{
            font-family: "Almarai" !important;
            font-weight: bold;
            font-size: 18px;
            color: var(--dark-color);
        }
        .title{
            font-family: "Almarai" !important;
            font-weight: bold;
        }
    </style>
</head>

<body>

<main class="login-page science">
   <?php echo $__env->yieldContent('content'); ?>
</main>


<script src="<?php echo e(asset('assets_v1/web_assets/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/web_assets/js/toastify.js')); ?>"></script>
<script src="<?php echo e(asset('assets_v1/web_assets/js/custom.js')); ?>"></script>
<?php echo $__env->yieldContent('script'); ?>

</body>
</html>

<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/auth_v2.blade.php ENDPATH**/ ?>