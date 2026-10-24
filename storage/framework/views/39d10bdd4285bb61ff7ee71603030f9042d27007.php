<?php $__env->startSection('title',$title); ?>


<?php $__env->startSection('actions'); ?>

    <div class="dropdown with-filter">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add users')): ?>
            <a href="<?php echo e(route('manager.user.create')); ?>" class="btn btn-primary btn-elevate btn-icon-sm me-2">
                <i class="la la-plus"></i>
                <?php echo e(t('Add User')); ?>

            </a>
        <?php endif; ?>

        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo e(t('Actions')); ?>

        </button>
        <ul class="dropdown-menu">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export users')): ?>
                <li><a class="dropdown-item" href="#!"
                       onclick="excelExport('<?php echo e(route('manager.user.export')); ?>')"><?php echo e(t('Export')); ?></a>
                </li>
                <li>
                    <a class="dropdown-item" href="#!" onclick="cardsExport(true)"><?php echo e(t('Cards')); ?></a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assign teacher')): ?>
                    <li>
                        <a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#add_users_teachers"><?php echo e(t('Assigned To Teacher')); ?></a>
                    </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('unassign teacher')): ?>
                    <li>
                        <a class="dropdown-item" href="#!" id="delete_users_teachers"><?php echo e(t('Unsigned Teacher')); ?></a>
                    </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users activation')): ?>
                    <li>
                        <a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#users_activation_modal"><?php echo e(t('Activation')); ?></a>
                    </li>
            <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update users grade')): ?>
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal"
                           data-bs-target="#users_update_grades_modal"><?php echo e(t('Update Grades')); ?></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restore deleted users')): ?>
                    <li id="restore-students" class="d-none"><a class="dropdown-item" href="#!" onclick="restore()"><?php echo e(t('Restore Students')); ?></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete users')): ?>
                    <li><a class="dropdown-item text-danger d-none checked-visible" href="#!" id="delete_rows"><?php echo e(t('Delete')); ?></a></li>
                <?php endif; ?>



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
            <label class="mb-2"><?php echo e(t('ID Number')); ?>:</label>
            <input type="text" name="id_number" class="form-control direct-search" placeholder="<?php echo e(t('ID Number')); ?>">
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
            <label class="mb-2"><?php echo e(t('School')); ?>:</label>
            <select class="form-select" name="school_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select School')); ?>">
                <option></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Teacher')); ?>:</label>
            <select class="form-select" name="teacher_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Teacher')); ?>">
                <option></option>
            </select>
        </div>
        <div class="col-3 mb-2">
            <label class="mb-2"><?php echo e(t('Year')); ?>:</label>
            <select class="form-select" name="year_id" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Year')); ?>">
                <option></option>
                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-2 mb-2">
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
            <label class="mb-2"><?php echo e(t('Section')); ?>:</label>
            <select class="form-select" name="section" data-control="select2" data-allow-clear="true"
                    data-placeholder="<?php echo e(t('Select Section')); ?>">
                <option></option>
                <?php $__currentLoopData = schoolSections(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($section); ?>"><?php echo e($section); ?></option>
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
            <label class="mb-2"><?php echo e(t('Register Date')); ?>:</label>
            <input id="register_date" class="form-control " placeholder="<?php echo e(t('Select Register Date')); ?>">
            <input type="hidden" id="start_register_date" name="start_register_date" value="">
            <input type="hidden" id="end_register_date" name="end_register_date" value="">
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label class="mb-2"><?php echo e(t('Login Date')); ?>:</label>
            <input id="login_at" class="form-control " placeholder="<?php echo e(t('Select Login Date')); ?>">
            <input type="hidden" id="start_login_at" name="start_login_at" value="">
            <input type="hidden" id="end_login_at" name="end_login_at" value="">
        </div>
        <div class="col-3 mb-2">
            <label class="mb-1"><?php echo e(t('Students Status')); ?>:</label>
            <select class="form-control form-select reset-no" data-hide-search="true" data-control="select2" data-placeholder="<?php echo e(t('Select Student Status')); ?>" name="deleted_at" id="students_status">
                <option value="1" selected><?php echo e(t('Not Deleted Students')); ?></option>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show deleted users')): ?>
                    <option value="2"><?php echo e(t('Deleted Students')); ?></option>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-2 mb-2">
            <label class="mb-1"><?php echo e(t('Archived Status')); ?>:</label>
            <select class="form-control form-select reset-no" data-hide-search="true" data-control="select2" data-placeholder="<?php echo e(t('Select Archived Status')); ?>" name="archived" id="archived">
                <option value="2" selected><?php echo e(t('Not Archived Students')); ?></option>
                
                <option value="1"><?php echo e(t('Archived Students')); ?></option>
                
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
                <th class="text-start"><?php echo e(t('School')); ?></th>
                <th class="text-start"><?php echo e(t('Dates')); ?></th>
                <th class="text-start"><?php echo e(t('Actions')); ?></th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="users_activation_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Users Activation')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="activation_form">
                        <div class="mb-2">
                            <label class="mb-2"><?php echo e(t('Activation Status')); ?> :</label>
                            <select name="activation_status" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                                <option></option>
                                <option value="1"><?php echo e(t('Activate')); ?></option>
                                <option value="2"><?php echo e(t('Deactivate')); ?></option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="mb-2"><?php echo e(t('Active To')); ?> :</label>
                            <input class="form-control form-control-solid" id="active_to_date" name="active_to_date"
                                   value="" placeholder="<?php echo e(t('Active to')); ?>"/>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_users_activation"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="add_users_teachers">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Users Teacher')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="users_teacher_form">
                        <div class="mb-2">
                            <label class="mb-2"><?php echo e(t('School')); ?> :</label>
                            <select name="teacher_school_id"  id="teacher_school_id" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select School')); ?>" data-allow-clear="true">
                                <option></option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="mb-2"><?php echo e(t('Teacher')); ?> :</label>
                            <select name="users_teacher_id" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Teacher')); ?>" data-allow-clear="true">
                                <option></option>
                            </select>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_users_teacher"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="users_update_grades_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo e(t('Users Grades')); ?></h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body d-flex flex-column">
                    <form id="users_grades_form">
                        <div class="mb-2">
                            <label><?php echo e(t('Move Grade')); ?> :</label>
                            <select name="move_grade" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Grade')); ?>" data-allow-clear="true">
                                <option></option>
                                <option value="1">1</option>
                                <option value="-1">-1</option>
                                <option value="2">2</option>
                                <option value="-2">-2</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label><?php echo e(t('Grade')); ?> :</label>
                            <select name="grade" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Grade')); ?>" data-allow-clear="true">
                                <option></option>
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label><?php echo e(t('Alternative Grade')); ?> :</label>
                            <select name="alternate_grade" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Alternative Grade')); ?>" data-allow-clear="true">
                                <option></option>
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade->id); ?>"><?php echo e($grade->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label><?php echo e(t('Assigned Year')); ?> :</label>
                            <select name="assigned_year_id" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Year')); ?>" data-allow-clear="true">
                                <option></option>
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label><?php echo e(t('Archived Status')); ?> :</label>
                            <select name="archived" class="form-select" data-control="select2"
                                    data-placeholder="<?php echo e(t('Select Status')); ?>" data-allow-clear="true">
                                <option></option>
                                <option value="1"><?php echo e(t('Archived')); ?></option>
                                <option value="2"><?php echo e(t('Not Archived')); ?></option>
                            </select>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(t('Close')); ?></button>
                    <button type="button" class="btn btn-primary" id="btn_users_update_grades"><?php echo e(t('Save')); ?></button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script>
        var UNSIGNED_TEACHER_URL = '<?php echo e(route('manager.user.unassigned-teacher')); ?>';
        var UNSIGNED_TEACHER = '<?php echo e(t('Unsigned Teacher')); ?>';
        var CONFIRM_DELETE_TEACHER_STUDENT = '<?php echo e(t('Do you want to delete teacher for selected students')); ?>';
        var CSRF = '<?php echo e(csrf_token()); ?>';
        var USER_ACTIVATION_URL = "<?php echo e(route('manager.user.activation')); ?>";
        var USER_UPDATE_GRADES_URL = "<?php echo e(route('manager.user.update_grades')); ?>";
        var ASSIGNED_TO_TEACHER_URL = "<?php echo e(route('manager.user.assigned-teacher')); ?>";
        var STUDENT_CARD_URL = "<?php echo e(route('manager.user.cards-export')); ?>";

        var DELETE_URL = "<?php echo e(route('manager.user.destroy')); ?>";
        var TABLE_URL = "<?php echo e(route('manager.user.index')); ?>";
        var TABLE_COLUMNS = [
            {data: 'id', name: 'id'},
            {data: 'student', name: 'student'},
            {data: 'school', name: 'school'},
            {data: 'dates', name: 'dates'},
            {data: 'actions', name: 'actions'},
        ];

        $('#students_status').on('change',function () {
            let value = $(this).val();
            if (value==='1'){ //Not deleted student
                //show actions for not deleted students
                $('.not-deleted-students').removeClass('d-none')
                // //show delete button
                $('#li_delete_rows').removeClass('d-none')
                $('#restore-students').addClass('d-none')
            }else {
                //hide actions for not deleted students
                $('.not-deleted-students').addClass('d-none')
                // //hide delete button
                $('#li_delete_rows').addClass('d-none')
                $('#restore-students').removeClass('d-none')

            }
            table.DataTable().draw(true);
        })

        //restore students
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restore deleted users')): ?>
        //restore students
        function restore(id = null) {
            let data = {
                '_token': '<?php echo e(csrf_token()); ?>'
            };

            if (!id) {
                id = [];
                $("table input:checkbox:checked").each(function () {
                    id.push($(this).val());
                });
                data['id'] = id;

                let school_id = $('select[name="school_id"]').val();
                if (id.length <= 0 && !school_id) {
                    toastr.error('<?php echo e(t('School is required')); ?>');
                    return;
                } else {
                    data['school_id'] = school_id;
                }

                let year_id = $('select[name="year_id"]').val();
                if (id.length <= 0 && !year_id) {
                    toastr.error('<?php echo e(t('Year is required')); ?>');
                    return;
                } else {
                    data['year_id'] = year_id;
                }
            }else {
                data['id']=id;
            }

            Swal.fire({
                title: '<?php echo e(t('Are you sure?')); ?>',
                text: '<?php echo e(t('Do you want to restore the selected students?')); ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?php echo e(t('Yes, restore!')); ?>',
                cancelButtonText: '<?php echo e(t('Cancel')); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingModal()
                    $.ajax({
                        type: "POST",
                        url: '<?php echo e(route('manager.user.restore')); ?>',
                        data: data,
                        success: function (result) {
                            hideLoadingModal()
                            Swal.fire({
                                icon: 'success',
                                title: '<?php echo e(t('Restored!')); ?>',
                                text: result.message,
                                //timer: 2000,
                                showConfirmButton: true
                            });
                            table.DataTable().draw(false);
                        },
                        error: function (error) {
                            hideLoadingModal()
                            Swal.fire({
                                icon: 'error',
                                title: '<?php echo e(t('Error')); ?>',
                                text: error.responseJSON?.message || '<?php echo e(t('Something went wrong')); ?>'
                            });
                        }
                    });
                }
            });
        }
        <?php endif; ?>

        // $('.modal').on('shown.bs.modal', function() {
        //     let parent = $(this)
        //     $('.form-select').select2({
        //         dropdownParent: parent
        //     }).focus();
        // });
    </script>

    <script src="<?php echo e(asset('assets_v1/js/datatable.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('assets_v1/js/manager/users.js')); ?>"></script>
    <script src="<?php echo e(asset('assets_v1/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
        getAndSetDataOnSelectChange('teacher_school_id','users_teacher_id',getTeacherBySchoolURL)
        getTeacherBySchool()
        getSectionBySchool()
        getSectionByTeacher()
    </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/user/index.blade.php ENDPATH**/ ?>