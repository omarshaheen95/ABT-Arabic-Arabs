
<?php $__env->startSection('title', $title); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">
        <div class="col-3 mb-2">
            <label class="mb-1"><?php echo e(t('Name')); ?>:</label>
            <input type="text" name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>"/>
        </div>

        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('School')); ?>:</label>
            <select name="school_id" class="form-control form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select School')); ?>" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Status')); ?>:</label>
            <select name="status" class="form-control form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select Status')); ?>" data-hide-search="true" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>"><?php echo e($status); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-lg-3  mb-2">
            <label class="mb-2"><?php echo e(t('Type')); ?>:</label>
            <select name="model_type" class="form-control form-select" data-control="select2"
                    data-placeholder="<?php echo e(t('Select Type')); ?>" data-hide-search="true" data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = ['User', 'Teacher']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('actions'); ?>
    <div class="dropdown" id="actions_dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete import files')): ?>
                <li><a class="dropdown-item" href="<?php echo e(route('manager.import_files.create', ['type' => 'User'])); ?>"><?php echo e(t('Import Students')); ?></a>
                </li>
                <li><a class="dropdown-item" href="<?php echo e(route('manager.import_files.create', ['type' => 'Teacher'])); ?>"><?php echo e(t('Import Teachers')); ?></a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete import files')): ?>
                <li><a class="dropdown-item text-danger d-none checked-visible delete_row" href="#!"><?php echo e(t('Delete')); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <table class="table table-row-bordered table-bordered gy-5" id="datatable">
        <thead>
        <tr class="fw-semibold fs-6 text-gray-800">
            <th class="text-start"></th>
            <th class="text-start"><?php echo e(t('School')); ?></th>
            <th class="text-start"><?php echo e(t('File')); ?></th>
            <th class="text-start"><?php echo e(t('Type')); ?></th>
            <th class="text-start"><?php echo e(t('Process')); ?></th>
            <th class="text-start"><?php echo e(t('Created Rows')); ?></th>
            <th class="text-start"><?php echo e(t('Updated Rows')); ?></th>
            <th class="text-start"><?php echo e(t('Deleted Rows')); ?></th>
            <th class="text-start"><?php echo e(t('Failed Data Rows')); ?></th>
            <th class="text-start"><?php echo e(t('Status')); ?></th>
            <th class="text-start"><?php echo e(t('Creation Date')); ?></th>
            <th class="text-start"><?php echo e(t('Actions')); ?></th>
        </tr>
        </thead>
    </table>
    <div class="modal fade" tabindex="-1" id="delete_file_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Delete File')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <p style="font-weight: normal;font-size: 18px"><?php echo e(t('are sure of the deleting process ?')); ?></p>
                    <div class="col-lg-12  d-flex align-items-center p-0">
                        <p class="m-0 p-0"
                           style="font-weight: normal;font-size: 14px"><?php echo e(t('Delete rows when deleting the import file')); ?></p>
                        <div class="form-check form-check-custom form-check-solid mx-2">
                            <input id="delete_rows" class="form-check-input" type="checkbox" value="1"
                                   name="delete_rows"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btn_close" type="button" class="btn btn-light"
                            data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-danger" onclick="deleteRows()"><?php echo e(t('Delete')); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script>
        
        var TABLE_URL = "<?php echo e(route('manager.import_files.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'original_file_name', name: 'original_file_name'},
            {data: 'process_type', name: 'process_type'},
            {data: 'model_type', name: 'model_type'},
            {data: 'created_rows_count', name: 'created_rows_count'},
            {data: 'updated_rows_count', name: 'updated_rows_count'},
            {data: 'deleted_rows_count', name: 'deleted_rows_count'},
            {data: 'failed_rows_count', name: 'failed_rows_count'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions'}
        ];
        let id = null; //id for deleted row

        $(document).on('click', '.delete_row', (function (e) {
            e.preventDefault();
            id = $(this).data('id')
            $('#delete_file_modal').modal('show')
        }))


        function deleteRows() {
            let row_id = [];
            if (!id) {
                $("input:checkbox[name='rows[]']:checked").each(function () {
                    row_id.push($(this).val());
                });
            } else {
                row_id.push(id);
            }

            let request_data = {
                'row_id': row_id,
                '_token': $('meta[name="csrf-token"]').attr('content'),
                '_method': 'DELETE',
            }

            if ($('#delete_rows').is(':checked')) {
                $('#delete_rows').prop('checked', false)
                request_data['delete_rows'] = true
            }

            $.ajax({
                type: "POST",
                url: "<?php echo e(route('manager.import_files.delete')); ?>",
                data: request_data, //set data
                success: function (result) {
                    console.log(result)
                    $('.group-checkable').prop('checked', false);
                    checkedVisible(false)
                    table.DataTable().draw(false);
                    Swal.fire("", result.message, "success")
                    table.DataTable().draw(false);
                },
                error: function (error) {
                    Swal.fire("", data.message, "error")
                }
            })
            id = null
            $('#btn_close').trigger('click')
        }

    </script>
    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import/index.blade.php ENDPATH**/ ?>