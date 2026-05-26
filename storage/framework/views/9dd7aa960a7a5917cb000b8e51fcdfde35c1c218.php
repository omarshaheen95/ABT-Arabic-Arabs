<?php $__env->startSection('title'); ?>
<?php echo e(t('Reset')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('status')): ?>
        <div class="alert alert-success">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <main class="login-page login-student">
        <div class="login-form">
            <div class="logo">
                <img src="<?php echo e(!settingCache('logo')? asset('logo.svg'):asset(settingCache('logo'))); ?>" alt="">
            </div>
            <div class="header">
                <div class="pic">
                    <img src="<?php echo e(asset('assets_v1/web_assets/img/forget-password.svg')); ?>" alt="">
                </div>
                <h1 class="title"><?php echo e(t('Reset Password')); ?></h1>
                <h2 class="info"><?php echo e(t('Please enter our registered email')); ?></h2>
            </div>
            <div class="form">
                <form method="POST" action="<?php echo e(url('/teacher/password/email')); ?>" class="needs-validation " novalidate>
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="password" class="form-label d-block">
                            <div class="d-flex justify-content-between">
                                <span><?php echo e(t('Email')); ?></span>
                                <a href="/teacher/login" class="link"><?php echo e(t('Login')); ?></a>
                            </div>
                        </label>
                        <div class="form-icon">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="24" viewBox="0 0 27 24">
                                        <path id="Icon"
                                              d="M7.834,0A7.827,7.827,0,0,0,0,7.794v8.412A7.814,7.814,0,0,0,7.834,24H19.166A7.838,7.838,0,0,0,27,16.206V14.258a.953.953,0,0,0-.955-.95l-.013.023a.954.954,0,0,0-.955.95v1.925a5.957,5.957,0,0,1-5.911,5.882H7.834a5.957,5.957,0,0,1-5.911-5.882V7.794A5.956,5.956,0,0,1,7.834,1.913H19.166a5.956,5.956,0,0,1,5.911,5.881.968.968,0,0,0,1.922,0A7.839,7.839,0,0,0,19.166,0Zm3.033,10.694L5.3,15.125a.959.959,0,0,0-.143,1.342A.947.947,0,0,0,6.5,16.61l5.612-4.42a1.943,1.943,0,0,1,2.389,0l5.553,4.42h.012a.97.97,0,0,0,1.349-.143.946.946,0,0,0-.155-1.342L15.7,10.694a3.871,3.871,0,0,0-4.837,0Z"
                                              transform="translate(27 24) rotate(180)" fill="#172239"/>
                                    </svg>
                                </span>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="form-control"
                                   placeholder="ex: example@domain.com" required>
                        </div>
                        <?php if($errors->has('email')): ?>
                            <span class="text-danger mt-2"><strong><?php echo e($errors->first('email')); ?></strong></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn btn-theme btn-submit w-100">
                            <span class="spinner-border spinner-border-sm d-none"></span>
                            <span class="text"> <?php echo e(t('Reset Password')); ?></span>
                        </button>
                    </div>















                </form>
            </div>
        </div>
    </main>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('general.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/old_auth/passwords/email.blade.php ENDPATH**/ ?>