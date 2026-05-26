<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/quiz.css')); ?>?v=1" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'lesson-test'); ?>

<?php $__env->startSection('content'); ?>
    <div class="quiz-container">
        <div id="quizContent">
            <h1 class="quiz-title"><?php echo e($lesson->name); ?></h1>
            <div class="quiz-content-wrapper">

                <!-- Quiz Progress Bar and Timer -->
                <div class="quiz-progress-container">
                    <div class="quiz-progress">
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progressBar" style="width: 20%"></div>
                        </div>
                    </div>
                    <div class="quiz-timer" id="quizTimer">
                        <span class="timer-text">15:00</span>
                    </div>
                </div>

                <form id="testForm" method="POST" action="<?php echo e(route('lesson.save-test', $lesson->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_at" id="startTimeInput" value="<?php echo e(\Carbon\Carbon::now()); ?>">

                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php switch($question->type):
                        case (1): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.true_false', ['type' => 'test'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (2): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.multiple_choice', ['type' => 'test'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (3): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.matching', ['type' => 'test'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (4): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.sorting', ['type' => 'test'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                    <?php endswitch; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Navigation -->
                <div class="quiz-navigation">
                    <button type="button" class="quiz-nav-btn" id="prevBtn" disabled>
                        السابق
                    </button>
                    <button type="button" class="quiz-nav-btn primary" id="nextBtn">
                        التالي
                    </button>
                    <button type="submit" class="quiz-nav-btn submit-btn" id="submitBtn" style="display: none;">
                        <span>إنهاء الاختبار</span>
                    </button>
                </div>

                </form>

            </div>
        </div>
    </div>

    <?php echo $__env->make('user.general.components.loading_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Submit Confirmation Dialog -->
    <div class="submit-confirmation-dialog-overlay" id="submitConfirmDialog" style="display: none;">
        <div class="submit-confirmation-dialog">
            <div class="submit-dialog-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#FF9800"/>
                </svg>
            </div>
            <h3 class="submit-dialog-title">تأكيد إنهاء الاختبار</h3>
            <p class="submit-dialog-message">
                هل أنت متأكد من إنهاء الاختبار؟<br>. لن تتمكن من تغيير إجاباتك بعد التقديم
            </p>
            <div class="submit-dialog-actions">
                <button class="submit-dialog-btn cancel-btn" id="cancelSubmitBtn">
                    <span>إلغاء</span>
                </button>
                <button class="submit-dialog-btn confirm-btn" id="confirmSubmitBtn">
                    <span>تأكيد التقديم</span>
                </button>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        // Quiz data from backend
        var QUIZ_DATA = <?php echo json_encode($quizData, 15, 512) ?>;
    </script>
    <script src="<?php echo e(asset('user_assets/js/pages/test.js')); ?>"></script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/pages/test.blade.php ENDPATH**/ ?>