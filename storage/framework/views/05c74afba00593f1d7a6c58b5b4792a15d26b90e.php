<?php $__env->startSection('style'); ?>
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
        <script src="<?php echo e(asset('intl-tel-input-master/build/js/intlTelInput.min.js')); ?>"></script>

    <?php else: ?>


    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> <?php echo e(w('Register New Account')); ?>  </h1>
                            <p><?php echo e(w('Fill in your personal information and start with the demo account')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">
                                <form class="col-form" id="form_information" action="/register" method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="name" placeholder="<?php echo e(w('Name')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="<?php echo e(w('Email')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input class="form-control" id="Phone" name="phone" type="text" value="<?php echo e(old('mobile')); ?>">
                                                <input type="hidden" id="country-code" name="country_code" value="<?php echo e(old('country_code')); ?>">
                                                <input type="hidden" id="short-country" name="short_country" value="<?php echo e(old('short_country')); ?>">
                                                <input type="hidden" placeholder="<?php echo e(t('Mobile')); ?>" name="mobile" id="mobileHidden" value="<?php echo e(old('mobile')); ?>" class="form-control form-control-lg">
                                                <span id="valid-msg" class="hide">✓ <?php echo e(t('Valid')); ?></span>
                                                <span id="error-msg" class="hide"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="<?php echo e(w('Password')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="<?php echo e(w('Password Confirmation')); ?> ">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="school_id">
                                                    <option selected disabled value=""><?php echo e(w('Select School')); ?></option>
                                                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="grade">
                                                    <option selected disabled value=""><?php echo e(w('Select Grade')); ?></option>
                                                    <option value="15">KG 2 / Year 1</option>
                                                    <option value="1">Grade 1 / Year 2</option>
                                                    <option value="2">Grade 2 / Year 3</option>
                                                    <option value="3">Grade 3 / Year 4</option>
                                                    <option value="4">Grade 4 / Year 5</option>
                                                    <option value="5">Grade 5 / Year 6</option>
                                                    <option value="6">Grade 6 / Year 7</option>
                                                    <option value="7">Grade 7 / Year 8</option>
                                                    <option value="8">Grade 8 / Year 9</option>
                                                    <option value="9">Grade 9 / Year 10</option>
                                                    <option value="10">Grade 10 / Year 11</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <select class="form-control" name="package_id">
                                                    <option selected disabled value=""><?php echo e(w('Select package')); ?></option>
                                                    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($package->id); ?>" <?php echo e(request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''); ?>><?php echo e($package->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 mt-30px">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block theme-btn-ss">
                                                <?php echo e(w('Register')); ?> <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END SLIDER -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo $validator->selector('#form_information'); ?>

    <script src="<?php echo e(asset('intl-tel-input-master/build/js/intlTelInput.min.js')); ?>"></script>
    <script type="text/javascript">
        var input = document.querySelector("#Phone");
        window.intlTelInput(input, {
            formatOnDisplay:false,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "ae";
                    callback(countryCode);
                });
            },
            utilsScript: "<?php echo e(asset('intl-tel-input-master/build/js/utils.js?1562189064761')); ?>",
        });
        errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["<?php echo e(t('Invalid number')); ?>", "<?php echo e(t('Invalid country code')); ?>", "<?php echo e(t('Too short')); ?>", "<?php echo e(t('Too long')); ?>", "<?php echo e(t('Invalid number')); ?>"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "<?php echo e(asset('intl-tel-input-master/build/js/utils.js?1562189064761')); ?>",
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#Phone').keyup(function () {
                $('#mobileHidden').val(iti.getNumber());
                console.log(iti.getNumber());
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/old_auth/register_old.blade.php ENDPATH**/ ?>