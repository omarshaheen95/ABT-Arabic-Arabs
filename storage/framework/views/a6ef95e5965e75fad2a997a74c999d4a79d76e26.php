<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('style'); ?>
    <?php if(app()->getLocale()=='ar'): ?>
        <link href="<?php echo e(asset('assets_v1/lib/multiselect/multiselect-rtl.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet"
              type="text/css"/>
    <?php endif; ?>
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
    <?php if(guardIn(['school', 'teacher']) && !isset($user)): ?>
        <div class="row my-3 justify-content-center">
            <div class="col-12">
                <div class="alert alert-danger">
                    <h4>سيظل الحساب معطلاً حتى تتم الموافقة عليه من قبل ABT</h4>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(isset($user) ? route(getGuard().'.user.update', $user->id): route(getGuard().'.user.store')); ?>"
          method="post" class="form" id="form-profile-save" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <?php if(isset($user)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>

        <div class="row">
            <?php if(guardIs('manager')): ?>
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
            <?php endif; ?>


            <div class="row">
                <div class="col-2 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('SID')); ?></label>
                        <input class="form-control" name="id_number" type="text"
                               value="<?php echo e(isset($user) ? $user->id_number : old("id_number")); ?>"
                               placeholder="<?php echo e(t('SID')); ?>">
                    </div>
                </div>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Name')); ?></label>
                        <input type="text" name="name" class="form-control name remove_spaces"
                               placeholder="<?php echo e(t('Name')); ?>"
                               value="<?php echo e(isset($user->name) ? $user->name : old("name")); ?>" required>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Email')); ?></label>
                        <div class="input-group">
                            <input dir="ltr" name="email" type="text" placeholder="<?php echo e(t('Email')); ?>"
                                   value="<?php echo e(isset($user->email) ? $user->email : old("email")); ?>"
                                   class="form-control username" aria-describedby="basic-addon1"/>
                            <span class="input-group-text" id="basic-addon1">
                         <a class="p-0 cursor-pointer" id="generateUserName"><i class="fas fa-refresh"></i></a>
                     </span>
                        </div>
                    </div>
                </div>

                <?php if(guardIs('manager')): ?>
                    <div class="col-3 mb-2">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(t('Mobile')); ?></label>
                            <input type="text" class="form-control" placeholder="<?php echo e(t('Mobile')); ?>" name="mobile" id="Phone"
                                   value="<?php echo e(isset($user) ? $user->mobile : old('mobile')); ?>">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="password" class="form-label"><?php echo e(t('Password')); ?></label>
                        <input type="text" name="password" class="form-control" placeholder="<?php echo e(t('Password')); ?>"
                               <?php if(!isset($user)): ?> value="123456" <?php endif; ?>>
                    </div>
                </div>

                <div class="col-3 mb-2">
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
                <?php if(guardIs('manager')): ?>
                    <div class="col-3 mb-2">
                        <div class="form-group">
                            <label for="" class="form-label"><?php echo e(t('Alternative Grade')); ?></label>
                            <select class="form-select" name="alternate_grade_id" data-control="select2"
                                    data-allow-clear="true" data-placeholder="<?php echo e(t('Select Grade')); ?>">
                                <option value="" selected><?php echo e(t('Select Grade')); ?></option>
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option
                                        value="<?php echo e($grade->id); ?>" <?php echo e(isset($user) && $user->alternate_grade_id == $grade->id ? 'selected':''); ?>>
                                        <?php echo e($grade->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(guardIs('manager')): ?>
                    <div class="col-3 mb-2">
                        <div class="form-group">
                            <label for="" class="form-label"><?php echo e(t('Section')); ?></label>
                            <input class="form-control" name="section" type="text"
                                   value="<?php echo e(isset($user) ? $user->section : old("section")); ?>"
                                   placeholder="<?php echo e(t('Section')); ?>">
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-3 mb-2">
                        <div class="form-group">
                            <label for="" class="form-label"><?php echo e(t('Section')); ?></label>
                            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                                    data-placeholder="<?php echo e(t('Select Section')); ?>">
                                <option></option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section); ?>" <?php echo e(isset($user) && $user->section == $section ? 'selected':''); ?>><?php echo e($section); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

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

                <?php if(guardIs('manager')): ?>
                    <div class="col-3 mb-2">
                        <div class="form-group">
                            <label for="" class="form-label"><?php echo e(t('School')); ?></label>
                            <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                                    data-placeholder="<?php echo e(t('Select School')); ?>">
                                <option></option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(isset($user) && $user->school_id == $school->id ? 'selected':''); ?>><?php echo e($school->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(guardIn(['manager','school','supervisor'])): ?>
                    <div class="col-3 mb-2">
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
                <?php endif; ?>

                <?php if(guardIs('manager')): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add users')): ?>
                        <div class="col-3 mb-2">
                            <div class="form-group">
                                <label for="" class="form-label"><?php echo e(t('Package')); ?></label>
                                <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                                        data-placeholder="<?php echo e(t('Select Package')); ?>">
                                    <option></option>
                                    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option data-days="<?php echo e($package->days); ?>"
                                                value="<?php echo e($package->id); ?>" <?php echo e(isset($user) && $user->package_id == $package->id ? 'selected':''); ?>><?php echo e($package->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3 mb-2">
                            <div class="form-group">
                                <label for="" class="form-label"><?php echo e(t('Active To')); ?></label>
                                <input class="form-control" name="active_to" type="text"
                                       value="<?php echo e(isset($user) ? $user->active_to : old("active_to")); ?>" id="active_to_date"
                                       placeholder="<?php echo e(t('Active To')); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

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
                        <label for="" class="form-label"><?php echo e(t('Nationality')); ?></label>
                        <input class="form-control" name="nationality" type="text"
                               value="<?php echo e(isset($user) ? $user->nationality : old("nationality")); ?>"
                               placeholder="<?php echo e(t('Nationality')); ?>">
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label for="" class="form-label"><?php echo e(t('Direct Email to Contact')); ?></label>
                        <input class="form-control" name="direct_email" type="text"
                               value="<?php echo e(isset($user) ? $user->direct_email : old("direct_email")); ?>"
                               placeholder="<?php echo e(t('Direct Email')); ?>">
                    </div>
                </div>
               <div class="col-12 row">
                   <?php if(guardIs('manager')): ?>
                       <div class="col-1 mb-2 d-flex gap-2 mt-4">
                           <div class="form-check form-check-custom form-check-solid">
                               <input class="form-check-input" type="checkbox" value="1" name="active"
                                      id="flexCheckDefault" <?php echo e(isset($user) && $user->active ? 'checked':''); ?>/>
                               <label class="form-check-label text-dark" for="flexCheckDefault">
                                   <?php echo e(t('Active')); ?>

                               </label>
                           </div>
                       </div>
                       <div class="col-1 mb-2 d-flex gap-2 mt-4">
                           <div class="form-check form-check-custom form-check-solid">
                               <input class="form-check-input" type="checkbox" value="1" name="demo"
                                      id="flexCheckDefault" <?php echo e(isset($user) && $user->demo ? 'checked':''); ?>/>
                               <label class="form-check-label text-dark" for="flexCheckDefault">
                                   <?php echo e(t('Demo')); ?>

                               </label>
                           </div>
                       </div>
                   <?php endif; ?>
               </div>

                <?php if(guardIs('manager')): ?>
                    <div id="demo_data" class="col-12 row justify-content-center d-none" dir="rtl">
                        <div class="col-5">
                            <label class="mb-2"><?php echo e(t('Grades')); ?></label>
                            <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-2 d-flex flex-column justify-content-center align-items-center mt-5" dir="rtl">
                            <button type="button"  id="multiselect_leftAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-forward"></i></button>
                            <button type="button" id="multiselect_leftSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-right"></i></button>
                            <button type="button" id="multiselect_rightSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" id="multiselect_rightAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-backward"></i></button>
                        </div>

                        <div class="col-5">
                            <label class="mb-2"><?php echo e(t('Selected Grades')); ?></label>
                            <select name="demo_grades[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">

                                <?php if(isset($user->demo_grades)&&count($user->demo_grades)>0): ?>
                                    <?php
                                        $demo_grades = $grades->whereIn('id',$user->demo_grades);
                                    ?>
                                    <?php $__currentLoopData = $demo_grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </select>
                        </div>
                    </div>
                <?php endif; ?>

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
    <?php echo JsValidator::formRequest(\App\Http\Requests\General\UserRequest::class, '#form-profile-save'); ?>

    <script src="<?php echo e(asset('intl-tel-input-master/build/js/intlTelInput.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets_v1/lib/multiselect/multiselect.min.js')); ?>"></script>
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

            <?php if(guardIs('manager')): ?>
            getAndSetDataOnSelectChange('school_id','teacher_id','<?php echo e(route("manager.getTeacherBySchool", ":id")); ?>')
            <?php endif; ?>
        });
        //Demo -------------------------------------------------------------------
        if (parseInt($('input[name="demo"]:checked').val()) === 1) {
            $('#demo_data').removeClass('d-none');
        }

        //show and hide Demo data
        $('input[name="demo"]').change(function () {
            var demo = $('input[name="demo"]:checked').val();
            if (parseInt(demo) === 1) {
                $('#demo_data').removeClass('d-none');
            } else {
                $('#demo_data').addClass('d-none');
            }
        });

        //Init multiselecet For Levels
        $('#multiselect').multiselect({
            sort: false,
        });
    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/edit.blade.php ENDPATH**/ ?>