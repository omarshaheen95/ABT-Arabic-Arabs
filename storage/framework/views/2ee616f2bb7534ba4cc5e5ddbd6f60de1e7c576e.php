
<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3 class="title"> المستوى - <?php echo e($grade); ?> </h3>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('teacher.levels.stories', $grade)); ?>"> المستويات </a>
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="lesson-box">
                            <div class="pic">
                                <img src="<?php echo e($story->image); ?>" alt="">
                            </div>
                            <div class="content">
                                <div class="title"> <?php echo e($story->name); ?> </div>

                                <a href="<?php echo e(route('teacher.stories.show', [$story->id, 'watch', $grade])); ?>"
                                   class="btn  btn-theme w-75 mb-4">
                                    استمع وشاهد
                                </a>
                                <div class="option">
                                    <a href="<?php echo e(route('teacher.stories.show', [$story->id, 'test', $grade])); ?>"
                                       class="btn btn-soft-danger"> اختبر نفسك </a>
                                    <a href="<?php echo e(route('teacher.stories.show', [$story->id, 'read', $grade])); ?>"
                                       class="btn btn-soft-info"> اقرأ القصة </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/stories_list.blade.php ENDPATH**/ ?>