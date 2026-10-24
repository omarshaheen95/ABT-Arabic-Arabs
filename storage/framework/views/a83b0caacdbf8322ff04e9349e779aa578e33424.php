<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startPush('breadcrumb'); ?>
        <li class="breadcrumb-item">
            <?php echo e(t('Change password')); ?>

        </li>
    <?php $__env->stopPush(); ?>
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">
                        تغيير كلمة المرور - Change password
                    </h4>
                </div>
                <form enctype="multipart/form-data" id="form_information" action="<?php echo e(route('update_password')); ?>"
                      method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body pb-0">







                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('New Password')); ?></label>
                            <div class="col-xl-6">
                                <input class="form-control" name="password" type="password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-1"></label>
                            <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Password confirmation')); ?></label>
                            <div class="col-xl-6">
                                <input class="form-control" name="password_confirmation" type="password">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 card-footer bg-white text-left">
                        <button type="submit" class="btn btn-danger"><?php echo e(t('Complete & save')); ?></button>
                    </div>
                </form>
            </div>
        </section>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo $validator->selector('#form_information'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/settings/password.blade.php ENDPATH**/ ?>