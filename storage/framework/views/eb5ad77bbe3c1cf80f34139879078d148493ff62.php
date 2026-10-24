

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add supervisors')): ?>
    <a href="<?php echo e(route('manager.supervisor.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
        <i class="la la-plus"></i>
        <?php echo e(t('Add Supervisor')); ?>

    </a>
    <?php endif; ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export supervisors')): ?>
                    <li><a class="dropdown-item" href="#!" onclick="excelExport('<?php echo e(route('manager.supervisor.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('supervisors activation')): ?>
                    <li><a class="dropdown-item text-primary" href="#!" data-bs-toggle="modal" data-bs-target="#activation_modal"><?php echo e(t('Activation')); ?></a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete supervisor')): ?>
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
            <label class="mb-2"><?php echo e(t('Approval')); ?> :</label>
            <select name="approved" id="approved" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
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
                <th class="text-start"><?php echo e(t('Supervisor')); ?></th>
                <th class="text-start"><?php echo e(t('Teachers Count')); ?></th>
                <th class="text-start"><?php echo e(t('Approval')); ?></th>
                <th class="text-start"><?php echo e(t('Activation')); ?></th>
                <th class="text-start"><?php echo e(t('Last login')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal fade" tabindex="-1" id="activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Supervisor Activation')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="activation_form">
                        <div class="mb-2">
                            <label class="mb-2"><?php echo e(t('Approval Status')); ?> :</label>
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
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_activation"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

    <script>
        var DELETE_URL = "<?php echo e(route('manager.supervisor.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.supervisor.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'supervisor_data', name: 'supervisor_data'},
            {data: 'teachers_count', name: 'teachers_count'},
            {data: 'approved', name: 'approved'},
            {data: 'active', name: 'active'},
            {data: 'last_login', name: 'last_login'},
            {data: 'actions', name: 'actions'}
        ];
        $(document).on('click', '#btn_activation',function(){
            $('#activation_modal').modal('hide')
            showLoadingModal()
            let data = getFilterData();
            data['activation_data'] = getFormDataAsObject('activation_form')
            data['_token'] = '<?php echo e(csrf_token()); ?>'
            $('#activation_form').find('select').val('').trigger('change')
            $.ajax({
                url: "<?php echo e(route('manager.supervisor.activation')); ?>",
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



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/supervisor/index.blade.php ENDPATH**/ ?>