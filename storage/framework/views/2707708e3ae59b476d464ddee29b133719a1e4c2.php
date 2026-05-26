

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <a href="<?php echo e(route('manager.import_files.index')); ?>" class="text-muted">
            <?php echo e(t('Student Import Files')); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($type == 'Teacher'): ?>
        <form action="<?php echo e(route('manager.import_files.store')); ?>"
              method="post" class="form" id="form-data" enctype="multipart/form-data">
            <input name="type" value="Teacher" type="hidden">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Select the file containing the data')); ?></label>
                    <div class="col-6">
                        <input type="file" class="form-control" name="import_file">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('School')); ?></label>
                    <div class="col-6">
                        <select name="school_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="<?php echo e(t('Select School')); ?>">
                            <option value="" disabled selected><?php echo e(t('Select School')); ?></option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Active To')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="active_to" type="text"
                               value="<?php echo e(isset($user) ? $user->active_to : old("active_to")); ?>" id="active_to_date"
                               placeholder="<?php echo e(t('Active To')); ?>">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Email Ends With')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="last_of_email" type="text"
                               value="@arabic-arabs.com" dir="ltr" id="last_of_email"
                               placeholder="<?php echo e(t('Email Ends With')); ?>">
                    </div>
                </div>

                <div class="d-flex align-items-center mt-8">
                    <h5 class="m-0 mr-2"><?php echo e(t('Note')); ?>: </h5>
                    <p class="m-0"><?php echo e($note); ?></p>
                </div>
                <div class="d-flex align-items-center my-2">

                    <h5 class="m-0"><?php echo e(t('Download Sample of Teachers file')); ?> : <a class="text-danger" target="_blank"
                                                                                   href="<?php echo e(asset('assets_v1/import_example/Teachers Example.xlsx')); ?>"> <?php echo e(t('Click to Download')); ?></a>
                    </h5>
                </div>
                <div class="row my-5">
                    <div class="separator separator-content my-4"></div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit"
                                class="btn btn-primary mr-2"><?php echo e(t('Import')); ?></button>
                    </div>
                </div>
            </div>

        </form>
    <?php else: ?>
        <div class="row">
            <form class="kt-form kt-form--fit mb-15" id="upload_file"
                  method="POST" action="<?php echo e(route('manager.import_files.store')); ?>" enctype="multipart/form-data">
                <input type="hidden" name="type" value="User">
                <?php echo csrf_field(); ?>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Status')); ?></label>
                    <div class="col-6">
                        <select name="process_type" class="form-control form-select" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Status')); ?>">
                            <?php $__currentLoopData = ['create', 'update', 'delete']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>"
                                        <?php if($loop->first): ?> selected <?php endif; ?>><?php echo e(t(ucfirst($status))); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Select the file containing the data')); ?></label>
                    <div class="col-6">
                        <input type="file" class="form-control" name="import_file">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('School')); ?></label>
                    <div class="col-6">
                        <select name="school_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="<?php echo e(t('Select School')); ?>">
                            <option value="" disabled selected><?php echo e(t('Select School')); ?></option>
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Year')); ?></label>
                    <div class="col-6">
                        <select name="year_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="<?php echo e(t('Select Year')); ?>">
                            <option value="" disabled selected><?php echo e(t('Select Year')); ?></option>
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Package')); ?></label>
                    <div class="col-6">
                        <select name="package_id" class="form-control form-select" data-control="select2"
                                data-allow-clear="true" data-placeholder="<?php echo e(t('Select Package')); ?>">
                            <option value="" disabled selected><?php echo e(t('Select Package')); ?></option>
                            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option data-days="<?php echo e($package->days); ?>"
                                        value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Active To')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="active_to" type="text"
                               value="<?php echo e(isset($user) ? $user->active_to : old("active_to")); ?>" id="active_to_date"
                               placeholder="<?php echo e(t('Active To')); ?>">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Back Grade')); ?></label>
                    <div class="col-6">
                        <select name="back_grade" class="form-control form-select" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Status')); ?>">
                            <?php $__currentLoopData = [0, 1, 2]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($grade); ?>" <?php if($loop->first): ?> selected <?php endif; ?>><?php echo e($grade); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Email Ends With')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="last_of_email" type="text"
                               value="@arabic-arabs.com" dir="ltr" id="last_of_email"
                               placeholder="<?php echo e(t('Email Ends With')); ?>">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Email Structure')); ?></label>
                    <div class="col-6">
                        <select name="email_structure" class="form-control form-select" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Structure')); ?>">
                            <option value="use_name" selected><?php echo e(t('Use Name')); ?></option>
                            <option value="use_student_id"><?php echo e(t('Use Student ID')); ?></option>
                            <option value="use_student_id_with_name"><?php echo e(t('Use Student ID With Name')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('Default mobile')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="default_mobile" type="text"
                               value="+971500000000">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('country code')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="country_code" type="text"
                               value="971">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-3 col-form-label"><?php echo e(t('short country')); ?></label>
                    <div class="col-6">
                        <input class="form-control" name="short_country" type="text"
                               value="ae">
                    </div>
                </div>
                <div class="d-flex align-items-center mt-8">
                    <h5 class="m-0 mr-2"><?php echo e(t('Note')); ?>: </h5>
                    <p class="m-0"><?php echo e($note); ?></p>
                </div>
                <div class="d-flex align-items-center my-2">

                    <h5 class="m-0"><?php echo e(t('Download Sample of users file')); ?> : <a class="text-danger" target="_blank"
                                                                                href="<?php echo e(asset('assets_v1/import_example/Users Example.xlsx')); ?>?v=1"> <?php echo e(t('Click to Download')); ?></a>
                    </h5>
                </div>
                <div class="row my-5">
                    <div class="separator separator-content my-4"></div>
                    <div class="col-12 d-flex justify-content-end">
                        <butto onclick="$('#upload_file').submit()" type="submit"
                               class="btn btn-primary mr-2"><?php echo e(t('Import')); ?></butto>
                    </div>
                </div>

            </form>


        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            $('#active_to_date').flatpickr();

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

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import/edit.blade.php ENDPATH**/ ?>