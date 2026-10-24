
<?php $__env->startSection('title',$title); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information"
          action="<?php echo e(route('teacher.stories_records.update', $user_record->id)); ?>" method="post">
        <?php echo csrf_field(); ?>
        <?php if(isset($user_record)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row gap-3">
            <div class="form-group row  align-items-center">
                <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Record answer')); ?></label>
                <div class="col-lg-9 d-flex align-items-center">
                    <audio src="<?php echo e(asset($user_record->record)); ?>" controls></audio>

                    <div class="ms-auto d-flex flex-column" style="width: 130px">
                        <div><?php echo e(t('Mark')); ?>:</div>
                        <input class="form-control" name="mark" type="number" placeholder="<?php echo e(t('Mark')); ?>"
                               value="<?php echo e($user_record->mark); ?>" min="0" max="10">
                    </div>

                </div>

            </div>
            <div class="col-12 row align-items-center">
                <label class="col-3"><?php echo e(t('Status')); ?></label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="pending" <?php if($user_record->status == 'pending'): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="section"><?php echo e(t(ucfirst('Waiting list'))); ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="corrected"  <?php if($user_record->status == 'corrected'): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="section"><?php echo e(t(ucfirst('Marking Completed'))); ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" value="returned" <?php if($user_record->status == 'returned'): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="section"><?php echo e(t(ucfirst('Send back'))); ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row align-items-center">
                <label class="col-3"><?php echo e(t('Show as model')); ?></label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="0"  <?php if(!$user_record->approved): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="section"><?php echo e(t('Do not Show as model')); ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="1" <?php if($user_record->approved): ?> checked <?php endif; ?>>
                            <label class="form-check-label" for="section"><?php echo e(t('Show as model')); ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator mt-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary"><?php echo e(t('Save')); ?></button>&nbsp;
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\UpdateUserRecordRequest::class, '#form_information'); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/stories_records/show.blade.php ENDPATH**/ ?>