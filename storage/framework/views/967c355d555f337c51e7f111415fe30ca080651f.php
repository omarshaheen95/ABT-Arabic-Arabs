<?php $__env->startSection('title',$title); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('actions'); ?>

    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(__('auth_log.Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export login sessions')): ?>
                <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('manager.login_sessions.export')); ?>')"><?php echo e(__('auth_log.Export')); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="E.g: 4590"/>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.User ID')); ?>:</label>
            <input type="text"  name="model_id" class="form-control kt-input" placeholder="E.g: 45">
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.Name')); ?>:</label>
            <input type="text" name="name" class="form-control kt-input" placeholder="<?php echo e(__('auth_log.Name')); ?>">
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.Email')); ?>:</label>
            <input type="text" name="email" class="form-control kt-input" placeholder="<?php echo e(__('auth_log.Email')); ?>">
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="form-group">
                <label class="mb-1"><?php echo e(__('auth_log.Type')); ?>:</label>
                <select class="form-select" data-control="select2" data-allow-clear="true" name="model_type" data-placeholder="<?php echo e(__('auth_log.Select Type')); ?>">
                    <option></option>
                    <?php $__currentLoopData = ['Manager', 'School', 'Teacher', 'Supervisor', 'User', 'Unknown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type); ?>"><?php echo e(__('auth_log.'.class_basename($type))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="form-group">
                <label class="mb-1"><?php echo e(__('auth_log.Status')); ?>:</label>
                <select class="form-select" data-control="select2" data-allow-clear="true" name="status" data-placeholder="<?php echo e(__('auth_log.Select Status')); ?>">
                    <option></option>
                    <?php $__currentLoopData = ['success' => __('auth_log.Success'), 'failed' => __('auth_log.Failed'), 'lockout' => __('auth_log.Lockout'), 'logout' => __('auth_log.Logout')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="form-group">
                <label class="mb-1"><?php echo e(__('auth_log.Guard')); ?>:</label>
                <select class="form-select" data-control="select2" data-allow-clear="true" name="login_guard" data-placeholder="<?php echo e(__('auth_log.Select Guard')); ?>">
                    <option></option>
                    <?php $__currentLoopData = ['manager', 'school', 'teacher', 'supervisor', 'web']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($guard); ?>"><?php echo e(__('auth_log.'.$guard)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.IP Address')); ?>:</label>
            <input type="text" name="ip" class="form-control kt-input" placeholder="E.g: 192.168.1.1">
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.Entered Identifier')); ?>:</label>
            <input type="text" name="identifier" class="form-control kt-input" placeholder="<?php echo e(__('auth_log.Entered Identifier')); ?>">
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <label class="mb-1"><?php echo e(__('auth_log.Date Range')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="<?php echo e(__('auth_log.Pick date range')); ?>" id="date_range"/>
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
                            <th class="text-start"><?php echo e(__('auth_log.User')); ?></th>
                            <th class="text-start"><?php echo e(__('auth_log.Type')); ?></th>
                            <th class="text-start"><?php echo e(__('auth_log.Status')); ?></th>
                            <th class="text-start"><?php echo e(__('auth_log.IP Address')); ?></th>
                            <th class="text-start"><?php echo e(__('auth_log.Data')); ?></th>
                            <th class="text-start"><?php echo e(__('auth_log.Time')); ?></th>
                        </tr>
                        </thead>
                    </table>
                </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script>
        var TABLE_URL = "<?php echo e(route('manager.login_sessions.index')); ?>";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'user', name: 'user'},
            {data: 'model_type', name: 'model_type'},
            {data: 'status', name: 'status'},
            {data: 'ip', name: 'ip'},
            {data: 'data', name: 'data'},
            {data: 'created_at', name: 'created_at'},
        ];

        initializeDateRangePicker('date_range')
    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/login_sessions/index.blade.php ENDPATH**/ ?>