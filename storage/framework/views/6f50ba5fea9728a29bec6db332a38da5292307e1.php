<title><?php echo e(t('My 1st Language Platform')); ?> | <?php echo $__env->yieldContent('title'); ?></title>
<meta charset="utf-8"/>
<meta name="description" content="<?php echo e(!settingCache('name') ?settingCache('name'):'My 1st Language Platform'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php echo $__env->yieldContent('b_style'); ?>
<!--begin::Vendor Stylesheets(used for this page only)-->
<link href="<?php echo e(asset('assets_v1/plugins/custom/fullcalendar/fullcalendar.bundle.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('assets_v1/plugins/custom/datatables/datatables.bundle.css')); ?>" rel="stylesheet" type="text/css" />
<!--end::Vendor Stylesheets-->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<?php if(app()->getLocale() == 'ar'): ?>
    <link href="<?php echo e(asset('assets_v1/plugins/global/plugins.bundle.rtl.css')); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('assets_v1/css/style.bundle.rtl.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>
<?php else: ?>
    <link href="<?php echo e(asset('assets_v1/plugins/global/plugins.bundle.css')); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo e(asset('assets_v1/css/style.bundle.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>
<?php endif; ?>
<link href="<?php echo e(asset('assets_v1/css/table-style.css')); ?>?v=2" rel="stylesheet" type="text/css"/>
<link href="<?php echo e(asset('assets_v1/css/custom.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet" type="text/css"/>

<!--end::Global Stylesheets Bundle-->
<link rel="SHORTCUT ICON"  type="image/x-icon" href="<?php echo e(!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))); ?>" />

<?php echo $__env->yieldContent('style'); ?>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/layout_parts/style.blade.php ENDPATH**/ ?>