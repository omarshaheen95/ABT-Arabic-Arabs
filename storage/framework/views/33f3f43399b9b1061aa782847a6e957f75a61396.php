<?php $__env->startSection('content'); ?>
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> <?php echo e(w('Login')); ?> </h1>
                            <p><?php echo e(w('Enter the email and password and start your Arabic learning journey')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">
                                <form class="col-form" action="/login" method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="<?php echo e(w('Email')); ?>">
                                                <?php if($errors->has('email')): ?>
                                                    <span class="help-block">
                                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="<?php echo e(w('Password')); ?>">
                                                <?php if($errors->has('password')): ?>
                                                    <span class="help-block">
                                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block"><?php echo e(w('Login')); ?> <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <a href="/password/reset"><?php echo e(w('Forget password ?')); ?></a>
                                        </div>
                                        <div class="col-lg-6 mt-4 text-left">
                                            <a href="/register"><?php echo e(w('Create new account')); ?></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END SLIDER -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/old_auth/login_old.blade.php ENDPATH**/ ?>