

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add lesson assignments')): ?>
        <a href="<?php echo e(route(getGuard().'.lesson_assignment.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            <?php echo e(t('Add Lesson Assignment')); ?>

        </a>
    <?php endif; ?>
    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export lesson assignments')): ?>
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('<?php echo e(route(getGuard().'.user_lesson_assignment.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete lesson assignments')): ?>
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!"
                       id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>

        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1  mb-2">
            <label><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-2 mb-2">
            <label><?php echo e(t('Student ID')); ?>:</label>
            <input type="text" name="user_id" class="form-control direct-search" placeholder="<?php echo e(t('Student ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label><?php echo e(t('Student Name')); ?>:</label>
            <input type="text" name="user_name" class="form-control direct-search" placeholder="<?php echo e(t('Student Name')); ?>">
        </div>
        <div class="col-lg-3  mb-2">
            <label><?php echo e(t('Student Email')); ?>:</label>
            <input type="text" name="user_email" class="form-control direct-search" placeholder="<?php echo e(t('Email')); ?>">
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

        <?php if(guardIs('manager')): ?>
            <div class="col-3 mb-2">
                <label class=""><?php echo e(t('School')); ?>:</label>
                <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="<?php echo e(t('Select School')); ?>">
                    <option></option>
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>

        <?php if(!guardIs('teacher')): ?>
            <div class="col-3 mb-2">
                <label class=""><?php echo e(t('Teacher')); ?>:</label>
                <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                        data-placeholder="<?php echo e(t('Select Teacher')); ?>">
                    <option></option>
                    <?php if(isset($teachers)): ?>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </select>
            </div>
        <?php endif; ?>

        <div class="col-3 mb-2">
            <label class=""><?php echo e(t('Section')); ?>:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Section')); ?>">
                <option></option>
                <?php if(isset($sections)): ?>
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($section); ?>"><?php echo e($section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </select>
        </div>
        <div class="col-3 mb-2">
            <label class=""><?php echo e(t('Activation')); ?>:</label>
            <select class="form-select" name="user_status" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Status')); ?>">
                <option></option>
                <option value="active"><?php echo e(t('Active')); ?></option>
                <option value="expire"><?php echo e('Expired'); ?></option>
            </select>
        </div>


        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Lesson')); ?> :</label>
            <select  name="lesson_id" id="lesson_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Lesson')); ?>" data-allow-clear="true">
                <option></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Status')); ?> :</label>
            <select name="status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Completed')); ?></option>
                <option value="2"><?php echo e(t('UnCompleted')); ?></option>
            </select>
        </div>

        <div class="col-lg-3 mb-2">
            <label><?php echo e(t('Assigned in')); ?> :</label>
            <input autocomplete="disabled" class="form-control form-control-solid" name="date_range" value="" placeholder="<?php echo e(t('Pick date range')); ?>" id="date_range"/>
            <input type="hidden" name="start_date" id="start_date_range" />
            <input type="hidden" name="end_date" id="end_date_range" />
        </div>
        <?php if(request()->get('lesson_assignment_id')): ?>
            <input type="hidden" name="lesson_assignment_id" value="<?php echo e(request()->get('lesson_assignment_id')); ?>">
        <?php endif; ?>
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
                <th class="text-start"><?php echo e(t('Lesson')); ?></th>
                <th class="text-start"><?php echo e(t('Status')); ?></th>
                <th class="text-start"><?php echo e(t('Dates')); ?></th>
            </tr>
            </thead>
        </table>
    </div>


<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        var DELETE_URL = "<?php echo e(route(getGuard().'.user_lesson_assignment.destroy')); ?>";
        var TABLE_URL = window.location.href;
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            // {data: 'school', name: 'school'},
            {data: 'lesson', name: 'lesson'},
            {data: 'status', name: 'status'},
            {data: 'dates', name: 'dates'},
        ];
        initializeDateRangePicker();
        callAllEvents();
    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user_lesson_assignment/index.blade.php ENDPATH**/ ?>