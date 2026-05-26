<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e(t('Students')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title',$title); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <form action="<?php echo e($url); ?>" id="filter">
            <?php echo e(csrf_field()); ?>

            <div class="row kt-margin-b-20">
                <?php if(guardIs('supervisor')): ?>
                    <div class="col-lg-12 mb-2">
                        <label><?php echo e(t('Teachers')); ?> :</label>
                        <select id="teachers" name="teacher_id" class="form-select grade" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Teacher')); ?>"
                                data-allow-clear="true">
                            <option></option>
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-lg-12 mb-2">
                    <label><?php echo e(t('Grade')); ?> :</label>
                    <select id="grades" name="grades[]" class="form-select grade" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Grade')); ?>"
                            data-allow-clear="true" multiple>
                        <option value="all"><?php echo e(t('All')); ?></option>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label"><?php echo e(t('Year')); ?>:</label>
                    <select class="form-select" data-placeholder="<?php echo e(t('Select Year')); ?>" data-control="select2"
                            data-allow-clear="true" name="year_id" id="year_id">
                        <option></option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-4 mb-2">
                    <label><?php echo e(t('Select date')); ?> :</label>
                    <input autocomplete="disabled" class="form-control form-control-solid" id="date_range"
                           name="date_range_report" value="" placeholder="<?php echo e(t('Pick date range')); ?>"/>
                    <input type="hidden" name="start_date" id="start_date_range"
                           value="<?php echo e(isset($date_range['start']) ? $date_range['start']:''); ?>"/>
                    <input type="hidden" name="end_date" id="end_date_range"
                           value="<?php echo e(isset($date_range['end']) ? $date_range['end']:''); ?>"/>
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
        $(document).ready(function () {

            initializeDateRangePicker('date_range', ["<?php echo e($date_range['start']); ?>", "<?php echo e($date_range['end']); ?>"])


            onSelectAllClick('grades')

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/reports/usage_report/pre_usage_report.blade.php ENDPATH**/ ?>