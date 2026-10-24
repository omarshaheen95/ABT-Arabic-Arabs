

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teachersTrackingExport')): ?>
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('<?php echo e(route('manager.teacher.tracking_export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <?php endif; ?>

        </ul>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1 mb-2">
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


        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('School')); ?> :</label>
            <select name="school_id" class="form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select School')); ?>" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Students')); ?> :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Has students')); ?></option>
                <option value="2"><?php echo e(t('Has no students')); ?></option>
                <option value="3"><?php echo e(t('Has active students')); ?></option>
                <option value="4"><?php echo e(t('Has inactive students')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Approval')); ?> :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>"
                    data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Approved')); ?></option>
                <option value="2"><?php echo e(t('Under review')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Activation')); ?> :</label>
            <select name="active" id="status" class="form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Active')); ?></option>
                <option value="2"><?php echo e(t('Non-Active')); ?></option>
            </select>
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
                <th class="text-start"><?php echo e(t('Passed tests')); ?></th>
                <th class="text-start"><?php echo e(t('Failed tests')); ?></th>
                <th class="text-start"><?php echo e(t('Pending teaks')); ?></th>
                <th class="text-start"><?php echo e(t('Completed tasks')); ?></th>
                <th class="text-start"><?php echo e(t('Returned tasks')); ?></th>
                <th class="text-start"><?php echo e(t('Last login')); ?></th>
                <th class="text-start"><?php echo e(t('Report')); ?></th>
            </tr>
            </thead>
        </table>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var TABLE_URL = "<?php echo e(route('manager.teacher.tracking')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'passed_tests', name: 'passed_tests'},
            {data: 'failed_tests', name: 'failed_tests'},
            {data: 'pending_tasks', name: 'pending_tasks'},
            {data: 'corrected_tasks', name: 'corrected_tasks'},
            {data: 'returned_tasks', name: 'returned_tasks'},
            {data: 'last_login', name: 'last_login'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/teacher/tracking.blade.php ENDPATH**/ ?>