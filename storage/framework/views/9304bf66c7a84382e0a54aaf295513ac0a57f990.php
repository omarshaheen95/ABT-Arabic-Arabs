<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="">
    <meta name="robots" content="index, follow">

    <meta name="geo.position" content="">
    <meta name="geo.placename" content="">
    <meta name="geo.region" content="">

    <meta property="og:type" content="" />
    <meta property="og:title" content="<?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="<?php echo e(asset('web_assets/img/logo.svg')); ?>" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />

    <meta name="twitter:title" content="<?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى">
    <meta name="twitter:description" content="">
    <meta name="twitter:image" content="<?php echo e(asset('web_assets/img/logo.svg')); ?>">
    <meta name="twitter:site" content="">
    <meta name="twitter:creator" content="">

    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="<?php echo e(asset('logo_min.svg')); ?>" type="image/x-icon">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="<?php echo e(asset('cdn_files/bootstrap.rtl.min.css')); ?>">
    <?php echo $__env->yieldContent('pre_style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/custom.css')); ?>?v2">
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/resposive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/exam_questions.css')); ?>">
    <link href="<?php echo e(asset('assets/vendors/general/toastr/build/toastr.css')); ?>" rel="stylesheet" />
    <?php echo $__env->yieldContent('style'); ?>

</head>
<body class="student-page user-page">
<div class="loader"></div>

<nav class="user-page-navbar">
    <div class="container">
        <div class="user-navbar">
            <div class="user-box">
                <div class="pic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23.589" height="31.195" viewBox="0 0 23.589 31.195">
                        <path id="Path_66479" data-name="Path 66479" d="M26.665,31.26l5.481,2.99a4.926,4.926,0,0,1,.88.626,18.359,18.359,0,0,1-23.589.061,4.863,4.863,0,0,1,.966-.647l5.87-2.934a2.238,2.238,0,0,0,1.237-2v-2.3a8.863,8.863,0,0,1-.555-.714,13.506,13.506,0,0,1-1.825-3.677,1.844,1.844,0,0,1-1.307-1.754V18.447a1.835,1.835,0,0,1,.614-1.362V13.531S13.707,8,21.2,8s6.759,5.53,6.759,5.53v3.554a1.833,1.833,0,0,1,.614,1.362v2.458a1.843,1.843,0,0,1-.85,1.547,12.2,12.2,0,0,1-2.223,4.6V29.3A2.241,2.241,0,0,0,26.665,31.26Z" transform="translate(-9.437 -8.001)" fill="#d9e3fd"/>
                    </svg>
                </div>
                <div class="content">
                    <h3 class="username"> <?php if(isset($user)): ?> <?php echo e($user->name); ?> <?php endif; ?></h3>
                    <p class="info"> <?php if(isset($user)): ?> <?php echo e($user->grade_name); ?> <?php endif; ?> </p>
                </div>
            </div>
            <div class="user-box">
                <div class="content"  dir="ltr">
                    <h3 class="username"><?php if(isset($user)): ?> <?php echo e($user->mobile); ?> <?php endif; ?></h3>
                    <p class="info"> Last Login: <?php if(isset($user)): ?> <?php echo e($user->last_login); ?> <?php endif; ?> </p>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- Start Navbar -->
<nav class="navbar navbar-expand-xl navbar-light">
    <div class="container">
        <div class="navbar-container">
            <div class="navbar-header d-flex justify-content-between aling-items-center w-100" >
                <a class="logo" href="/home">
                    <img src="<?php echo e(asset('web_assets/img/logo.svg')); ?>" alt="">
                </a>
                <div class="btn-collapse ms-3 d-xl-none" data-bs-toggle="collapse" data-bs-target="#main-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="45" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
            </div>

            <div class="d-flex gap-4 align-items-center">
                <?php if(guardHasAnyPermission(['',''])): ?>
                    <button type="button" class="btn btn-theme correcting_exam" data-bs-toggle="modal" data-bs-target="#correcting-exam-modal" style="font-weight: bold;background-color: #0043b3;">
                        <span class="txt" style="font-size: 18px">تصحيح</span>
                    </button>
                <?php endif; ?>
                <div class="fw-bold fs-5" style="width: 180px">
                    <?php echo e(t('Total')); ?>: <?php echo e($student_test->total_per); ?> / <?php echo e(t($student_test->status)); ?>

                </div>

            </div>


        </div>
    </div>
