

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('intl-tel-input-master/build/css/intlTelInput.min.css')); ?>" rel="stylesheet">
    <?php if(app()->getLocale() == 'ar'): ?>
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(isset($user) ? route('school.student.update', $user->id): route('school.student.store')); ?>"
          method="post" class="form" id="form-profile-save" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <?php if(isset($user)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>

        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div><?php echo e(t('Image')); ?></div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)">

                    <?php if(isset($user) && $user->image ): ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e(asset($user->image)); ?>)"></div>

                    <?php else: ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    <?php endif; ?>

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
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
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="cancel"
                          data-bs-toggle="tooltip"
                          data-bs-dismiss="click"
                          title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
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
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Name')); ?></label>
                        <input type="text" name="name" class="form-control name remove_spaces" placeholder="<?php echo e(t('Name')); ?>"
                               value="<?php echo e(isset($user->name) ? $user->name : old("name")); ?>" required>
                    </div>
                </div>
                <div class="col-5 mb-2">
                    <div class="form-group">
                        <label class="form-label mb-1"><?php echo e(t('Email')); ?>:</label>
                        <div class="input-group mb-5">
                            <input dir="ltr" name="email" type="text" placeholder="<?php echo e(t('Email')); ?>"
                                   value="<?php echo e(isset($user->email) ? $user->email : old("email")); ?>"
                                   class="form-control username" aria-describedby="basic-addon1"/>
                            <span class="input-group-text" id="basic-addon1">
                         <a class="p-0 cursor-pointer" id="generateUserName"><i class="fas fa-refresh"></i></a>
                     </span>
                        </div>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Mobile')); ?></label>
                        <input type="text" class="form-control" id="Phone" placeholder="<?php echo e(t('Mobile')); ?>" name="phone"
                               value="<?php echo e(isset($user) ? $user->mobile : old('mobile')); ?>">
                        <input type="hidden" id="country-code" name="country_code"
                               value="<?php echo e(isset($user) ? $user->country_code : old('country_code')); ?>">
                        <input type="hidden" id="short-country" name="short_country"
                               value="<?php echo e(isset($user) ? $user->short_country : old('short_country')); ?>">
                        <input type="hidden" placeholder="الموبايل" name="mobile"
                               id="mobileHidden"
                               value="<?php echo e(isset($user) ? $user->mobile : old('mobile')); ?>"
                               class="form-control form-control-lg">
                        <span id="valid-msg" class="hide">✓ فعال</span>
                        <span id="error-msg" class="hide"></span>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="password" class="form-label"><?php echo e(t('Password')); ?></label>
                        <input type="text" name="password" class="form-control" placeholder="<?php echo e(t('Password')); ?>" <?php if(!$user): ?> value="123456" <?php endif; ?>>
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Teacher')); ?></label>
                        <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="<?php echo e(t('Select Teacher')); ?>">
                            <option></option>
                            <?php if(isset($teachers)): ?>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(isset($user) && optional($user->teacherUser)->teacher_id == $teacher->id ? 'selected':''); ?>><?php echo e($teacher->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Grade')); ?></label>
                        <select class="form-select" name="grade_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="<?php echo e(t('Select Grade')); ?>">
                            <option value="" selected disabled><?php echo e(t('Select Grade')); ?></option>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($grade->id); ?>" <?php echo e(isset($user) && $user->grade_id == $grade->id ? 'selected':''); ?>>
                                    <?php echo e($grade->name); ?></option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Alternative Grade')); ?></label>
                        <select class="form-select" name="alternate_grade_id" data-control="select2"
                                data-allow-clear="true" data-placeholder="<?php echo e(t('Select Alternative Grade')); ?>">
                            <option value="" selected><?php echo e(t('Select Grade')); ?></option>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                    value="<?php echo e($grade->id); ?>" <?php echo e(isset($user) && $user->alternate_grade_id == $grade->id ? 'selected':''); ?>>
                                    <?php echo e($grade->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Year')); ?></label>
                        <select class="form-select" name="year_id" data-control="select2" data-allow-clear="true"
                                data-placeholder="<?php echo e(t('Select Year')); ?>">
                            <option></option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year->id); ?>" <?php echo e(isset($user) && $user->year_id == $year->id ? 'selected':''); ?>><?php echo e($year->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>


                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Section')); ?></label>
                        <input class="form-control" name="section" type="text"
                               value="<?php echo e(isset($user) ? $user->section : old("section")); ?>" placeholder="<?php echo e(t('Section')); ?>">
                    </div>
                </div>


                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Active To')); ?></label>
                        <input class="form-control" name="active_to" type="text"
                               value="<?php echo e(isset($user) ? $user->active_to : old("active_to")); ?>" id="active_to_date" placeholder="<?php echo e(t('Active To')); ?>">
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Gender')); ?></label>
                        <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                                data-placeholder="<?php echo e(t('Select Gender')); ?>">
                            <option></option>
                            <option value="Boy" <?php echo e(isset($user) && $user->gender == 'Boy' ? 'selected':''); ?>><?php echo e(t('Boy')); ?></option>
                            <option value="Girl" <?php echo e(isset($user) && $user->gender == 'Girl' ? 'selected':''); ?>><?php echo e(t('Girl')); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('SID')); ?></label>
                        <input class="form-control" name="id_number" type="text"
                               value="<?php echo e(isset($user) ? $user->id_number : old("id_number")); ?>" placeholder="<?php echo e(t('SID')); ?>">
                    </div>
                </div>
                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active" id="flexCheckDefault" <?php echo e(isset($user) && $user->active ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Active')); ?>

                        </label>
                    </div>


                </div>




            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mr-2"><?php echo e(isset($user)?t('Update'):t('Save')); ?></button>
                </div>
            </div>
        </div>

    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\School\UserRequest::class, '#form-profile-save'); ?>

    <script src="<?php echo e(asset('intl-tel-input-master/build/js/intlTelInput.min.js')); ?>"></script>
    <script type="text/javascript">
        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay: false,
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["غير فعال", "رمز دولة خاطئ", "قصير جدا", "طويل جدا", "رقم خاطئ"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            utilsScript: "<?php echo e(asset('intl-tel-input-master/build/js/utils.js?1562189064761')); ?>"
        });


        var reset = function () {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
            if (input.value.trim()) {
                if (iti.isValidNumber()) {
                    countryCode.value = iti.getSelectedCountryData().dialCode;
                    shortCountry.value = iti.getSelectedCountryData().iso2;
                    validMsg.classList.remove("hidden");
                } else {
                    input.classList.add("error");
                    var errorCode = iti.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("hidden");
                }
            }
        };

        // on blur: validate
        input.addEventListener('blur', reset);

        // on keyup / change flag: reset
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);
    </script>
    <script>
        $(document).ready(function () {
            $('#generateUserName').click(function () {
                generateUserName();
            });
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
            $('#active_to_date').flatpickr();
            //on change package get days and create date from today + days with format Y-m-d and add to flatpickr active to
            $('select[name="package_id"]').change(function () {
                let days = $(this).find(':selected').data('days');
                let date = new Date();
                date.setDate(date.getDate() + days);
                let formattedDate = date.toISOString().substr(0, 10);
                $('input[name="active_to"]').val(formattedDate);
                $('#active_to_date').flatpickr();
            });
        });
    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/user/edit.blade.php ENDPATH**/ ?>