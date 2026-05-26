

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('intl-tel-input-master/build/css/intlTelInput.min.css')); ?>" rel="stylesheet">
    <style>
        .hide{
            display: none;
        }
    </style>
    <?php if(app()->getLocale() == 'ar'): ?>
        <style>
            .iti * {
                direction: ltr;
            }
        </style>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startPush('breadcrumb'); ?>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('supervisor.student.index')); ?>"><?php echo e(t('Students')); ?></a>
        </li>
        <li class="breadcrumb-item">
            <?php echo e(isset($user) ? t('Edit Student') : t('Add Student')); ?>

        </li>
    <?php $__env->stopPush(); ?>
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"><?php echo e(isset($user) ? t('Edit Student') : t('Add Student')); ?></h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="<?php echo e(isset($user) ? route('supervisor.student.update', $user->id): route('supervisor.student.store')); ?>" method="post">
                    <?php echo e(csrf_field()); ?>

                    <?php if(isset($user)): ?>
                        <input type="hidden" name="_method" value="patch">
                    <?php endif; ?>
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Image')); ?></label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-danger"><?php echo e(t('Upload Image')); ?></button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file" />
                                                </div>
                                                <img id="blah" <?php if(!isset($user) || is_null($user->image)): ?> style="display:none" <?php endif; ?> src="<?php echo e(isset($user) && !is_null($user->image)  ? $user->image:''); ?>" width="150" alt="No file chosen" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Name')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="<?php echo e(isset($user->name) ? $user->name : old("name")); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Email')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text" value="<?php echo e(isset($user->email) ? $user->email : old("email")); ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Mobile')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" id="Phone" name="phone" type="text" value="<?php echo e(isset($user) ? $user->mobile : old('mobile')); ?>">
                                        <input type="hidden" id="country-code" name="country_code" value="<?php echo e(isset($user) ? $user->country_code : old('country_code')); ?>">
                                        <input type="hidden" id="short-country" name="short_country" value="<?php echo e(isset($user) ? $user->short_country : old('short_country')); ?>">
                                        <input type="hidden" placeholder="<?php echo e(t('Mobile')); ?>" name="mobile" id="mobileHidden" value="<?php echo e(isset($user) ? $user->mobile : old('mobile')); ?>" class="form-control form-control-lg">
                                        <span id="valid-msg" class="hide">✓ <?php echo e(t('Valid')); ?></span>
                                        <span id="error-msg" class="hide"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Password')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="text" <?php if(!isset($user)): ?> value="123456" <?php endif; ?>>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Teacher')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="teacher_id">
                                            <option value="" selected><?php echo e(t('Select teacher')); ?></option>
                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($teacher->id); ?>" <?php echo e(isset($user) && optional($user->teacher_student)->teacher_id == $teacher->id ? 'selected':''); ?>><?php echo e($teacher->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger"><?php echo e(isset($user) ? t('update'):t('create')); ?></button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')); ?>" type="text/javascript"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\175\UserRequest::class, '#form_information'); ?>

    <script src="<?php echo e(asset('intl-tel-input-master/build/js/intlTelInput.min.js')); ?>"></script>
    <script type="text/javascript">
        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay:false,
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["<?php echo e(t('Invalid number')); ?>", "<?php echo e(t('Invalid country code')); ?>", "<?php echo e(t('Too short')); ?>", "<?php echo e(t('Too long')); ?>", "<?php echo e(t('Invalid number')); ?>"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            utilsScript: "<?php echo e(asset('intl-tel-input-master/build/js/utils.js?1562189064761')); ?>"
        });



        var reset = function() {
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
        $(document).ready(function() {
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('supervisor.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/supervisor/user/edit.blade.php ENDPATH**/ ?>