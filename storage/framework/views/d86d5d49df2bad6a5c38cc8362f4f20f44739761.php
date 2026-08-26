<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/lessons-grid.css')); ?>"/>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
            <h1 class="lessons-page-title"><?php echo e($title); ?></h1>
        </header>
        <?php if(isset($levels) && count($levels)>0): ?>
            <div class="lessons-grid" id="lessons-grid">

                <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Get progress data for this level
                    $isCompleted = $level['isCompleted'] ?? false;
                    $levelProgress = $level['progress'] ?? 0;
                    $completedLessons = $level['completed_lessons'] ?? 0;
                    $totalLessons = $level['total_lessons'] ?? 0;
                ?>
                    <article class="lesson-card-grid <?php echo e($isCompleted ? 'lesson-card--completed' : ''); ?>" style="animation-delay: 0.1s;">
                            <?php if($isCompleted): ?>
                                <div class="lesson-completed-badge">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 7L5.5 10.5L12 3.5" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span><?php echo e(t('Completed')); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="lesson-icon-container">
                                <img class="lesson-icon-grid" src="<?php echo e(asset("web_assets/img/levels/".$level["level"].".svg")); ?>"
                                     alt="<?php echo e($level["level"]); ?>"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid"> المستوى <?php echo e($level["level"]); ?></h5>

                                <div class="lesson-level-progress-container">
                                    <?php if($isCompleted): ?>
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--completed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: 100%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--completed" style="width: 100%;"></div>
                                        </div>
                                        <div class="lesson-level-check-icon">
                                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 5L4.5 8.5L11 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="<?php echo e($levelProgress); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: <?php echo e($levelProgress); ?>%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: <?php echo e($levelProgress); ?>%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage"><?php echo e($levelProgress); ?>%</span>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="<?php echo e(route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$type,'level'=>$level["level"]])); ?>">
                                    <span>دخول</span>
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 7H2M2 7L6 3M2 7L6 11"
                                              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



        </div>
        <?php endif; ?>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/lessons-grid.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/sub-levels.blade.php ENDPATH**/ ?>