<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> ترقية الإشتراك </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> ترقية الإشتراك </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <div class="profile-card form form-login">
                        <div class="profile-header">
                            <div class="title"> تغيير كلمة المرور </div>
                        </div>
                        <form id="form_password" action="<?php echo e(route('post_package_upgrade')); ?>" method="post" class="needs-validation" novalidate>
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="password" class="form-label"> الصف </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('web_assets/img/password.svg')); ?>" alt="">
                                    </div>
                                    <select class="form-control form-select" name="grade_id" required>
                                        <option value="" selected disabled>اختر صف</option>
                                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($grade->id); ?>" <?php echo e(isset($user) && $user->grade_id == $grade->id ? 'selected':''); ?>>الصف <?php echo e($grade->grade_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">الباقة </label>
                                <div class="form-control-icon">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('web_assets/img/password.svg')); ?>" alt="">
                                    </div>
                                    <select class="form-control form-select" name="package_id" required>
                                        <option value="" selected disabled>اختر باقة</option>
                                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($package->id); ?>" <?php echo e(isset($user) && $user->package_id == $package->id ? 'selected':''); ?>>
                                                <?php echo e($package->name); ?>  - <?php echo e($package->price); ?>$ - <?php echo e($package->days); ?> يوم
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-theme btn-submit">
                                    <span class="spinner-border d-none"></span>
                                    <span class="text"> ترقية الإشتراك </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/settings/packages.blade.php ENDPATH**/ ?>