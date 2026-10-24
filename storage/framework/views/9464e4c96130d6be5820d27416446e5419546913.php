<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(!empty($forced)): ?>
        <div class="alert alert-warning d-flex align-items-center mb-6">
            <i class="la la-exclamation-triangle fs-2 me-3"></i>
            <div>
                <h4 class="mb-1"><?php echo e(t('Password change required')); ?></h4>
                <span>
                    <?php if(($reason ?? null) === 'expired'): ?>
                        <?php echo e(t('Your password has expired, please choose a new one to continue.')); ?>

                    <?php else: ?>
                        <?php echo e(t('You must change your password before you can continue.')); ?>

                    <?php endif; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <form class="form" id="form_data" action="<?php echo e(route('teacher.update-password')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="form-group row">

                <div class="col-lg-4 mb-2">
                    <label class="form-label mb-1"><?php echo e(t('Old Password')); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input name="old_password" type="password" placeholder="<?php echo e(t('Old Password')); ?>"
                               autocomplete="current-password" class="form-control"/>
                        <button class="btn btn-icon btn-light border" type="button" data-password-toggle
                                title="<?php echo e(t('Show / Hide Password')); ?>">
                            <i class="la la-eye"></i>
                        </button>
                    </div>
                </div>

                <?php echo $__env->make('components.password-fields', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=2"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Teacher\TeacherPasswordRequest::class, '#form_data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/profile/password.blade.php ENDPATH**/ ?>