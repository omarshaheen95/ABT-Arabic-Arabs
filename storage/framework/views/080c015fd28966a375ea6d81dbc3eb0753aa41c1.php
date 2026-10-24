

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('cdn_files/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startPush('breadcrumb'); ?>
        <li class="breadcrumb-item">
            <?php echo e($title); ?>

        </li>
    <?php $__env->stopPush(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo e(route('manager.import_users_files.create')); ?>" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    <?php echo e(t('Import File')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th class="text-start"><?php echo e(t('School')); ?></th>
                        <th class="text-start"><?php echo e(t('File')); ?></th>
                        <th class="text-start"><?php echo e(t('Created Students')); ?></th>
                        <th class="text-start"><?php echo e(t('Updated Students')); ?></th>
                        <th class="text-start"><?php echo e(t('Failed Data Students')); ?></th>
                        <th class="text-start"><?php echo e(t('Status')); ?></th>
                        <th class="text-start"><?php echo e(t('Creation Date')); ?></th>
                        <th class="text-start"><?php echo e(t('Actions')); ?></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(t('Confirm Delete')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    <?php echo e(csrf_field()); ?>

                    <div class="modal-body">
                        <h5><?php echo e(t('Are You Sure To Delete The Selected Record ?')); ?></h5>
                        <br />
                        <p><?php echo e(t('Deleting The Record Will Delete All Records Related To It')); ?></p>
                        <br>
                        <div class="form-group row">
                            <label class="col-6 col-form-label font-weight-bold"><?php echo e(t('Delete students when deleting the import file')); ?></label>
                            <div class="col-3">
                                        <span class="kt-switch">
                                            <label class="mb-2">
                                            <input type="checkbox" value="1" name="delete_students">
                                            <span></span>
                                            </label>
                                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(t('Cancel')); ?></button>
                        <button type="submit" class="btn btn-warning"><?php echo e(t('Delete')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- DataTables -->
    <script src="<?php echo e(asset('cdn_files/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('cdn_files/dataTables.bootstrap4.min.js')); ?>"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '<?php echo e(route("manager.import_users_files.destroy", ":id")); ?>';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: `<'row'<'col-sm-12'tr>>
      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

                    <?php if(app()->getLocale() == 'ar'): ?>
                    language: {
                        url: "<?php echo e(asset('cdn_files/Arabic.json')); ?>"
                    },
                    <?php endif; ?>
                    ajax: {
                        url : '<?php echo e(route('manager.import_users_files.index')); ?>',
                        data: function (d) {
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'school', name: 'school'},
                        {data: 'original_file_name', name: 'original_file_name'},
                        {data: 'created_rows_count', name: 'created_rows_count'},
                        {data: 'updated_rows_count', name: 'updated_rows_count'},
                        {data: 'failed_rows_count', name: 'failed_rows_count'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/import_old/index.blade.php ENDPATH**/ ?>