

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('pre-content'); ?>
    <div class="row">
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="<?php echo e(asset($user->image)); ?>" alt="image">
                            <div
                                class="position-absolute translate-middle bottom-0 start-100 mb-6 <?php if($user->active_to > now()): ?> bg-success <?php else: ?> bg-danger <?php endif; ?> rounded-circle border border-4 border-body h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->

                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="#"
                                       class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo e($user->name); ?></a>
                                    <?php if($user->active_to > now()): ?>
                                        <a href="#"><i class="ki-duotone ki-verify fs-1 text-success"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    <?php else: ?>
                                        <a href="#"><i class="ki-duotone ki-verify fs-1 text-danger"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    <?php endif; ?>
                                </div>
                                <!--end::Name-->

                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Teacher')); ?> : </span>
                                        <label><?php echo e(optional($user->teacher)->name ?? t('Unsigned')); ?></label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Grade')); ?> : </span>
                                        <label><?php echo e($user->grade->name); ?></label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Alternative Grade')); ?> : </span>
                                        <label><?php echo e($user->alternateGrade ? $user->alternateGrade->name:'-'); ?></label>
                                    </a>
                                </div>
                                <!--end::Info-->

                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Register Date')); ?> : </span>
                                        <label><?php echo e($user->created_at); ?></label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Active From')); ?> : </span>
                                        <label><?php echo e(optional($user->active_from)->format('Y-m-d')); ?></label>
                                    </a>
                                    <a href="#"
                                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                        <span class="text-primary"><?php echo e(t('Active To')); ?> : </span>
                                        <label><?php echo e(optional($user->active_to)->format('Y-m-d')); ?></label>
                                    </a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->

                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap text-center">
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="<?php echo e($data['watching_avg']); ?>"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1"><?php echo e($data['watching_avg']); ?>%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500"><?php echo e(t('Watching')); ?></div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="<?php echo e($data['reading_avg']); ?>"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1"><?php echo e($data['reading_avg']); ?>%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500"><?php echo e(t('Reading')); ?></div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="<?php echo e($data['test_avg']); ?>"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1"><?php echo e($data['test_avg']); ?>%
                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500"><?php echo e(t('Test')); ?></div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="<?php echo e($data['passed_tests']); ?>"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1"><?php echo e($data['passed_tests']); ?>

                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500"><?php echo e(t('Passed Tests')); ?></div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                 data-kt-countup-value="<?php echo e($data['failed_tests']); ?>"
                                                 data-kt-countup-prefix="%"
                                                 data-kt-initialized="1"><?php echo e($data['failed_tests']); ?>

                                            </div>
                                        </div>
                                        <!--end::Number-->

                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-500"><?php echo e(t('Failed Tests')); ?></div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->



                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->

                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Grade')); ?> :</label>
            <select name="grade" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                    data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = storyGradesSys(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e($grade); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Story')); ?> :</label>
            <select  name="story_id" id="story_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Story')); ?>" data-allow-clear="true">
                <option></option>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Type')); ?> :</label>
            <select name="type" id="type" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Type')); ?>" data-allow-clear="true">
                <option></option>
                <option value="watching"><?php echo e(t('Watching')); ?></option>
                <option value="reading"><?php echo e(t('Reading')); ?></option>
                <option value="test"><?php echo e(t('Test')); ?></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Start Date')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('Story')); ?></th>
                <th class="text-start"><?php echo e(t('Type')); ?></th>
                <th class="text-start"><?php echo e(t('Start Date')); ?></th>
                <th class="text-start"><?php echo e(t('Time Spent')); ?></th>
            </tr>
            </thead>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        var TABLE_URL = "<?php echo e(route($guard.'.user.story_review', $user->id)); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'story', name: 'story'},
            {data: 'type', name: 'type'},
            {data: 'created_at', name: 'created_at'},
            {data: 'time_spent', name: 'time_spent'},
        ];
        initializeDateRangePicker();
        getStoriesByGrade()
    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($guard.'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/user_story_review.blade.php ENDPATH**/ ?>