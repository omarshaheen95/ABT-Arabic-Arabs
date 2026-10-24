

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add roles')): ?>
        <a href="<?php echo e(route(getGuard().'.permission.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            <?php echo e(t('Add Permission')); ?>

        </a>
    <?php endif; ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete permissions')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!"
                       id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>

        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1 mb-2">
            <label><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>">
        </div>
        <div class="col-3 mb-2">
            <div class="form-group">
                <label><?php echo e(t('Guard')); ?>:</label>
                <select name="guard_name" class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Guard')); ?>" data-allow-clear="true">
                    <option></option>
                    <?php $__currentLoopData = sysGuards(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($guard); ?>"><?php echo e(camelCaseText($guard)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-4 mb-2">
            <div class="form-group">
                <label><?php echo e(t('Group')); ?>:</label>
                <select name="group" class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select Group')); ?>" data-allow-clear="true">
                    <option></option>
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group); ?>"><?php echo e(camelCaseText($group)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('Permission')); ?></th>
                <th class="text-start"><?php echo e(t('Guard Name')); ?></th>
                <th class="text-start"><?php echo e(t('Group')); ?></th>
                <th class="text-start"><?php echo e(t('Roles Count')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = "<?php echo e(route(getGuard().'.permission.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route(getGuard().'.permission.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'guard_name', name: 'guard_name'},
            {data: 'group', name: 'group'},
            {data: 'roles_count', name: 'roles_count'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/role_and_permission/permission/index.blade.php ENDPATH**/ ?>