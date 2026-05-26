

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <form
        action="<?php echo e(isset($permission) ? route(getGuard().'.permission.update', $permission->id): route(getGuard().'.permission.store')); ?>"
        method="post" class="form" id="form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($permission)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row">

            <div class="row">
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Name')); ?></label>
                        <input type="text" name="name" class="form-control" placeholder="<?php echo e(t('Name')); ?>"
                               value="<?php echo e(isset($permission) ? $permission->name : old("name")); ?>" required>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Group')); ?></label>
                        <input type="text" name="group" class="form-control" placeholder="<?php echo e(t('Group')); ?>"
                               value="<?php echo e(isset($permission) ? $permission->group : old("group")); ?>" required>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(t('Guard')); ?></label>
                        <select name="guard_name" class="form-select" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Guard')); ?>" data-allow-clear="true" <?php if(isset($permission)  && $permission->roles->count()>0): ?> disabled <?php endif; ?>>
                            <option></option>
                            <?php $__currentLoopData = sysGuards(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($guard); ?>" <?php echo e(isset($permission) && $guard == $permission->guard_name ? 'selected':''); ?>><?php echo e(camelCaseText($guard)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php if(isset($permission) && $permission->roles->count()>0): ?>
                        <input type="hidden" name="guard_name" value="<?php echo e($permission->guard_name); ?>">
                    <?php endif; ?>
                </div>
                <div class="separator separator-content my-4"></div>
                <div class="col-12 row justify-content-center">
                    <div class="col-5">
                        <label><?php echo e(t('Roles')); ?></label>
                        <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
                            <?php if(isset($roles)): ?>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-2 d-flex flex-column justify-content-center align-items-center mt-5" >
                        <button type="button" id="multiselect_rightAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-forward"></i></button>
                        <button type="button" id="multiselect_rightSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-right"></i></button>
                        <button type="button" id="multiselect_leftSelected" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" id="multiselect_leftAll" class="btn btn-secondary btn-sm mb-1"><i class="fa fa-backward"></i></button>
                    </div>

                    <div class="col-5">
                        <label><?php echo e(t('Selected Roles')); ?></label>
                        <select name="roles[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">
                            <?php if(isset($selected_roles)): ?>
                                <?php $__currentLoopData = $selected_roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(isset($permission)?t('Update'):t('Submit')); ?></button>
                </div>
            </div>
        </div>

    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\General\RoleAndPermission\PermissionRequest::class, '#form-data'); ?>

    <script type="text/javascript" src="<?php echo e(asset('assets_v1/lib/multiselect/multiselect.min.js')); ?>"></script>
    <script>
        let roles = <?php echo json_encode($roles, 15, 512) ?>;
        //Filter permissions on guard name change by guard name
        $('select[name="guard_name"]').change(function () {
            let guard = $(this).val()
            let options = ``;
            roles.forEach(function (item, index) {
                if (guard === item.guard_name){
                    options += `<option value="${item.id}">${item.name}</option>`

                }
            })
            $('select[name="from[]"]').html(options)
            $('select[name="roles[]"]').html(null)
        })

        //Init multiselecet For Levels
        $('#multiselect').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control mb-2" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control mb-2" placeholder="Search..." />',
            },
            sort:false
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(getGuard().'.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/role_and_permission/permission/edit.blade.php ENDPATH**/ ?>