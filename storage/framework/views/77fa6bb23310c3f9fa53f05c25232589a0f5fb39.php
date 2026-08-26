<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/training-quiz.css')); ?>?v=1" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'lesson-training'); ?>

<?php $__env->startSection('content'); ?>
    <div class="training-quiz-container">
        <div id="trainingQuizContent">
            <h1 class="training-quiz-title"><?php echo e($lesson->name); ?></h1>
            <div class="training-quiz-content-wrapper">


                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php switch($question->type):
                        case (1): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.true_false', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (2): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.multiple_choice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (3): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.matching', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                        <?php case (4): ?>
                            <?php echo $__env->make('user.lessons.pages.questions.sorting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php break; ?>
                    <?php endswitch; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Navigation -->
                <div class="training-quiz-navigation">
                    <button class="training-quiz-nav-btn" id="prevBtn" disabled>
                        السابق
                    </button>
                    <button class="training-quiz-nav-btn primary" id="nextBtn">
                        التالي
                    </button>
                </div>

                <!-- Question Numbers -->
                <div class="question-numbers">

                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="question-number-btn <?php if($loop->index==0): ?> active <?php endif; ?>" data-question-index="<?php echo e($loop->index); ?>"><?php echo e($loop->iteration); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>
        </div>
    </div>

    <?php echo $__env->make('user.general.components.loading_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Training Completion Dialog -->
    <div class="completion-dialog-overlay" id="completionDialog" style="display: none;">
        <div class="completion-dialog">
            <div class="completion-content">
                <div class="completion-icon">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="40" fill="#39D670" fill-opacity="0.1"/>
                        <path d="M55 30L35 50L25 40" stroke="#39D670" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="completion-title">أحسنت! لقد أكملت التدريب</h3>
                <div class="completion-buttons">
                    <button class="completion-btn primary" id="goToTestBtn">
                        <span>انتقل للاختبار</span>
                    </button>
                    <button class="completion-btn secondary" id="goToLessonsBtn">
                        <span>العودة للدروس</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        // Quiz data from backend
        var QUIZ_DATA = <?php echo json_encode($quizData, 15, 512) ?>;
        var LESSON_TRACKING_URL = '<?php echo e(route('lesson.tracking', [$lesson->id, 'practise'])); ?>';

        // Navigation URLs
        var GO_TO_TEST_URL = '<?php echo e(route('lesson.lesson-index', [$lesson->id, 'test'])); ?>';
        var GO_TO_LESSONS_URL = '<?php echo e(route('lesson.lessons-by-level', ['id'=>$lesson->grade_id,'type'=>$lesson->lesson_type])); ?>';
    </script>

    <script src="<?php echo e(asset('user_assets/js/pages/training-quiz.js')); ?>?v=2"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/pages/training.blade.php ENDPATH**/ ?>