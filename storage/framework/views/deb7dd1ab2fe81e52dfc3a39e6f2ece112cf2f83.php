<!DOCTYPE html>
<html lang="<?php echo e(app()->getlocale()); ?>" dir="<?php echo e(app()->getLocale()=='ar'?'rtl':'ltr'); ?>">
<!-- begin::Head -->
<head>
    <?php echo $__env->make('general.layout_parts.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<!-- end::Head -->

<!--begin::Body-->
<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::App-->
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <?php echo $__env->make('general.layout_parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

            <?php echo $__env->make(getGuard().'.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">

                    <!--begin::Toolbar-->
                    <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

                        <!--begin::Toolbar container-->
                        <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">



                            <!--begin::Page title-->
                            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                                <!--begin::Title-->
                                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                    <?php echo $__env->yieldContent('title'); ?>
                                </h1>
                                <!--end::Title-->


                                <!--Breadcrumb-->
                                <ol class="breadcrumb breadcrumb-line text-muted fs-7 fw-semibold">
                                    <?php if(Request::is(getGuard().'/home')): ?>
                                        <li class="breadcrumb-item text-muted"><?php echo e(t('Home')); ?></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item"><a href="<?php echo e(route(getGuard().'.home')); ?>" class=""> <?php echo e(t('Home')); ?></a></li>
                                    <?php endif; ?>
                                    <?php echo $__env->yieldPushContent('breadcrumb'); ?>
                                </ol>
                                <!--Breadcrumb-->

                            </div>
                            <!--end::Page title-->
                            <!--begin::Actions-->
                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                <?php echo $__env->yieldContent('actions'); ?>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Toolbar container-->
                    </div>
                    <!--end::Toolbar-->

                    <!--begin::Content-->
                    <div id="kt_app_content" class="app-content  flex-column-fluid ">


                        <!--begin::Content container-->
                        <div id="kt_app_content_container" class="app-container  container-xxl ">
                            <?php if(Session::has('not_active')): ?>
                                <!--begin::Alert-->
                                <div class="alert alert-warning d-flex align-items-center p-5">
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                        <i class="path3"></i>
                                    </i>
                                    <!--end::Icon-->

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Title-->
                                        <h4 class="mb-1 text-dark"><?php echo e(t('Not Active Account')); ?></h4>
                                        <!--end::Title-->

                                        <!--begin::Content-->
                                        <span class="text-gray-800"><?php echo e(t('Your account is not active, please contact support')); ?></span>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Alert-->
                            <?php else: ?>
                                <!--begin::Card-->
                                <?php echo $__env->yieldContent('charts'); ?>
                                <?php if (! empty(trim($__env->yieldContent('pre-content')))): ?>
                                    <?php echo $__env->yieldContent('pre-content'); ?>
                                <?php endif; ?>
                                <?php echo $__env->make('components.password-expiry-notice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                                    <div class="card py-2 px-3">
                                        <!--begin::Card header-->
                                        <?php if(count($errors) > 0): ?>
                                            <div class="mx-10 mt-5 mb-0 alert alert-dismissible alert-danger d-flex flex-column flex-sm-row">
                                                <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                                                    <i class="path1"></i>
                                                    <i class="path2"></i>
                                                    <i class="path3"></i>
                                                </i>
                                                <div class="d-flex flex-column">
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span><?php echo e($error); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                                <!--begin::Close-->
                                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                    <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                                <!--end::Close-->
                                            </div>
                                        <?php endif; ?>
                                        <!--end::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body">
                                            <?php if (! empty(trim($__env->yieldContent('filter')))): ?>
                                                <!--begin::Accordion-->

                                                <!--end::Accordion-->

                                                <div class="row mt-5 mb-2">
                                                    <div class="accordion " id="kt_accordion_1">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="kt_accordion_1_header_1">
                                                                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                                                                    <?php echo e(t('Filter')); ?>

                                                                </button>
                                                            </h2>
                                                            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                                                                <div class="accordion-body">
                                                                    <div class="card-header border-0 px-2">
                                                                        <form class="w-100 filter" id="filter" action="" autocomplete="off">
                                                                            <?php echo csrf_field(); ?>
                                                                            <div class="row">
                                                                                <?php echo $__env->yieldContent('filter'); ?>

                                                                                <div class="col-12 mt-2 d-flex justify-content-end gap-2">

                                                                                    <button type="button" class="btn btn-primary " id="kt_search">
                                                            <span>
                                                                <i class="la la-search"></i>
                                                                <span><?php echo e(t('Search')); ?></span>
                                                            </span>
                                                                                    </button>

                                                                                    <button type="reset" class="btn btn-secondary " id="kt_reset">
                                                            <span>
                                                                <i class="la la-close"></i>
                                                                <span><?php echo e(t('Reset')); ?></span>
                                                            </span>
                                                                                    </button>

                                                                                    <?php if (! empty(trim($__env->yieldContent('filter-actions')))): ?>
                                                                                        <?php echo $__env->yieldContent('filter-actions'); ?>
                                                                                    <?php else: ?>

                                                                                </div>
                                                                                <?php endif; ?>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>




                                            <?php endif; ?>

                                            <?php echo $__env->yieldContent('content'); ?>

                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                <?php endif; ?>
                                <!--end::Card-->
                            <?php endif; ?>

                        </div>
                        <!--end::Content container-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Content wrapper-->


                <?php echo $__env->make('general.layout_parts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::App-->


<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
    </i>
</div>
<!--end::Scrolltop-->


<?php echo $__env->make('general.layout_parts.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
<!--end::Body-->
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/layout/container.blade.php ENDPATH**/ ?>