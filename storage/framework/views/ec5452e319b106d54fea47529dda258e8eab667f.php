
<?php $__env->startSection('title'); ?>
    <?php echo e(isset($manager) ? 'Edit Manager' : 'Add Manager'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item text-muted"><a href="<?php echo e(route('manager.manager.index')); ?>" class=""><?php echo e(t('Managers')); ?></a></li>

    <li class="breadcrumb-item text-muted">
        <?php echo e(isset($manager) ? t('Edit Manager' ) : t('Add Manager')); ?>

    </li>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row col-12">
            <form class="form" id="form_data"
                      action="<?php echo e(isset($manager) ? route('manager.manager.update', $manager->id):route('manager.manager.store')); ?>"
                      method="post" autocomplete="off">
                    <?php echo csrf_field(); ?>

                    <?php if(isset($manager)): ?>
                       <?php echo method_field('PATCH'); ?>
                    <?php endif; ?>
                <div class="form-group row">
                    <div class="col-lg-3">
                        <label class="mb-2"><?php echo e(t('Name')); ?> : </label>
                        <input name="name" type="text" placeholder="<?php echo e(t('Name')); ?>"
                               class="form-control"
                               value="<?php echo e(isset($manager) ? $manager->name : old("name")); ?>"
                        />
                    </div>
                    <div class="col-lg-4">
                        <label class="mb-2"><?php echo e(t('Email')); ?> :</label>
                        <input name="email" type="text" placeholder="<?php echo e(t('Email')); ?>"
                               class="form-control" autocomplete="off"
                               value="<?php echo e(isset($manager) ? $manager->email : old("email")); ?>"/>
                    </div>
                    <div class="col-lg-3">
                        <label class="mb-2"><?php echo e(t('Password')); ?> :</label>
                        <input name="password" type="text" placeholder="<?php echo e(t('Password')); ?>" class="form-control" <?php if(!isset($manager)): ?> value="123456" <?php endif; ?>/>
                    </div>
                    <div class="col-lg-2">
                        <label class="mb-2"><?php echo e(t(' Status')); ?> :</label>

                        <div class="form-check form-switch form-check-custom form-check-solid mt-1">
                            <input class="form-check-input" type="checkbox" value="1" id="flexSwitchDefault"
                                   <?php echo e(isset($manager) && $manager->active == 1 ? 'checked' :''); ?> name="active"
                            />
                        </div>
                    </div>

                </div>
                        <div class="row my-5">
                            <div class="separator separator-content my-4"></div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-2"><?php echo e(t('Submit')); ?></button>
                            </div>
                        </div>
                </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
            <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\ManagerRequest::class, '#form_data'); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/managers/edit.blade.php ENDPATH**/ ?>