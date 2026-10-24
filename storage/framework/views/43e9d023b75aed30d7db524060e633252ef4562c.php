
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
              action="<?php echo e(route('manager.update-profile')); ?>"
              method="post">
            <?php echo csrf_field(); ?>
            <?php if(isset($manager)): ?>
                <input type="hidden" name="_method" value="PATCH"/>
            <?php endif; ?>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="mb-2"><?php echo e(t('Name')); ?> : </label>
                    <input name="name" type="text" placeholder="<?php echo e(t('Name')); ?>"
                           class="form-control"
                           value="<?php echo e(\Illuminate\Support\Facades\Auth::user()->name); ?>"
                    />
                </div>
                <div class="col-lg-6">
                    <label class="mb-2"><?php echo e(t('Email')); ?> :</label>
                    <input name="email" type="text" placeholder="<?php echo e(t('Email')); ?>"
                           class="form-control"
                           value="<?php echo e(\Illuminate\Support\Facades\Auth::user()->email); ?>"/>
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
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\ManagerProfileRequest::class, '#form_data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/managers/profile.blade.php ENDPATH**/ ?>