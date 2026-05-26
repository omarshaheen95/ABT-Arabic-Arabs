

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('s_website/css/animate_cards.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home pt-5 user-home ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('teacher.levels', [$grade])); ?>"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page"><?php echo e($title); ?> </span>
                        </nav>
                        <h3 class="title"><?php echo e($title); ?></h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="<?php echo e(asset("web_assets/img/levels/".$level["level"].".svg")); ?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> المستوى <?php echo e($level["level"]); ?></div>
                                    <a href="<?php echo e(route('teacher.sub_lessons', [$grade, $type, $level["level"]])); ?>"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/sub_levels.blade.php ENDPATH**/ ?>