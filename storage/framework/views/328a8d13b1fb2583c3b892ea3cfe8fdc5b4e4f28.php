<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('teacher.stories.list', $story->grade)); ?>">القصص</a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> <?php echo e($story->name); ?></h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('teacher.stories.list', $story->grade)); ?>"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row" dir="rtl">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="<?php echo e(route('teacher.stories.list', $story->grade)); ?>" class=" btn btn-theme px-5">
                        <i class="fa-solid fa-arrow-right"></i>
                        القصص
                    </a>
                    
                    
                    
                    
                    
                    <a href="<?php echo e(route('teacher.stories.show', [$story->id,'read', $grade])); ?>" class=" btn btn-theme px-5">
                        التدريب
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div class="card-body pb-0">
                    <div id="video<?php echo e($story->id); ?>" class="mb-4"></div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('s_website/js/playerjs.js')); ?>"></script>

    <script>
        var player_<?php echo e($story->id); ?> = new Playerjs({
            id: "video<?php echo e($story->id); ?>",
            file: '<?php echo e(asset($story->video)); ?>',
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/story/learn.blade.php ENDPATH**/ ?>