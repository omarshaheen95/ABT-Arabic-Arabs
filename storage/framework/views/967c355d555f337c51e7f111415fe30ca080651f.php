

<?php $__env->startSection('title',$title); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>















<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('User ID')); ?>:</label>
            <input type="text"  name="model_id" class="form-control kt-input" placeholder="E.g: 45">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control kt-input" placeholder="<?php echo e(t('Name')); ?>">
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text" name="email" class="form-control kt-input" placeholder="<?php echo e(t('Email')); ?>">
        </div>

        <div class="col-3 mb-2">
            <div class="form-group">
                <label class="mb-2"><?php echo e(t('Type')); ?>:</label>
                <select class="form-select" data-control="select2" data-allow-clear="true" name="model_type" data-placeholder="<?php echo e(t('Select Type')); ?>">
                    <option></option>
                    <?php $__currentLoopData = ['Manager','School','Inspection','Student']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>"><?php echo e(t($type)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Date Range')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" id="date_range" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" />
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
        </div>

    </div>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
                        <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th class="text-start"></th>
                            <th class="text-start"><?php echo e(t('User')); ?></th>
                            <th class="text-start"><?php echo e(t('Type')); ?></th>
                            <th class="text-start"><?php echo e(t('Data')); ?></th>
                            <th class="text-start"><?php echo e(t('Time')); ?></th>
                        </tr>
                        </thead>
                    </table>
                </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/manager/models/general.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        var TABLE_URL = "<?php echo e(route('manager.login_sessions.index')); ?>";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'user', name: 'user'},
            {data: 'model_type', name: 'model_type'},
            {data: 'data', name: 'data'},
            {data: 'created_at', name: 'created_at'},
        ];

        initializeDateRangePicker('date_range')
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>


<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/login_sessions/index.blade.php ENDPATH**/ ?>