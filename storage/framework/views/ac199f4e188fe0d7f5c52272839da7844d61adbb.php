

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('school.user.export_students_excel')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <li><a class="dropdown-item" href="#!" onclick="cardsExport(true)"><?php echo e(t('Cards')); ?></a></li>
            
            <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1  mb-2">
            <label class="mb-2"><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-lg-2  mb-2">
            <label class="mb-2"><?php echo e(t('Student Name')); ?>:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Student Name')); ?>">
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="<?php echo e(t('Email')); ?>">
        </div>

        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('Grade')); ?> :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                    data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Teacher')); ?>:</label>
            <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Teacher')); ?>">
                <option></option>
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('Section')); ?>:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Section')); ?>">
                <option></option>
            </select>
        </div>


        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Package')); ?>:</label>
            <select class="form-select" name="package_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Package')); ?>">
                <option></option>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($package->id); ?>"><?php echo e($package->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('Gender')); ?>:</label>
            <select class="form-select" name="gender" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Gender')); ?>">
                <option></option>
                <option value="Boy"><?php echo e(t('Boy')); ?></option>
                <option value="Girl"><?php echo e('Girl'); ?></option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Register Date')); ?>:</label>
            <input id="register_date" class="form-control " placeholder="<?php echo e(t('Select Register Date')); ?>">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('Activation')); ?>:</label>
            <select class="form-select" name="status" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Status')); ?>">
                <option></option>
                <option value="active"><?php echo e(t('Active')); ?></option>
                <option value="expire"><?php echo e('Expired'); ?></option>
            </select>
        </div>


        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Login Date')); ?>:</label>
            <input id="login_at" class="form-control " placeholder="<?php echo e(t('Select Login Date')); ?>">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
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
                <th class="text-start"><?php echo e(t('Teacher')); ?></th>
                <th class="text-start"><?php echo e(t('Dates')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script>
        var STUDENT_CARD_URL = "<?php echo e(route('school.user.cards-export')); ?>";

        var DELETE_URL = "<?php echo e(route('school.student.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('school.student.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'teacher', name: 'teacher'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];

        //restore students
    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('assets_v1/js/manager/users.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        getSectionByTeacher()
    </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('school.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/user/index.blade.php ENDPATH**/ ?>