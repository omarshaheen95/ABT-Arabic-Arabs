

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <a href="<?php echo e(route('school.teacher.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-plus"></i>
        <?php echo e(t('Add Teacher')); ?>

    </a>
    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('school.teacher.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Name')); ?>">
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="<?php echo e(t('Email')); ?>">
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('Mobile')); ?>:</label>
            <input type="text" name="mobile" class="form-control" placeholder="<?php echo e(t('Mobile')); ?>">
        </div>

        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Students')); ?> :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Has students')); ?></option>
                <option value="2"><?php echo e(t('Has no students')); ?></option>
                <option value="3"><?php echo e(t('Has active students')); ?></option>
                <option value="4"><?php echo e(t('Has inactive students')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Approval')); ?> :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Approved')); ?></option>
                <option value="2"><?php echo e(t('Under review')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Activation')); ?> :</label>
            <select name="active" id="status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Active')); ?></option>
                <option value="2"><?php echo e(t('Non-Active')); ?></option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Activation Date')); ?>:</label>
            <input id="active_to" class="form-control " placeholder="<?php echo e(t('Select Activation Date')); ?>">
            <input type="hidden" id="start_active_to" name="start_active_to" value="">
            <input type="hidden" id="end_active_to" name="end_active_to" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Login Date')); ?>:</label>
            <input id="login_at" class="form-control " placeholder="<?php echo e(t('Select Login Date')); ?>">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
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
                <th class="text-start"><?php echo e(t('Teacher')); ?></th>
                <th class="text-start"><?php echo e(t('Status')); ?></th>
                <th class="text-start"><?php echo e(t('Active')); ?></th>
                <th class="text-start"><?php echo e(t('Students Count')); ?></th>
                <th class="text-start"><?php echo e(t('Last login')); ?></th>
                <th class="text-start"><?php echo e(t('Active To')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = "<?php echo e(route('school.teacher.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('school.teacher.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'users_count', name: 'users_count'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active_to', name: 'active_to'},
            {data: 'actions', name: 'actions'}
        ];

    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('school.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/teacher/index.blade.php ENDPATH**/ ?>