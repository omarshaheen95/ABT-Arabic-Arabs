<?php $__env->startSection('title', 'User Profile'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">

                    <div class="text-center mt-3">
                        <img src="<?php echo e($user->school->logo ?? asset('assets/media/users/default.jpg')); ?>"
                             alt="Profile Picture"
                             class="rounded-circle shadow-lg p-2"
                             style="width: 140px; height: 140px; object-fit: fill;">
                    </div>
                    <hr>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Name')); ?></div>
                            <div class="col-md-8"><?php echo e($user->name ?? '—'); ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Grade')); ?></div>
                            <div class="col-md-8"><?php echo e($user->grade->name ?? '—'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('School')); ?></div>
                            <div class="col-md-8"><?php echo e($user->school->name ?? '—'); ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Teacher')); ?></div>
                            <div class="col-md-8"><?php echo e($user->teacher->name ?? '—'); ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Email')); ?></div>
                            <div class="col-md-8"><?php echo e($user->email ?? '—'); ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Mobile')); ?></div>
                            <div class="col-md-8"><?php echo e($user->mobile ?? '—'); ?></div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Gender')); ?></div>
                            <div class="col-md-8 text-capitalize"><?php echo e($user->gender ?? '—'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Active to')); ?></div>
                            <div class="col-md-8">
                                <?php echo e(\Carbon\Carbon::parse($user->active_to)->format('d M Y')); ?>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold"><?php echo e(t('Joined On')); ?></div>
                            <div class="col-md-8"><?php echo e($user->created_at->format('d M Y')); ?></div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/settings/profile-show.blade.php ENDPATH**/ ?>