

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add teachers')): ?>
    <a href="<?php echo e(route('manager.teacher.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-plus"></i>
        <?php echo e(t('Add Teacher')); ?>

    </a>
    <?php endif; ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export teachers')): ?>
                <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('manager.teacher.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teachers activation')): ?>
                    <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#teacher_activation_modal"><?php echo e(t('Activation')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher users unsigned')): ?>
                    <li><a class="dropdown-item text-warning" href="#!" data-bs-toggle="modal" data-bs-target="#delete_students_modal"><?php echo e(t('Unsigned Students')); ?></a></li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete teachers')): ?>
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>

        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-1 mb-2">
            <label class="mb-2"><?php echo e(t('ID')); ?>:</label>
            <input type="text" name="id" class="form-control direct-search" placeholder="<?php echo e(t('ID')); ?>">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control direct-search" placeholder="<?php echo e(t('Name')); ?>">
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Email')); ?>:</label>
            <input type="text" name="email" class="form-control direct-search" placeholder="<?php echo e(t('Email')); ?>">
        </div>

        <div class="col-2 mb-2">
            <label class="mb-2"><?php echo e(t('Mobile')); ?>:</label>
            <input type="text" name="mobile" class="form-control" placeholder="<?php echo e(t('Mobile')); ?>">
        </div>


        <div class="col-lg-3 mb-2">
            <label class="mb-2"><?php echo e(t('School')); ?> :</label>
            <select name="school_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select School')); ?>" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Students')); ?> :</label>
            <select name="student_status" id="student_status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Has students')); ?></option>
                <option value="2"><?php echo e(t('Has no students')); ?></option>
                <option value="3"><?php echo e(t('Has active students')); ?></option>
                <option value="4"><?php echo e(t('Has inactive students')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Approval')); ?> :</label>
            <select name="approved" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Approved')); ?></option>
                <option value="2"><?php echo e(t('Under review')); ?></option>
            </select>
        </div>
        <div class="col-lg-2 mb-2">
            <label class="mb-2"><?php echo e(t('Activation')); ?> :</label>
            <select name="active" id="status" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                <option></option>
                <option value="1"><?php echo e(t('Active')); ?></option>
                <option value="2"><?php echo e(t('Non-Active')); ?></option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Activation Date')); ?>:</label>
            <input id="active_to" class="form-control " placeholder="<?php echo e(t('Select Activation Date')); ?>">
            <input type="hidden" id="start_active_to" name="start_active_to" value="">
            <input type="hidden" id="end_active_to" name="end_active_to" value="">
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
                <th class="text-start"><?php echo e(t('Teacher')); ?></th>
                <th class="text-start"><?php echo e(t('School')); ?></th>
                <th class="text-start"><?php echo e(t('Approval')); ?></th>
                <th class="text-start"><?php echo e(t('Activation')); ?></th>
                <th class="text-start"><?php echo e(t('Last login')); ?></th>
                <th class="text-start"><?php echo e(t('Active To')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" tabindex="-1" id="teacher_activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Teacher Activation')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                   <form id="activation_form">
                       <div class="mb-2">
                           <label class="mb-2"><?php echo e(t('Approved Status')); ?> :</label>
                           <select name="approved" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                               <option></option>
                               <option value="1"><?php echo e(t('Approved')); ?></option>
                               <option value="2"><?php echo e(t('Under review')); ?></option>
                           </select>
                       </div>
                       <div class="mb-2">
                           <label class="mb-2"><?php echo e(t('Activation Status')); ?> :</label>
                           <select name="active" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                               <option></option>
                               <option value="1"><?php echo e(t('Activate')); ?></option>
                               <option value="2"><?php echo e(t('Deactivate')); ?></option>
                           </select>
                       </div>

                       <div class="mb-2">
                           <label class="mb-2"><?php echo e(t('Active To')); ?> :</label>
                           <input class="form-control form-control-solid" id="active_to_date" name="active_to" value="" placeholder="<?php echo e(t('Active to')); ?>"/>
                       </div>
                   </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_teacher_activation"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="delete_students_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Unsigned Students')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                   <form id="delete_students_form">
                       <div class="mb-2">
                           <label class="mb-2"><?php echo e(t('Students Type')); ?> :</label>
                           <select name="type" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Type')); ?>" data-allow-clear="true">
                               <option></option>
                               <option value="1"><?php echo e(t('All')); ?></option>
                               <option value="2"><?php echo e(t('Active')); ?></option>
                               <option value="3"><?php echo e(t('Inactive')); ?></option>
                           </select>
                       </div>
                   </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_delete_students"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = "<?php echo e(route('manager.teacher.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.teacher.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'teacher', name: 'teacher'},
            {data: 'school', name: 'school'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'last_login', name: 'last_login'},
            {data: 'active_to', name: 'active_to'},
            {data: 'actions', name: 'actions'}
        ];

        $('#active_to_date').flatpickr();
        initializeDateRangePicker('active_to');
        initializeDateRangePicker('login_at');

        $(document).on('click', '#btn_teacher_activation',function(){
            $('#teacher_activation_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['activation_data'] = getFormDataAsObject('activation_form')
            data['_token'] = '<?php echo e(csrf_token()); ?>'
            $('#activation_form').find('input').val('')
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "<?php echo e(route('manager.teacher.activation')); ?>",
                type: 'post',
                data: data,
                success: function(response){
                    hideLoadingModal()
                    table.DataTable().draw(true);
                    toastr.success(response.message);
                },
                error(error){
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message);
                }
            });
        });
        $(document).on('click', '#btn_delete_students',function(){
            $('#delete_students_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['delete_students'] = getFormDataAsObject('delete_students_form')
            data['_token'] = '<?php echo e(csrf_token()); ?>'
            $('#delete_students_form').find('select').val('').trigger('change')
            $.ajax({
                url: "<?php echo e(route('manager.teacher.user-unsigned')); ?>",
                type: 'post',
                data: data,
                success: function(response){
                    hideLoadingModal()
                    table.DataTable().draw(true);
                    toastr.success(response.message);
                },
                error(error){
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message);
                }
            });
        });

    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/teacher/index.blade.php ENDPATH**/ ?>