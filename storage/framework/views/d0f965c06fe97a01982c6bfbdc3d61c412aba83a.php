
<?php $__env->startSection('title',$title); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
          action="<?php echo e(route('school.update-profile')); ?>"
          method="post">
        <?php echo csrf_field(); ?>


        <div class="d-flex flex-column align-items-center mb-10">
            <label class="form-label mb-2"><?php echo e(t('Logo')); ?></label>
            <div class="">

                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(<?php echo e(asset('assets_v1/media/svg/avatars/blank.svg')); ?>)">

                    <?php if($school->logo): ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e($school->logo); ?>)"></div>
                    <?php else: ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e(asset('assets_v1/media/svg/avatars/blank.svg')); ?>)"></div>
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
                        <input type="file" name="logo" accept=".png, .jpg, .jpeg"/>
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
                <!--end::Image input-->


            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label"><?php echo e(t('Name')); ?></label>
                <input class="form-control" name="name" type="text"
                       value="<?php echo e($school->name); ?>">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label"><?php echo e(t('Email')); ?></label>
                <input class="form-control" name="email" type="text"
                       value="<?php echo e($school->email); ?>">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label"><?php echo e(t('Phone')); ?></label>
                <input class="form-control" name="phone" type="text"
                       value="<?php echo e($school->phone); ?>">
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label"><?php echo e(t('Website')); ?></label>
                <input class="form-control" name="website" type="text"
                       value="<?php echo e($school->website); ?>">
            </div>

        </div>
        <div class="row my-5">
            <div class="separator separator-content my-4"></div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit"
                        class="btn btn-primary"><?php echo e(t('Update')); ?></button>&nbsp;
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\School\SchoolProfileRequest::class, '#form_information'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('school.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/profile/profile.blade.php ENDPATH**/ ?>