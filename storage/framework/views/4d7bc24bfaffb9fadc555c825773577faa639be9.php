<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(isset($school) ? route('manager.school.update', $school->id): route('manager.school.store')); ?>"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($school)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div><?php echo e(t('Image')); ?></div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)">

                    <?php if(isset($school) && $school->logo ): ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e(asset($school->logo)); ?>)"></div>

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
            </div>
            <!--end::Image input-->

            <div class="row">



                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="name" class="form-label"><?php echo e(t('Name')); ?></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>"
                               value="<?php echo e(isset($school) ? $school->name : old("name")); ?>" required>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="Email" class="form-label"><?php echo e(t('Email')); ?></label>
                        <input type="email" id="Email" name="email" class="form-control" placeholder="<?php echo e(t('Email')); ?>"
                               value="<?php echo e(isset($school) ? $school->email : old("email")); ?>" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <?php echo $__env->make('components.password-fields', [
                        'confirm' => false,
                        'col' => 'col-lg-4 mb-2',
                        'required' => !isset($school),
                        'simple' => true,
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('components.force-password-change', ['row' => $school ?? null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="mobile" class="form-label"><?php echo e(t('Mobile')); ?></label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="<?php echo e(t('Mobile')); ?>"
                               value="<?php echo e(isset($school) ? $school->mobile : old("mobile")); ?>" required>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="mobile" class="form-label"><?php echo e(t('Website')); ?></label>
                        <input type="tel" name="website" class="form-control" placeholder="<?php echo e(t('Website')); ?>"
                               value="<?php echo e(isset($school) ? $school->website : old("website")); ?>" required>
                    </div>
                </div>

                <div class="col-2 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" <?php echo e(isset($school) && $school->active ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Active')); ?>

                        </label>
                    </div>
                </div>

                <div class="col-2 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="student_login"
                               id="flexCheckDefault" <?php echo e(isset($school) && $school->student_login ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Student Login')); ?>

                        </label>
                    </div>
                </div>

                <div class="col-2 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="suspend_student_login"
                               id="suspendStudentLogin" <?php echo e(isset($school) && $school->suspend_student_login ? 'checked':''); ?>/>
                        <label class="form-check-label text-danger" for="suspendStudentLogin">
                            <?php echo e(t('Suspend Student Login')); ?>

                        </label>
                    </div>
                </div>
            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(isset($school)?t('Update'):t('Submit')); ?></button>
                </div>
            </div>
        </div>

    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\SchoolRequest::class, '#form-data'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/school/edit.blade.php ENDPATH**/ ?>