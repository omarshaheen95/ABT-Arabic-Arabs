<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hide stories')): ?>
        <a href="<?php echo e(route('manager.hidden_story.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            <?php echo e(t('Hide Story')); ?>

        </a>
    <?php endif; ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export hidden stories')): ?>
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('<?php echo e(route('manager.hidden_story.export')); ?>')"><?php echo e(t('Export')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete hidden stories')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>

        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('School')); ?>:</label>
            <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select School')); ?>">
                <option></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Grade')); ?> :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                    data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($grade); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Story')); ?> :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Story')); ?>" data-allow-clear="true">
                <option></option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Hidden at')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
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
                <th class="text-start"><?php echo e(t('School')); ?></th>
                <th class="text-start"><?php echo e(t('Grade')); ?></th>
                <th class="text-start"><?php echo e(t('Story')); ?></th>
                <th class="text-start"><?php echo e(t('Hidden At')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>


<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        var DELETE_URL = "<?php echo e(route('manager.hidden_story.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.hidden_story.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'grade', name: 'grade'},
            {data: 'story', name: 'story'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions'},
        ];
        initializeDateRangePicker();
        getStoriesByGrade();

    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/hidden_story/index.blade.php ENDPATH**/ ?>