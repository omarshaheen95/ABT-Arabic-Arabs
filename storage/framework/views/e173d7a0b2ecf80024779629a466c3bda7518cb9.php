
    <!DOCTYPE html>
<html lang="ar" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى</title>
    <link href="<?php echo e(asset('s_website/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendors/general/toastr/build/toastr.css')); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo e(asset('s_website/css/style.css')); ?>" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo $__env->yieldContent('style'); ?>
    <link rel="shortcut icon" href="<?php echo e(asset('website/images/logo.svg')); ?>"/>
</head>
<body>

<div class="loader"></div>

<header class="position-fixed w-100">
    <div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3 text-right">
                    <div class="logo py-1 px-1"><a href="#"><img src="<?php echo e(asset('logo.png')); ?>" width="50%"></a>
                    </div>

                </div>
                <div class="col-md-9">
                    <nav class="navbar-expand-lg navbar-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="list-inline m-0 p-0">
                                
                                
                                
                                <li class="list-inline-item text-right font-weight-bold">
                                    <span><?php echo e(Auth::user()->grade->name); ?></span>
                                </li>
                                <?php
                                    $userAssignmentsCount = 0;
 /*\App\Models\UserAssignment::query()
    ->where('user_id', Auth::user()->id)
    ->where('completed', 0)
    ->count();*/
                                ?>
                                <li class="list-inline-item text-right font-weight-bold mx-0">
                                    <a class="nav-link active" href="<?php echo e(route('assignments')); ?>">
                                        <i class="fas fa-pencil"></i>
                                        
                                        <span id="userAssignmentsCount"
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                                            <?php echo e($userAssignmentsCount); ?>

                                        </span>
                                        
                                    </a>
                                </li>
                                <li class="list-inline-item text-right font-weight-bold">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre
                                       style="color: #000">
                                        <i class="fas fa-user"></i>
                                        <span><?php echo e(Auth::user()->name); ?></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                        <a class="dropdown-item" href="<?php echo e(route('certificates')); ?>">
                                            الشهادات
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('assignments')); ?>">
                                            الواجبات
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('profile')); ?>">
                                            الملف الشخصي
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('update_password_view')); ?>">
                                            تغيير كلمة المرور
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('package_upgrade')); ?>">
                                            ترقية الإشتراك
                                        </a>
                                        <form id="logout-form" action="<?php echo e(url("/logout")); ?>" method="POST"
                                              style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                        <a class="dropdown-item" href="<?php echo e(url("/logout")); ?>" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                            تسجيل الخروج
                                        </a>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <section class="main-inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">
                        <?php if(!Request::is('home')): ?>
                            <ul class="breadcrumb mt-5 "
                                <?php if(isset($level) && !is_null($level->color)): ?> style="background-color: <?php echo e($level->color); ?>; font-weight: bold" <?php endif; ?>>
                                <li class="breadcrumb-item">
                                    <a href="<?php echo e(url('/home')); ?>"
                                       <?php if(isset($level) && !is_null($level->text_color)): ?> style="color: <?php echo e($level->text_color); ?> !important; font-weight: bold"
                                       <?php else: ?> style="font-weight: bold" <?php endif; ?>>الرئيسة</a>
                                </li>
                                <?php echo $__env->yieldPushContent('breadcrumb'); ?>
                            </ul>
                        <?php endif; ?>
                        <?php if(count($errors) > 0): ?>
                            <div class="alert alert-warning">
                                <ul style="width: 100%;">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>
<div class="modal fade" id="logout" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content px-4">
            <div class="modal-body text-center py-5"><h2
                    class="mb-0"> هل أنت متأكد من تسجيل الخروج ؟ </h2></div>
            <div class="modal-footer border-0 justify-content-center">
                <form action="/logout" method="post">
                    <?php echo e(csrf_field()); ?>

                    <button type="button" class="theme-btn btn-style-one btnSecondary" data-dismiss="modal"><span
                            class="txt"> البقاء في الصفحة الحالية </span></button>
                    <button type="submit" class="theme-btn btn-style-one btnSuccess"><span
                            class="txt"> تسجيل الخروج </span></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('s_website/js/jquery-3.4.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('s_website/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('s_website/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset("assets/vendors/general/toastr/build/toastr.min.js")); ?>" type="text/javascript"></script>

<script src="<?php echo e(asset('s_website/js/script.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script src="<?php echo e(asset("js/push.min.js")); ?>" type="text/javascript"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
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
    $(document).keydown(function (event) {
        if (event.ctrlKey == true && (event.which == '67' /*|| event.which == '86'*/)) {
            event.preventDefault();
        }
    });
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });


    Push.Permission.get();

    function notifyMe(notify) {

        toastr.success(notify.title);
        Push.create(notify.title, {
            icon: 'https://www.non-arabs.com/website/images/icons/icon.png',
            timeout: 4000,
            onClick: function () {
                window.focus();
                this.close();
            }
        });

        $('#userAssignmentsCount').text(notify.unread_notifications);
    }



    var pusher = new Pusher('dca3e1c68a7801d90df2', {
        cluster: 'mt1'
    });

    call_back = function (message) {
        notifyMe(message)
        console.log(message);
    };
    //Also remember to change channel and event name if your's are different.
    var channel = pusher.subscribe('users');
    channel.bind('user-notification', call_back);

    var user_channel = pusher.subscribe('user_<?php echo e(auth()->user()->id); ?>');
    user_channel.bind('user-notification', call_back);

</script>

<?php echo $__env->yieldContent('script'); ?>
</body>
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/layout/container.blade.php ENDPATH**/ ?>