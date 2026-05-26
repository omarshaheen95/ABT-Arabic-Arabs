

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(isset($lesson) ? route('manager.lesson.update', $lesson->id): route('manager.lesson.store')); ?>"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($lesson)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div><?php echo e(t('Image')); ?></div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/manager_assets/media/svg/avatars/blank.svg)">

                    <?php if(isset($lesson) ): ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e($lesson->getFirstMediaUrl('imageLessons')); ?>)"></div>

                    <?php else: ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    <?php endif; ?>

                    <!--begin::Edit button-->
                    <label
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Change avatar">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
                        <input type="hidden" name="avatar_remove"/>
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="cancel"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span
                        class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="remove"
                        data-bs-toggle="tooltip"
                        data-bs-dismiss="click"
                        title="Remove avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Remove button-->
                </div>
            </div>
            <!--end::Image input-->

            <div class="row">
                    <div class="col-6 mb-2">
                        <div class="form-group">
                            <label for="name" class="form-label"><?php echo e(t('Name')); ?></label>
                            <input type="text" id="" name="name" class="form-control"
                                   placeholder="<?php echo e(t('Name')); ?>"
                                   value="<?php echo e(isset($lesson) ? $lesson->name : old("name")); ?>"
                                   required>
                        </div>
                    </div>


                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="grade" class="form-label"><?php echo e(t('Grade')); ?></label>
                        <select name="grade_id" class="form-select"  data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>" data-allow-clear="true">
                            <option></option>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e(isset($lesson) && $lesson->grade_id == $grade->id ? 'selected':''); ?> value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="lesson_type" class="form-label"><?php echo e(t('Skill')); ?></label>
                        <select name="lesson_type" class="form-select"  data-control="select2" data-placeholder="<?php echo e(t('Select Skill')); ?>" data-allow-clear="true">
                            <option></option>
                            <?php $__currentLoopData = lessonTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e(isset($lesson) && $lesson->lesson_type == $type ? 'selected':''); ?> value="<?php echo e($type); ?>"><?php echo e(t($type)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="section_type" class="form-label"><?php echo e(t('Section Type')); ?></label>
                        <select name="section_type" class="form-select"  data-control="select2" data-placeholder="<?php echo e(t('Select Section Type')); ?>" data-allow-clear="true">
                            <option value="" selected><?php echo e(t('General')); ?></option>
                            <?php $__currentLoopData = ['informative','literary']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e(isset($lesson) && $lesson->section_type == $section_type ? 'selected':''); ?> value="<?php echo e($section_type); ?>"><?php echo e(t($section_type)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>
                </div>
                <div class="col-4 mb-2" id="levels">
                    <div class="form-group">
                        <label for="level" class="form-label"><?php echo e(t('Level')); ?></label>
                        <select name="level" class="form-select"  data-control="select2" data-placeholder="<?php echo e(t('Select Level')); ?>" data-allow-clear="true">
                            <option value=""></option>
                            <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e(isset($lesson) && !is_null($lesson->level) && $lesson->level == $value ? 'selected':''); ?> value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="ordered" class="form-label"><?php echo e(t('Lesson Order')); ?></label>
                        <input type="number" id="" name="ordered" class="form-control"
                               value="<?php echo e(isset($lesson) ? $lesson->ordered : 1); ?>">
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="color" class="form-label"><?php echo e(t('Lesson Color')); ?></label>
                        <input type="color" id="" name="color" class="form-control" style="height: 43px"
                               value="<?php echo e(isset($lesson) ? $lesson->color : 1); ?>">
                    </div>
                </div>



                <div class="col-3 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" <?php echo e(isset($lesson) && $lesson->active ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Active')); ?>

                        </label>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(isset($lesson)?t('Update'):t('Save')); ?></button>
                </div>
            </div>
        </div>

    </form>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\LessonRequest::class, '#form-data'); ?>

    <script>
        let lessonTypes = ['grammar', 'dictation', 'rhetoric'];

        <?php if(isset($lesson)): ?>
        showOrHideLevels("<?php echo e($lesson->lesson_type); ?>") //on start
        <?php else: ?>
        $('#levels').addClass('d-none')
        <?php endif; ?>

        $('select[name="lesson_type"]').on('change',function () {
            let value = $(this).val()
            showOrHideLevels(value)
        })

        function showOrHideLevels(value) {
            if (lessonTypes.includes(value)){
                $('#levels').removeClass('d-none')
            }else {
                $('#levels').addClass('d-none')
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/lesson/edit.blade.php ENDPATH**/ ?>