</nav>
<!-- End Navbar -->
<!-- Start Main -->
<main class="wrapper" id="user-home">
    <!-- Start user-home -->
   <div class="container">
       <div class="row">
           <?php if(count($errors) > 0): ?>
               <div class="alert alert-warning">
                   <ul style="width: 100%;">
                       <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <li><?php echo e($error); ?></li>
                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   </ul>
               </div>
           <?php endif; ?>
       </div>
   </div>
    <?php echo $__env->yieldContent('content'); ?>
    <!-- End user-home -->
</main>
<!-- End Main -->
<!-- Start Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-card">
                    <div class="footer-box">
                        <a href="tel:+971503842666">
                            <img src="<?php echo e(asset('web_assets/img/number.svg')); ?>" class="me-3" alt="">
                            <span dir="ltr"> 00971503842666</span>
                        </a>
                    </div>
                    <div class="footer-box">
                        <img src="<?php echo e(asset('web_assets/img/email.svg')); ?>" class="me-3" alt="">
                        <a href="mailto:Support@abt-assessments.com">
                            <span dir="ltr"> Support@abt-assessments.com</span>
                        </a>
                        <span class="mx-4"> - </span>
                        <a href="mailto:Support@Non-Arabs.com">
                            <span dir="ltr"> Support@Non-Arabs.com</span>
                        </a>
                    </div>
                    <div class="footer-box">
                        <ul class="nav nav-social-media">
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#!" target="_blank" rel="noopener noreferrer">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copyright">
                    <div class="text">2017 - 2023 © <a href="#!">ABT assessments</a>, All copyrights reserved</div>

                    <ul class="nav links">
                        <li class="nav-itme">
                            <a href="<?php echo e(asset('Privacy Policy.pdf')); ?>" class="nav-link"> Privacy policy </a>
                        </li>



                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->
<!-- Logout Modal -->































<!-- Bootstrap JavaScript Libraries -->

<script src="<?php echo e(asset('s_website/js/jquery-3.6.3.min.js')); ?>"></script>
<script src="<?php echo e(asset('cdn_files/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('cdn_files/bootstrap_5_0.min.js')); ?>"></script>
<script src="<?php echo e(asset('cdn_files/toastify-js.net_npm_toastify-js')); ?>"></script>
<script src="<?php echo e(asset('cdn_files/fancybox.umd.js')); ?>"></script>
<script src="<?php echo e(asset("assets/vendors/general/toastr/build/toastr.min.js")); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('web_assets/intlTelInput/intlTelInput.min.js')); ?>"></script>
<script src="<?php echo e(asset('web_assets/js/jquery.countdown.min.js')); ?>"></script>
<script src="<?php echo e(asset('web_assets/js/green-audio-player.min.js')); ?>"></script>
<script src="<?php echo e(asset('web_assets/js/recorder.js')); ?>"></script>
<script src="<?php echo e(asset('web_assets/js/recorder-app.js')); ?>"></script>
<script src="<?php echo e(asset('web_assets/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script src="<?php echo e(asset('s_website/js/jquery.ui.touch-punch.js')); ?>"></script>
<script src="<?php echo e(asset('s_website/js/jquery-ui.js')); ?>"></script>
<script src="<?php echo e(asset('s_website/js/script.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script src="<?php echo e(asset('s_website/js/questions/matching.js')); ?>"></script>
<script src="<?php echo e(asset('s_website/js/questions/sorting.js')); ?>"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "2000",
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    <?php if(Session::has('message')): ?>
    toastr.<?php echo e(Session::get('m-class') ? Session::get('m-class'):'success'); ?>("<?php echo e(Session::get('message')); ?>");
    <?php endif; ?>



</script>

<?php echo $__env->yieldContent('script'); ?>

<script>
    $(document).on("click", ".btn-logout", function(){
        $("#logout-modal").modal("show");
    });
</script>
</body>
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/correcting_lesson_and_story_test/layout.blade.php ENDPATH**/ ?>