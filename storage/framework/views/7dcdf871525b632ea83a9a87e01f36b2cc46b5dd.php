

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e(t('Students')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title',$title); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <form  action="<?php echo e(route('supervisor.report.usage_report')); ?>" id="filter">
            <?php echo e(csrf_field()); ?>

            <div class="row kt-margin-b-20">
                <div class="col-lg-12 mb-2">
                    <label class="mb-2"><?php echo e(t('Grade')); ?> :</label>
                    <select id="grades" name="grades[]" class="form-select grade" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                            data-allow-clear="true" multiple>
                        <option value="all" ><?php echo e(t('All')); ?></option>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-4 mb-2">
                    <label class="mb-2"><?php echo e(t('Select date')); ?> :</label>
                    <input autocomplete="disabled" class="form-control form-control-solid" id="date_range" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" />
                    <input type="hidden" name="start_date" id="start_date_range" />
                    <input type="hidden" name="end_date" id="end_date_range" />
                </div>

                <div class="separator my-4"></div>
                <div class="d-flex justify-content-end">
                    <button type="submit" id="save" class="btn btn-primary"><?php echo e(t('Get Report')); ?></button>&nbsp;
                </div>


            </div>

        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- DataTables -->
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){

            initializeDateRangePicker('date_range')


            onSelectAllClick('grades')

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('supervisor.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/supervisor/report/pre_usage_report.blade.php ENDPATH**/ ?>