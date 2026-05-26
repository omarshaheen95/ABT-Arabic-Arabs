<?php $__env->startSection('content'); ?>
    <!-- START SLIDER -->
    <div id="slider" class="aos-item slider-bg theme-bg-secondary-light" data-aos="fade-in">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="section-warp">
                        <div class="section-header">
                            <h1 class="section-title"> استعادة كلمة المرور </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-item">
                        <div class="card">
                            <div class="card-body">

                                <form class="col-form" method="POST" action="/password/reset">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="token" value="<?php echo e($token); ?>">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php if(session('status')): ?>
                                                <div class="alert alert-success" role="alert">
                                                    <?php echo e(session('status')); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if(count($errors) > 0): ?>
                                                <div class="alert alert-warning">
                                                    <ul style="width: 100%;">
                                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li><?php echo e($error); ?></li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="البريد الإلكتروني" value="<?php echo e($email ?? old('email')); ?>">
                                                <?php if($errors->has('email')): ?>
                                                    <span class="help-block">
                                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password" placeholder="كلمة المرور">
                                                <?php if($errors->has('password')): ?>
                                                    <span class="help-block">
                                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="تأكيد كلمة المرور">
                                                <?php if($errors->has('password_confirmation')): ?>
                                                    <span class="help-block">
                                                        <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <button type="submit" class="theme-btn theme-btn-default btn-block">تغيير كلمة المرور <i class="flaticon-double-right-arrows-angles"></i></button>
                                        </div>
                                        <div class="col-lg-6 mt-4">
                                            <a href="/login">تسجيل الدخول</a>
                                        </div>
                                        <div class="col-lg-6 mt-4 text-left">
                                            <a href="/register">تسجيل حساب جديد</a>
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

<?php echo $__env->make('layouts.container_2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/old_auth/passwords/reset.blade.php ENDPATH**/ ?>