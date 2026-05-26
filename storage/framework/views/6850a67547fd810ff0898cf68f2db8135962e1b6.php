<?php $__env->startSection('p_style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/auth-modern.css')); ?>?v=2">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page">
        <div class="auth-container">
            <!-- Left Side - Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <div class="floating-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>
                    <img src="<?php echo e(asset('web_assets/img/forget-password.svg')); ?>" alt="Reset Password" class="main-illustration">
                    <h2 class="welcome-text">إنشاء كلمة مرور جديدة</h2>
                    <p class="welcome-subtitle">أدخل كلمة المرور الجديدة لتأمين حسابك</p>
                </div>
            </div>

            <!-- Right Side - Reset Form -->
            <div class="auth-form-section">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1 class="auth-title">إعادة تعيين كلمة المرور</h1>
                        <p class="auth-description">الرجاء إدخال كلمة المرور الجديدة</p>
                    </div>

                    <?php if(session('status')): ?>
                        <div class="alert-box alert-success">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(count($errors) > 0): ?>
                        <div class="alert-box alert-warning">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="/password/reset" method="post" class="auth-form needs-validation" novalidate>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="token" value="<?php echo e($token); ?>">

                        <!-- Email Field -->
                        <div class="input-group-custom">
                            <label class="input-label">البريد الالكتروني</label>
                            <div class="input-field-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    class="input-field"
                                    placeholder="example@domain.com"
                                    value="<?php echo e($email ?? old('email')); ?>"
                                    required
                                    autocomplete="off">
                            </div>
                            <?php if($errors->has('email')): ?>
                                <span class="input-error">
                                    <strong><?php echo e($errors->first('email')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Password Field -->
                        <div class="input-group-custom">
                            <label class="input-label">كلمة المرور</label>
                            <div class="input-field-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="input-field"
                                    placeholder="ادخل كلمة المرور الجديدة"
                                    required>
                                <button type="button" class="password-toggle" data-target="password">
                                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            <?php if($errors->has('password')): ?>
                                <span class="input-error">
                                    <strong><?php echo e($errors->first('password')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="input-group-custom">
                            <label class="input-label">تأكيد كلمة المرور</label>
                            <div class="input-field-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="input-field"
                                    placeholder="نأكيد كلمة المرور الجديدة"
                                    required>
                                <button type="button" class="password-toggle" data-target="password_confirmation">
                                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            <?php if($errors->has('password_confirmation')): ?>
                                <span class="input-error">
                                    <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-auth-primary">
                            <span class="spinner-border d-none"></span>
                            <span class="btn-text"><?php echo e(w('Reset Password')); ?></span>
                            <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>

                        <!-- Links -->
                        <div class="auth-footer">
                            <p class="footer-text">
                                <a href="/login" class="register-link"><?php echo e(w('Login')); ?></a>
                                <span style="margin: 0 0.5rem;">|</span>
                                <a href="/register" class="register-link"><?php echo e(w('Create new account')); ?></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        // Password Toggle for multiple fields
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const eyeOpen = this.querySelector('.eye-open');
                const eyeClosed = this.querySelector('.eye-closed');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.container_2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>