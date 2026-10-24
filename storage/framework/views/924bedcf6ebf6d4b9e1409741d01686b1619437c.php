
<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <a href="<?php echo e(route('manager.year.index')); ?>" class="text-muted">
            <?php echo e(t('Years')); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form class="form" id="form_data"
          action="<?php echo e(isset($year) ? route('manager.year.update', $year->id):route('manager.year.store')); ?>"
          method="post">
        <?php echo csrf_field(); ?>
        <?php if(isset($year)): ?>
            <input type="hidden" name="_method" value="PATCH"/>
        <?php endif; ?>
        <div class="form-group row">
            <div class="col-lg-4 mb-2">
                <label class="form-label mb-1"><?php echo e(t('Year Name')); ?> </label>
                <input name="name" type="text" placeholder="<?php echo e(t('Year Name')); ?>"
                       class="form-control"
                       value="<?php echo e(isset($year) ? $year->name : old("name")); ?>"
                />
            </div>
            <div class="col-lg-4 mb-2">
                <label class="form-label mb-1"><?php echo e(t('Slug')); ?> </label>
                <input name="slug" type="text" placeholder="<?php echo e(t('Slug')); ?>"
                       class="form-control"
                       value="<?php echo e(isset($year) ? $year->slug : old("slug")); ?>"
                />
            </div>
        </div>
        <div class="col-md-6 mb-2 mt-2">
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="1" name="default" id="flexCheckDefault"
                    <?php echo e(isset($year) && $year->default == 1 ? 'checked':''); ?>/>
                <label class="form-check-label" for="flexCheckDefault">
                    <?php echo e(t('Default Year')); ?>

                </label>
            </div>
        </div>
        <div class="row my-5">
            <div class="separator separator-content my-4"></div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mr-2"><?php echo e(t('Submit')); ?></button>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\YearRequest::class, '#form_data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/year/edit.blade.php ENDPATH**/ ?>