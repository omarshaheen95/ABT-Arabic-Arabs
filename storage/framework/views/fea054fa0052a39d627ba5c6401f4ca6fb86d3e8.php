

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(isset($package) ? route('manager.package.update', $package->id): route('manager.package.store')); ?>"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($package)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row">
            <div class="row">
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Name ')); ?></label>
                        <input type="text" id="" name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>" value="<?php echo e(isset($package) ? $package->name : old("name")); ?>" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="days" class="form-label"><?php echo e(t('Days')); ?></label>
                        <input type="number" id="days" name="days" class="form-control" placeholder="<?php echo e(t('Days')); ?>"
                               value="<?php echo e(isset($package) ? $package->days : old("color")); ?>" required>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="price" class="form-label"><?php echo e(t('Price')); ?></label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="<?php echo e(t('Price')); ?>"
                               value="<?php echo e(isset($package) ? $package->price : old("price")); ?>" required>
                    </div>
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" <?php echo e(isset($package) && $package->active ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Active')); ?>

                        </label>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(isset($package)?t('Update'):t('Save')); ?></button>
                </div>
            </div>
        </div>

    </form>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\PackageRequest::class, '#form-data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/package/edit.blade.php ENDPATH**/ ?>