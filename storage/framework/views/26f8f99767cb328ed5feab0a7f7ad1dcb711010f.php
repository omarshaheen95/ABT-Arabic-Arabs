<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home level-section">
        <div class="container">
            <div class="row justify-content-center">
                <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <a href="<?php echo e(route('stories.list', $level)); ?>" class="level-box">
                            <div class="pic">
                                <img src="<?php echo e(asset("web_assets/img/levels/$level.svg")); ?>?v=2" alt="">
                            </div>
                            <div class="content">
                                <div class="title">
                                    المستوى <?php echo e($level != 15 ? $level : 'التأسيسي'); ?>


                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/stories_levels.blade.php ENDPATH**/ ?>