<?php $__env->startSection('title'); ?>
    <?php echo e(t('Managers')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted"><?php echo e(t('Managers')); ?></li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add managers')): ?>
        <a href="<?php echo e(route('manager.manager.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm">
            <i class="la la-plus"></i>
            <?php echo e(t('Add New Manager')); ?>

        </a>
    <?php endif; ?>


    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export managers')): ?>
                <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('manager.manager.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit managers')): ?>
                <li><a class="dropdown-item text-warning" href="#!"
                       data-filtered-action="<?php echo e(route('manager.manager.force-password-change')); ?>"
                       data-confirm="<?php echo e(t('Password change will be enforced on every account matching the current filters. Continue?')); ?>"><?php echo e(t('Force Password Change')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete managers')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('ID')); ?>:</label>
            <input type="text" id="id" name="id" class="form-control kt-input" placeholder="E.g: 4590">
        </div>

        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
            <input type="text" id="name" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Name')); ?>">
        </div>

        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text" id="email" name="email" class="form-control kt-input" placeholder="<?php echo e(t('Email')); ?>">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Status')); ?>:</label>
            <select name="active" class="form-control form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-hide-search="true" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Active')); ?></option>
                <option value="2"><?php echo e(t('Not Active')); ?></option>
            </select>
        </div>
    </div>

<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
    <!--begin::Hidden Form-->
    <form action="" method="post" id="filter_action" style="display: none;">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="row_id[]" id="row_id">
    </form>
    <div class="row">

        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('ID')); ?></th>
                <th class="text-start"><?php echo e(t('Name')); ?></th>
                <th class="text-start"><?php echo e(t('Email')); ?></th>
                <th class="text-start"><?php echo e(t('Role')); ?></th>
                <th class="text-start"><?php echo e(t('Last Login')); ?></th>
                <th class="text-start"><?php echo e(t('Status')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = "<?php echo e(route('manager.manager.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.manager.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active', name: 'active'},
            {data: 'actions', name: 'actions'}
        ];


    </script>
    <script src=<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/managers/index.blade.php ENDPATH**/ ?>