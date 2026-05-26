<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information"
          action="<?php echo e(route('manager.hidden_story.store')); ?>" method="post">
        <?php echo csrf_field(); ?>

        <div class="row">
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('School')); ?></label>
                <select class="form-select" data-control="select2"
                        data-placeholder="<?php echo e(t('Select School')); ?>"
                        data-allow-clear="true" name="school_id" id="school_id">
                    <option></option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Grade')); ?></label>
                <select class="form-select assignment_grade" data-control="select2"
                        data-placeholder="<?php echo e(t('Select grade')); ?>"
                        data-allow-clear="true" name="grade" id="assignment_grade">
                    <option></option>
                    <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($grade); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group col-6 mb-2">
                <label class="form-label"><?php echo e(t('Story')); ?></label>
                <select class="form-select" data-control="select2" multiple
                        data-placeholder="<?php echo e(t('Select Story')); ?>"
                        data-allow-clear="true"
                        name="story_id[]" id="assignment_lesson">
                    <option></option>
                </select>
            </div>

            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary"><?php echo e(t('Save')); ?></button>&nbsp;
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('assets_v1/js/custom.js')); ?>"></script>

    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\HiddenStoryRequest::class, '#form_information'); ?>


    <script>

        onSelectAllClick('section')
        getStoriesByGrade();

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/hidden_story/create.blade.php ENDPATH**/ ?>