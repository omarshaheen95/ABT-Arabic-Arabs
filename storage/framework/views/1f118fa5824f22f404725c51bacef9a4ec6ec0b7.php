
<?php $__env->startSection('title',$title); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted">
        <a href="<?php echo e($index_route); ?>" class="text-muted">
            <?php echo e(t(camelCaseText($user_guard))); ?>

        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <?php echo e(t('Manage Roles & Permission')); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('actions'); ?>
    <a onclick="selectAll()" class="btn btn-primary font-weight-bolder">
        <i class="la la-check-circle"></i><?php echo e(t('Select All Permissions')); ?></a>
    <button onclick="$('#form_data').submit()" class="btn btn-warning font-weight-bolder">
        <i class="la la-check-circle"></i><?php echo e(t('Submit')); ?></button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card m-0 p-0">
                <!--begin::Form-->
                <form class="form" id="form_data"
                      action="<?php echo e($update_route); ?>"
                      method="post">
                     <?php echo csrf_field(); ?>

                    <input name="guard_id" type="hidden" value="<?php echo e($guard_id); ?>">
                    <input name="user_guard" type="hidden" value="<?php echo e($user_guard); ?>">

                    <div class="card-body m-0 p-0">

                        <h4>
                            <?php echo e(t('Roles')); ?>

                        </h4>

                        <div class="col-12 mb-2">
                            <div class="form-group">
                                <select name="roles[]" class="form-select" data-control="select2"
                                        data-placeholder="<?php echo e(t('Select Roles')); ?>" data-allow-clear="true" multiple>
                                    <option></option>
                                    <?php if(isset($roles)): ?>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->name); ?>" <?php echo e(isset($user_roles)  && $user_roles->whereIn('role_id',$role->id)->first()? 'selected':''); ?>><?php echo e(camelCaseText($role->name)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <?php if(isset($permission) && $permission->roles->count()>0): ?>
                                <input type="hidden" name="guard_name" value="<?php echo e($permission->guard_name); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="separator content-separator my-5"></div>
                        <h2 class="mb-4">
                            <?php echo e(t('Direct Permissions')); ?>

                        </h2>
                        <div class="form-group row">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 d-flex flex-column">
                                    <?php
                                     $permissions_ids = $values->pluck('id')->toArray();
                                     $guard_permissions_ids = collect($user_permissions)->pluck('permission_id')->toArray();
                                     $c_status = false;
                                     foreach ($permissions_ids as $value) {
                                        if (in_array($value, $guard_permissions_ids)) {
                                            $c_status= true;
                                        } else {
                                            $c_status= false;
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="d-flex my-5 align-items-center">
                                        <h4 class="m-0"><?php echo e(t(camelCaseText($key))); ?></h4>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm ms-2">
                                            <input id="<?php echo e($key); ?>_checkbox" class="form-check-input" type="checkbox" value=""  <?php echo e($c_status?'checked':''); ?>

                                            onclick="checkAllPermissions('<?php echo e($key); ?>_checkbox','<?php echo e('class_'.$key); ?>')"  <?php echo e($c_status?'checked':''); ?>/>
                                        </div>
                                    </div>
                                <div class="card bg-gray-100">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php $__currentLoopData = $values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                $status =
                                                collect($user_permissions)->where('permission_id',$permission->id)->first();
                                                ?>
                                                <div class="col-3 form-check form-check-custom form-check-solid form-check-sm p-2">
                                                    <input class="form-check-input <?php echo e('class_'.$key); ?>" type="checkbox" name="permissions[]" value="<?php echo e($permission->name); ?>" id="flexRadioLg" <?php echo e($status?'checked':''); ?>/>
                                                    <label class="form-check-label text-dark" for="flexRadioLg">
                                                        <?php echo e(t(camelCaseText($permission->name))); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                    <div class="card-footer mt-4">
                        <div class="row">
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-2"><?php echo e(t('Submit')); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        function checkAllPermissions(input_id,m_class) {
            let is_checked = $('#'+input_id).is(':checked')
            $('.'+m_class).each((index,item)=>{
                if (is_checked){ //when checked
                    $(item).prop('checked',true)

                }else {//when not checked
                    $(item).prop('checked',false)
                }
            })
        }
        function selectAll(){
            $('#form_data input:checkbox').prop('checked',true);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/role_and_permission/user_role_and_permission.blade.php ENDPATH**/ ?>