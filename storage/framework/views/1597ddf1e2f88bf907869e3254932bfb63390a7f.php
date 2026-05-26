

<?php $__env->startSection('content'); ?>
    <?php $__env->startPush('breadcrumb'); ?>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('manager.students_works.index')); ?>"><?php echo e(t('Students works')); ?></a>
        </li>
        <li class="breadcrumb-item">
            <?php echo e($title); ?>

        </li>
    <?php $__env->stopPush(); ?>
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"> <?php echo e($title); ?></h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="<?php echo e(route('manager.students_works.update', $user_lesson->id)); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Attach Writing Answer')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <?php if(isset($user_lesson) && !is_null($user_lesson->attach_writing_answer)): ?>
                                <label class="mb-2">
                                    <a href="<?php echo e($user_lesson->attach_writing_answer); ?>" class="kt-font-warning"
                                       target="_blank"><?php echo e(t('Browse')); ?></a>
                                </label>
                            <?php endif; ?>
                            <input type="file" name="attach_writing_answer"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Writing answer')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <textarea class="form-control"
                                      name="writing_answer"><?php echo e($user_lesson->writing_answer); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Writing mark')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" name="writing_mark" type="number"
                                   value="<?php echo e($user_lesson->writing_mark); ?>">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Reading answer')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <?php if(!is_null($user_lesson->getOriginal('reading_answer'))): ?>
                                <audio class="w-100" controls src="<?php echo e($user_lesson->reading_answer); ?>"></audio>
                            <?php endif; ?>
                            <input class="form-control" name="reading_answer" type="file">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Reading mark')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" name="reading_mark" type="number"
                                   value="<?php echo e($user_lesson->reading_mark); ?>">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Feedback')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <textarea class="form-control"
                                      name="teacher_message"><?php echo e($user_lesson->teacher_message); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Status')); ?></label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="d-flex gap-2 mt-3">
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           <?php if($user_lesson->status == 'pending'): ?> checked <?php endif; ?> type="radio" required
                                           value="pending"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        <?php echo e(t(ucfirst('Waiting list'))); ?>

                                    </label>
                                </div>
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           <?php if($user_lesson->status == 'corrected'): ?> checked <?php endif; ?> type="radio" required
                                           value="corrected"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        <?php echo e(t(ucfirst('Marking Completed'))); ?>

                                    </label>
                                </div>
                                <div
                                    class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input"
                                           <?php if($user_lesson->status == 'returned'): ?> checked <?php endif; ?> type="radio" required
                                           value="returned"
                                           name="status" id="flexRadioLg"/>
                                    <label class="form-check-label" for="flexRadioLg">
                                        <?php echo e(t(ucfirst('Send back'))); ?>

                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row my-5">
                        <div class="separator separator-content my-4"></div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit"
                                    class="btn btn-primary mr-2"><?php echo e(t('Save')); ?></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\UserLessonRequest::class, '#form_information'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/students_works/show.blade.php ENDPATH**/ ?>