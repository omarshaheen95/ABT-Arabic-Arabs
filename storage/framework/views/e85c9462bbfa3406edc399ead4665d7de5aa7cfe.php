<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/test-result.css')); ?>" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'quiz-result'); ?>

<?php $__env->startSection('content'); ?>
    <div class="quiz-result-container">
        <div class="quiz-result-content-wraper">
            <div class="quiz-result-content-container">
                <!-- Page title -->
                <div class="result-header">
                    <h1 class="result-title-ar" style="margin-bottom: 20px;!important;"><?php echo e($lesson->name); ?></h1>
                    <h1 class="result-title-ar">نتيجة الاختبار</h1>

                </div>

                <!-- Celebration illustration -->
                <div class="result-illustration">
                    <img src="<?php echo e(asset('user_assets/images/illustrations/quiz-result.svg')); ?>" alt="Quiz result celebration" class="celebration-image" />
                </div>

                <!-- Result cards -->
                <div class="result-cards">
                    <!-- Total XP card -->
                    <div class="result-card xp-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="<?php echo e(asset('user_assets/images/illustrations/diamond.svg')); ?>" alt="Diamond icon" />
                            </div>
                            <div class="card-value"><?php echo e($xpEarned); ?></div>
                        </div>
                        <div class="card-label">مجموع النقاط</div>
                    </div>

                    <!-- Timing card -->
                    <div class="result-card timing-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="<?php echo e(asset('user_assets/images/illustrations/clock.svg')); ?>" alt="Clock icon" />
                            </div>
                            <div class="card-value"><?php echo e($timingMinutes); ?> min</div>
                        </div>
                        <div class="card-label">الوقت</div>
                    </div>

                    <!-- Score card -->
                    <div class="result-card score-card">
                        <div class="result-card-content">
                            <div class="card-icon">
                                <img src="<?php echo e(asset('user_assets/images/illustrations/reward.svg')); ?>" alt="Reward icon" />
                            </div>
                            <div class="card-value"><?php echo e($percentage); ?>%</div>
                        </div>
                        <div class="card-label">النقاط</div>
                    </div>
                </div>

                <!-- Congratulations message -->
                <div class="congratulations-section">
                    <?php if($total >= $level_mark): ?>
                        <p class="congrats-text-ar">
                            تهانينا، نتيجتك في هذا التقييم هي <?php echo e($percentage); ?>% انت مؤهل للحصول على الشهادة
                        </p>



                    <?php else: ?>
                        <p class="congrats-text-ar">
                            درجتك في هذا التقييم هي <?php echo e($percentage); ?>%. تحتاج إلى <?php echo e($level_mark); ?>% للنجاح. يرجى المحاولة مرة أخرى.
                        </p>



                    <?php endif; ?>
                </div>

                <!-- Action buttons -->
                <div class="result-actions">
                    <?php if($total >= $level_mark): ?>
                        <button class="result-btn certificate-btn" data-url="<?php echo e($certificate_url); ?>">الحصول على الشهادة</button>
                    <?php endif; ?>
                    <button class="result-btn next-lesson-btn" data-url="<?php echo e($next_lesson_url); ?>">الدرس التالي</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
    </script>
    <script src="<?php echo e(asset('user_assets/js/pages/test-result.js')); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/pages/test_result.blade.php ENDPATH**/ ?>