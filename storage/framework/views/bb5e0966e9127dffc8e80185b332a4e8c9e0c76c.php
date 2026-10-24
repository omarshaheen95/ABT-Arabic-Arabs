<?php $__env->startSection('title'); ?>
    <?php echo e(t('Achievement Levels')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add achievement levels')): ?>
        <a href="<?php echo e(route('manager.achievement_levels.create')); ?>" class="btn btn-primary font-weight-bolder">
            <i class="la la-plus"></i><?php echo e(t('Add Achievement Level')); ?></a>
    <?php endif; ?>

    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete achievement levels')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Name')); ?>">
        </div>
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Required Points')); ?>:</label>
            <input type="number" name="required_points" class="form-control direct-search" placeholder="<?php echo e(t('Required Points')); ?>">
        </div>
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Description')); ?>:</label>
            <input type="text" name="description" class="form-control direct-search" placeholder="<?php echo e(t('Description')); ?>">
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e(t('Achievement Levels')); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th class="text-start"></th>
                    <th class="text-start"><?php echo e(t('Name')); ?></th>
                    <th class="text-start"><?php echo e(t('Required Points')); ?></th>
                    <th class="text-start"><?php echo e(t('Badge Icon')); ?></th>
                    <th class="text-start"><?php echo e(t('Description')); ?></th>
                    <th class="text-start"><?php echo e(t('Actions')); ?></th>
                </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = '<?php echo e(route("manager.achievement_levels.destroy")); ?>';
        var TABLE_URL = "<?php echo e(route('manager.achievement_levels.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'required_points', name: 'required_points'},
            {data: 'badge_icon', name: 'badge_icon'},
            {data: 'description', name: 'description'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/achievement_level/index.blade.php ENDPATH**/ ?>