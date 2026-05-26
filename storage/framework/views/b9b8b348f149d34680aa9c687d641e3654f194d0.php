
<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add years')): ?>
        <a href="<?php echo e(route('manager.year.create')); ?>" class="btn btn-primary font-weight-bolder">
            <i class="la la-plus"></i><?php echo e(t('Create Year')); ?></a>
    <?php endif; ?>

    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete years')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('filter'); ?>
    <div class="col-lg-3 mb-lg-0 mb-6">
        <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
        <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Name')); ?>"
               data-col-index="0"/>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <table class="table table-row-bordered gy-5" id="datatable">
        <thead>
        <tr class="fw-semibold fs-6 text-gray-800">
            <th class="text-start"></th>
            <th class="text-start">#</th>
            <th class="text-start"><?php echo e(t('Name')); ?></th>
            <th class="text-start"><?php echo e(t('Default')); ?></th>
            <th class="text-start"><?php echo e(t('Actions')); ?></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var DELETE_URL = "<?php echo e(route('manager.year.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.year.index')); ?>";
        var EXPORT_URL = null;
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'default', name: 'default'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v<?php echo e(time()); ?>"></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/year/index.blade.php ENDPATH**/ ?>