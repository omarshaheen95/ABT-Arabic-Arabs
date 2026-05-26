

<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add lesson assignments')): ?>
        <a href="<?php echo e(route(getGuard().'.lesson_assignment.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
            <i class="la la-plus"></i>
            <?php echo e(t('Add Assignment')); ?>

        </a>
    <?php endif; ?>

    <div class="dropdown with-filter">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export lesson assignments')): ?>
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('<?php echo e(route(getGuard().'.lesson_assignment.export')); ?>')"><?php echo e(t('Export')); ?></a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete lesson assignments')): ?>
                    <li><a class="dropdown-item text-danger d-none checked-visible delete_assignment" href="#!"><?php echo e(t('Delete')); ?></a></li>
            <?php endif; ?>

        </ul>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('filter'); ?>
    <div class="row">

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
            <label><?php echo e(t('Grade')); ?> :</label>
            <select name="grade_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Grade')); ?>"
                    data-allow-clear="true">
                <option></option>
                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->id); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label><?php echo e(t('Lesson')); ?> :</label>
            <select  name="lesson_id" id="lesson_id" class="form-select" data-control="select2" data-placeholder="<?php echo e(t('Select Lesson')); ?>" data-allow-clear="true">
                <option></option>
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
                <th class="text-start"><?php echo e(t('School')); ?></th>
                <th class="text-start"><?php echo e(t('Level')); ?></th>
                <th class="text-start"><?php echo e(t('Lesson')); ?></th>
                <th class="text-start"><?php echo e(t('Date')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="delete_modal">
        <div class="modal-dialog">
            <form id="delete_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><?php echo e(t('Delete Lesson Assignment')); ?></h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                             aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <p style="font-weight: normal;font-size: 18px"><?php echo e(t('Do you want to delete this assignment ?')); ?></p>
                        <div class="col-lg-12  d-flex align-items-center p-0">
                            <p class="m-0 p-0"
                               style="font-weight: normal;font-size: 14px"><?php echo e(t('Delete users assignments when delete assignment')); ?></p>
                            <div class="form-check form-check-custom form-check-solid mx-2">
                                <input id="with_user_assignments" class="form-check-input" type="checkbox" value="1"
                                       name="with_user_assignments"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="btn_close" type="button" class="btn btn-light"
                                data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                        <button type="button" class="btn btn-danger" id="btn_delete_assignment"><?php echo e(t('Delete')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>

        var DELETE_URL = "<?php echo e(route(getGuard().'.lesson_assignment.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route(getGuard().'.lesson_assignment.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'school', name: 'school'},
            {data: 'level', name: 'level'},
            {data: 'lesson', name: 'lesson'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];

        initializeDateRangePicker();
        callAllEvents();

        //delete lesson assignment------------------------------------------
        let row_id = null;
        $(document).on('click','.delete_assignment',function (e) {
            e.preventDefault()
            if (typeof $(this).attr('data-id') !== 'undefined'){
                row_id = [$(this).attr('data-id')]
            }
            $('#delete_modal').modal('show')
        })
        $(document).on('click','#btn_delete_assignment',function (e) {
            e.preventDefault()
            let formData = getFormData('delete_form')
            let data = getFilterData()
            $.each(formData, function (key, val) {
                data[key] = val;
            });
            if (row_id){
                data['row_id'] = row_id
            }
            $('#delete_modal').modal('hide')
            showLoadingModal()
            $.ajax({
                type: "DELETE",
                url: DELETE_URL,
                data: data,
                success:function (result) {
                    hideLoadingModal()
                    toastr.success(result.message)
                    table.DataTable().draw(false);
                },
                error:function (error) {
                    hideLoadingModal()
                    toastr.error(error.responseJSON.message)
                }
            })
            row_id = null;
            resetForm('delete_form')
        })
        //*------------------------------------------------------------------

    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/lesson_assignment/index.blade.php ENDPATH**/ ?>