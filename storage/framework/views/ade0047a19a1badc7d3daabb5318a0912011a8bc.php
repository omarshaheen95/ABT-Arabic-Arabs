<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('actions'); ?>

    <a class="btn btn-secondary  text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Causer')); ?> :</label>
            <select name="causer_type" class="form-select" data-control="select2" data-allow-clear="true" data-placeholder="<?php echo e(t('Select Type')); ?>">
                <option></option>
                <?php $__currentLoopData = $causers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name=>$causer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($causer); ?>"><?php echo e(t($name)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Causer ID')); ?>:</label>
            <input type="text"  name="causer_id" class="form-control" placeholder="<?php echo e(t('Causer ID')); ?>">
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
            <input type="text"  name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>">
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text"  name="email" class="form-control" placeholder="<?php echo e(t('Email')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Subject')); ?> :</label>
            <select name="subject_type" class="form-select" data-control="select2" data-allow-clear="true" data-placeholder="<?php echo e(t('Select Type')); ?>">
                <option></option>
                <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($model); ?>"><?php echo e(t(class_basename($model))); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Subject ID')); ?>:</label>
            <input type="text"  name="subject_id" class="form-control" placeholder="<?php echo e(t('Subject ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Type')); ?> :</label>
            <select name="type" class="form-select" data-control="select2" data-allow-clear="true" data-placeholder="<?php echo e(t('Select Type')); ?>">
                <option></option>
                <option value="created"><?php echo e(t('Created')); ?></option>
                <option value="updated"><?php echo e(t('Update')); ?></option>
                <option value="deleted"><?php echo e(t('Delete')); ?></option>
                <option value="other"><?php echo e(t('Other')); ?></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Date Range')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" value="" placeholder="<?php echo e(t('Pick date rage')); ?>" id="date_range_picker"/>
            <input type="hidden" name="date_start" id="start_date_range_picker" />
            <input type="hidden" name="date_end" id="end_date_range_picker" />
        </div>
        <!--Actions-->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e(t('Activity Logs')); ?>

    </li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('Causer ')); ?></th>
                <th class="text-start"><?php echo e(t('Subject')); ?></th>
                <th class="text-start"><?php echo e(t('Type')); ?></th>
                <th class="text-start"><?php echo e(t('Action Date')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = '<?php echo e(route("manager.activity-log.delete")); ?>';
        var TABLE_URL = "<?php echo e(route('manager.activity-log.index')); ?>";

        var TABLE_COLUMNS  = [
            {data: 'id', name: 'id'},
            {data: 'causer', name: 'causer'},
            {data: 'subject', name: 'subject'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions'}
        ];
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script>
        initializeDateRangePicker()
    </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/activity-log/index.blade.php ENDPATH**/ ?>