<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e(t('Teacher Report')); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <form action="<?php echo e($url); ?>" id="filter">
            <?php echo e(csrf_field()); ?>

            <div class="row kt-margin-b-20">

                <div class="col-lg-12 mb-2">
                    <label><?php echo e(t('Teachers')); ?> :</label>
                    <select id="teachers" name="teacher_ids[]" class="form-select" data-control="select2"
                            data-placeholder="<?php echo e(t('Select Teacher')); ?>"
                            data-allow-clear="true" multiple>
                        <option value="all"><?php echo e(t('All')); ?></option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
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
                    <div class="input-group">
                        <input autocomplete="disabled" class="form-control form-control-solid" id="date_range"
                               name="date_range_report" value="" placeholder="<?php echo e(t('Pick date range')); ?>"/>
                        <button class="btn btn-light-danger" type="button" id="clear_date_range"
                                title="<?php echo e(t('Clear')); ?>">&times;</button>
                    </div>
                    <div class="form-text"><?php echo e(t('Leave empty to cover the whole year')); ?></div>
                    
                    <input type="hidden" name="start_date" id="start_date_range" value=""/>
                    <input type="hidden" name="end_date" id="end_date_range" value=""/>
                </div>

                <div class="col-lg-12 mb-2">
                    
                    <input type="hidden" name="include_archived" value="0"/>
                    <div class="form-check form-check-custom">
                        <input class="form-check-input" type="checkbox" value="1" id="include_archived"
                               name="include_archived" checked/>
                        <label class="form-check-label ms-2" for="include_archived">
                            <?php echo e(t('Include archived students')); ?>

                        </label>
                    </div>
                    <div class="form-text"><?php echo e(t('Archived students kept their activity; unticking hides it from the report.')); ?></div>
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
    <script>
        $(document).ready(function () {

            // No default range: the report covers the whole year until the user picks one.
            initializeDateRangePicker('date_range')

            $('#clear_date_range').on('click', function () {
                $('#date_range').val('');
                $('#start_date_range').val('');
                $('#end_date_range').val('');
            });

            onSelectAllClick('teachers')

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/reports/teacher_report/pre_teacher_report.blade.php ENDPATH**/ ?>