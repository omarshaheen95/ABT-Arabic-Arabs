
<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form class="kt-form kt-form--fit kt-margin-b-20" action="<?php echo e(route('teacher.report.usage_report')); ?>" id="filter">
        <?php echo e(csrf_field()); ?>

        <div class="row gap-3">
            <div class="col-12">
                <label class="form-label"><?php echo e(t('Grade')); ?>:</label>
                <select class="form-select grade" data-placeholder="<?php echo e(t('Select grade')); ?>" data-control="select2" data-allow-clear="true" name="grades[]" multiple id="grades">
                    <option value="all" selected><?php echo e(t('All')); ?></option>
                    <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($grade->id); ?>" selected><?php echo e($grade->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-12">
                <label class="mb-2"><?php echo e(t('Sections')); ?>:</label>
                <select class="form-select sections" data-placeholder="<?php echo e(t('Select sections')); ?>" data-control="select2" data-allow-clear="true" name="sections[]" multiple id="sections">
                    <option value="all" selected><?php echo e(t('All')); ?></option>
                    <?php $__currentLoopData = teacherSections(Auth::guard('teacher')->user()->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option selected value="<?php echo e($section); ?>"><?php echo e($section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-12">
                <label class="mb-2"><?php echo e(t('Date Range')); ?> :</label>
                <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" id="date_range"/>
                <input type="hidden" name="start_date" id="start_date_range" />
                <input type="hidden" name="end_date" id="end_date_range" />
            </div>
            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary"><?php echo e(t('Get Report')); ?></button>&nbsp;
            </div>
        </div>

    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        initializeDateRangePicker('date_range')
        onSelectAllClick('grades')
        onSelectAllClick('sections')
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/report/pre_usage_report.blade.php ENDPATH**/ ?>