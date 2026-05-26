<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <form class="form" id="form_data"
              action="<?php echo e(route('teacher.update-password')); ?>"
              method="post">
            <?php echo csrf_field(); ?>

            <div class="form-group row">

                <div class="col-lg-4">
                    <label class="mb-2"><?php echo e(t('Old Password')); ?> :</label>
                    <input name="old_password" type="password" placeholder="<?php echo e(t('Old Password')); ?>"
                           class="form-control"/>
                </div>
                <div class="col-lg-4">
                    <label class="mb-2"><?php echo e(t('Password')); ?> :</label>
                    <input name="password" type="password" placeholder="<?php echo e(t('Password')); ?>"
                           class="form-control"/>
                </div>
                <div class="col-lg-4">
                    <label class="mb-2"><?php echo e(t('Confirmed Password')); ?> :</label>
                    <input name="password_confirmation" type="password" placeholder="<?php echo e(t('Confirmed Password')); ?>"
                           class="form-control"/>
                </div>

            </div>

            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2"><?php echo e(t('Submit')); ?></button>
                </div>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Teacher\TeacherPasswordRequest::class, '#form_data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/profile/password.blade.php ENDPATH**/ ?>