

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')); ?>" rel="stylesheet" type="text/css"/>
    <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">استيراد طلاب</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="<?php echo e(route('manager.import_users_files.store')); ?>"
                      method="post">
                    <?php echo e(csrf_field()); ?>

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">الملف </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="file" type="file">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('School')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control select2L" name="school_id">
                                            <option value="" selected disabled><?php echo e(t('Select School')); ?></option>
                                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label"><?php echo e(t('Subscription package')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="package_id">
                                            <option value="" selected disabled><?php echo e(t('Select package')); ?></option>
                                            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label"><?php echo e(t('Back Grade')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="back_grade">
                                            <option value="0" selected>0</option>
                                            <option value="1">Back 1</option>
                                            <option value="2">Back 2</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Active To')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control date" name="active_to" type="text"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Last Of Email')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="last_of_email" type="text"
                                               value="@arabic-arabs.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('Default mobile')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="default_mobile" type="text"
                                               value="+971500000000">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('country code')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="country_code" type="text"
                                               value="971">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label"><?php echo e(t('short country')); ?></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="short_country" type="text"
                                               value="ae">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">استيراد</button>&nbsp;
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
    <script src="<?php echo e(asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')); ?>"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import_old/edit.blade.php ENDPATH**/ ?>