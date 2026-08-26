<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add users')): ?>
        <a href="<?php echo e(route(getGuard().'.user.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            <?php echo e(t('Add User')); ?>

        </a>
    <?php endif; ?>
    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="cardsExport(true)"><?php echo e(t('Cards')); ?></a></li>
            <li><a class="dropdown-item" href="#!"
                   onclick="excelExport('<?php echo e(route(getGuard().'.user.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <li><a class="dropdown-item d-none checked-visible" href="#!" onclick="$('#update_learning_years_modal').modal('show')" ><?php echo e(t('Update Learning Years')); ?></a></li>
            <li><a class="dropdown-item text-danger d-none checked-visible" id="unassigned_users_teachers"><?php echo e(t('Unsigned')); ?></a></li>

            
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <input type="hidden" id="teacher_id" value="<?php echo e(auth()->guard('teacher')->id()); ?>">

        <div class="col-1  mb-2">
            <label><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-lg-3  mb-2">
            <label><?php echo e(t('Student Name')); ?>:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Student Name')); ?>">
        </div>
        <div class="col-lg-3  mb-2">
            <label><?php echo e(t('Email')); ?>:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="<?php echo e(t('Email')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class=""><?php echo e(t('Year')); ?>:</label>
            <select class="form-select" name="year_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Year')); ?>">
                <option></option>
                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Grade')); ?> :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                    data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class=""><?php echo e(t('Package')); ?>:</label>
            <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Package')); ?>">
                <option></option>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class=""><?php echo e(t('Section')); ?>:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Section')); ?>">
                <option></option>
                <?php $__currentLoopData = teacherSections(Auth::user()->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($section); ?>"><?php echo e($section); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class=""><?php echo e(t('Gender')); ?>:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Gender')); ?>">
                <option></option>
                <option value="Boy"><?php echo e(t('Boy')); ?></option>
                <option value="Girl"><?php echo e(t('Girl')); ?></option>
            </select>
        </div>



        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label><?php echo e(t('Register Date')); ?>:</label>
            <input id="register_date" class="form-control " placeholder="<?php echo e(t('Select Register Date')); ?>">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label><?php echo e(t('Login Date')); ?>:</label>
            <input id="login_at" class="form-control " placeholder="<?php echo e(t('Select Login Date')); ?>">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>
        <div class="col-2 mb-2">
            <label class=""><?php echo e(t('Activation')); ?>:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Status')); ?>">
                <option></option>
                <option value="active"><?php echo e(t('Active')); ?></option>
                <option value="expire"><?php echo e('Expired'); ?></option>
            </select>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <table class="table table-row-bordered gy-5" id="datatable">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800">
                <th class="text-start"></th>
                <th class="text-start"><?php echo e(t('Student')); ?></th>
                <th class="text-start"><?php echo e(t('Information')); ?></th>
                <th class="text-start"><?php echo e(t('Dates')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="update_learning_years_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Update Learning Years')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <div class="">
                        <label><?php echo e(t('Learning Years')); ?> :</label>
                        <select id="learning_years" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Learning Year')); ?>" data-allow-clear="true">
                            <?php $__currentLoopData = range(0,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="update_learning_year" ><?php echo e(t('Update')); ?></button>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var ASSIGNED_STUDENTS = '<?php echo e(t('Students Assign')); ?>',
            ASSIGNED_STUDENTS_MESSAGE = '<?php echo e(t('Do you really want to assign students?')); ?>',
            UNSIGNED_TEACHER = '<?php echo e(t('Unsigned Teacher')); ?>',
            UNSIGNED_TEACHER_MESSAGE = '<?php echo e(t('Do you want to delete teacher for selected students')); ?>';


        var DELETE_URL = '<?php echo e(route(getGuard().'.user.destroy')); ?>';
        var TABLE_URL = "<?php echo e(route(getGuard().'.my-students')); ?>";
        var UPDATE_LEARNING_YEARS_URL = '<?php echo e(route(getGuard().'.user.update_learning_years')); ?>';
        var UNSIGNED_TEACHER_URL = '<?php echo e(route(getGuard().'.user.unassigned_user_teacher')); ?>';
        var STUDENT_CARD_URL = "<?php echo e(route(getGuard().'.user.cards-export')); ?>";

        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];


    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('assets_v1/js/general/users.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('teacher.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/teacher_students.blade.php ENDPATH**/ ?>