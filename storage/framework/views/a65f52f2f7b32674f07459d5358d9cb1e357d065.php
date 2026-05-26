<?php $__env->startPush('style'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/participant-info.css')); ?>?v=2" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'participant-info'); ?>

<?php $__env->startSection('content'); ?>
    <div class="participant-container">
        <!-- Header Section - User Info Only -->
        <header class="participant-header" style="background: white; padding: 24px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 24px;">
                <!-- Avatar -->
                <div class="profile-avatar" id="participantAvatar" style="flex-shrink: 0;">
                    <img src="<?php echo e($user->school->logo ?? asset('assets/images/illustrations/avatar.svg')); ?>" alt="User avatar" class="avatar-img" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #667eea; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                    <div class="default-avatar" style="display: none; width: 100px; height: 100px; border-radius: 50%; background: #e2e8f0;"></div>
                </div>

                <!-- User Info Container -->
                <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                    <div>
                        <h1 class="profile-name" id="participantName" style="font-size: 28px; margin: 0; font-weight: 700; color: #2d3748;"><?php echo e($user->name ?? 'User'); ?></h1>
                        <p class="profile-level" id="participantLevel" style="font-size: 16px; margin: 4px 0 0 0; color: #718096;"><?php echo e($user->grade_name ?? '—'); ?></p>
                    </div>

                    <?php if($user->package): ?>
                        <div style="display: flex; flex-direction: column; gap: 4px; padding-top: 8px; border-top: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 14px; color: #4a5568; font-weight: 600;">الباقة:</span>
                                <span style="font-size: 14px; color: #2d3748;"><?php echo e($user->package->name ?? '—'); ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 13px; color: #4a5568; font-weight: 600;">الفترة:</span>
                                <span style="font-size: 13px; color: #718096;">
                                    <?php echo e($user->active_from ? $user->active_from->format('Y-m-d') : '—'); ?> → <?php echo e($user->active_to ? $user->active_to->format('Y-m-d') : '—'); ?>

                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Edit Button -->
                <div style="flex-shrink: 0;">
                    <button class="edit-profile-btn" id="editProfileBtn" aria-label="Edit profile">
                        تعديل الحساب
                    </button>
                </div>
            </div>
        </header>

        <!-- Progress Info -->
        <div class="participant-user-level-info" style="display: flex; align-items: center; gap: 20px;">
            <img
                class="participant-level-badge"
                src="<?php echo e(asset('user_assets/images/illustrations/level-badge.svg')); ?>"
                alt="Level badge"
                style="width: 80px; height: 80px;"
            />
            <div class="participant-level-details" style="flex: 1;">
                <p class="participant-level-title" style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #2d3748;">
                    <span class="participant-level-name" id="levelName"><?php echo e($user_data['levelName']); ?></span>
                </p>
                <div class="participant-level-progress-container" style="position: relative; width: 100%;">
                    <div class="participant-level-progress-bg" style="width: 100%; height: 32px; background: #e2e8f0; border-radius: 16px;"></div>
                    <div
                        class="participant-level-progress-bar"
                        role="progressbar"
                        aria-valuenow="<?php echo e($user_data['currentXp']); ?>"
                        aria-valuemin="0"
                        aria-valuemax="<?php echo e($user_data['maxXp']); ?>"
                        aria-label="Level progress: <?php echo e($user_data['currentXp']); ?> out of <?php echo e($user_data['maxXp']); ?> XP"
                        id="levelProgressBar"
                        style="position: absolute; top: 0; left: 0; height: 32px; border-radius: 16px; transition: width 0.3s ease;"
                    ></div>
                    <span class="participant-level-progress-text" id="levelProgressText" style="position: absolute; top: 84%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: 600; font-size: 14px;"><?php echo e($user_data['currentXp']); ?>/<?php echo e($user_data['maxXp']); ?>xp</span>
                </div>
            </div>
        </div>


        <!-- Overview Section -->
        <section class="overview-section" aria-label="Overview">
            <h2 class="section-title"><?php echo e(t('Overview')); ?></h2>
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="card-icon-container">
                        <div class="card-icon card-icon--streak">
                            <img src="<?php echo e(asset('user_assets/images/illustrations/streak.svg')); ?>" alt="Streak icon" width="58" height="58" />
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-value" id="streakDays"><?php echo e($user_data['streak']); ?> يوم</h3>
                        <p class="card-label">استمرارية الحماس</p>
                    </div>
                </div>













                <div class="overview-card">
                    <div class="card-icon-container">
                        <div class="card-icon card-icon--xp">
                            <img src="<?php echo e(asset('user_assets/images/illustrations/diamond.svg')); ?>" alt="Streak icon" width="58" height="58" />
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-value" id="totalXp"><?php echo e($user_data['currentXp']); ?> xp</h3>
                        <p class="card-label">مجموع النقاط</p>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- Edit Profile Modal -->
    <div class="modal-overlay" id="editProfileModal" aria-hidden="true">
        <div class="modal-container" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
            <div class="modal-content-wrapper">
                <button class="modal-close-btn" id="closeModalBtn" aria-label="Close dialog">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="#666" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <div class="modal-inner-content">
                    <h2 class="modal-title" id="modalTitle">تعديل الحساب</h2>

                    <div class="modal-avatar-section">
                        <div class="modal-avatar">
                            <img src="<?php echo e($user->image ?? asset('assets/images/illustrations/avatar.svg')); ?>" alt="User avatar" class="modal-avatar-img" id="modalAvatarImg" />






                            <input type="file" id="avatarInput" accept="image/*" style="display: none;" aria-label="Upload avatar image" />
                        </div>
                    </div>

                    <form class="modal-form" id="editProfileForm">
                        <div class="modal-input-group">
                            <label class="modal-label" for="profileName">
                                الاسم<span class="required-star">*</span>
                            </label>
                            <input
                                type="text"
                                id="profileName"
                                class="modal-input"
                                value="<?php echo e($user->name ?? ''); ?>"
                                required
                                aria-required="true"
                                style="background-color: #f5f5f5; cursor: not-allowed;" disabled
                            />
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label" for="profileEmail">
                                البريد الالكتروني<span class="required-star">*</span>
                            </label>
                            <input
                                type="email"
                                id="profileEmail"
                                class="modal-input"
                                value="<?php echo e($user->email ?? ''); ?>"
                                required
                                aria-required="true"
                                style="background-color: #f5f5f5; cursor: not-allowed;" disabled
                            />
                        </div>

                        <!-- Read-only User Information -->
                        <div class="modal-input-group">
                            <label class="modal-label">الصف</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->grade_name ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">المدرسة</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->school->name ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">المعلم</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->teacher->name ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">الهاتف</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->mobile ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">الجنس</label>
                            <div class="modal-input text-capitalize" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->gender ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">نشط حتى</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e(\Carbon\Carbon::parse($user->active_to)->format('d M Y') ?? '—'); ?>

                            </div>
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label">تم الانضمام في</label>
                            <div class="modal-input" style="background-color: #f5f5f5; cursor: not-allowed;" disabled>
                                <?php echo e($user->created_at->format('d M Y') ?? '—'); ?>

                            </div>
                        </div>



                        <button type="button" class="modal-change-password" id="changePasswordBtn">
                            تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal-overlay" id="changePasswordModal" aria-hidden="true">
        <div class="modal-container" role="dialog" aria-labelledby="changePasswordModalTitle" aria-modal="true">
            <div class="modal-content-wrapper">
                <button class="modal-close-btn" id="closeChangePasswordModalBtn" aria-label="Close dialog">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="#666" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <div class="modal-inner-content">
                    <h2 class="modal-title" id="changePasswordModalTitle">تغيير كلمة المرور</h2>

                    <form class="modal-form" id="changePasswordForm">
                        <div class="modal-input-group">
                            <label class="modal-label" for="currentPassword">
                                كلمة المرور الحالية<span class="required-star">*</span>
                            </label>
                            <input
                                type="password"
                                id="currentPassword"
                                class="modal-input"
                                required
                                aria-required="true"
                                autocomplete="current-password"
                            />
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label" for="newPassword">
                                كلمة المرور الجديدة<span class="required-star">*</span>
                            </label>
                            <input
                                type="password"
                                id="newPassword"
                                class="modal-input"
                                required
                                aria-required="true"
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="modal-input-group">
                            <label class="modal-label" for="confirmPassword">
                                تأكيد كلمة المرور الجديدة<span class="required-star">*</span>
                            </label>
                            <input
                                type="password"
                                id="confirmPassword"
                                class="modal-input"
                                required
                                aria-required="true"
                                autocomplete="new-password"
                            />
                        </div>

                        <button type="submit" class="modal-save-btn">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.general.components.loading_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startPush('script'); ?>
    <script>
    </script>
    <script src="<?php echo e(asset('user_assets/js/pages/participant-info.js')); ?>?v=1"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/profile.blade.php ENDPATH**/ ?>