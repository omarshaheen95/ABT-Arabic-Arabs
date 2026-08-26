<?php $__env->startPush('style'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/stories-levels-grid.css')); ?>" />
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'library'); ?>

<?php $__env->startSection('content'); ?>
    <div class="stories-levels-container">
        <header class="stories-levels-header">
            <h1 class="stories-levels-title">مكتبة القصص</h1>
            <p class="stories-levels-subtitle">تصفح جميع مستويات القصص المتاحة</p>
        </header>

        <div class="stories-levels-grid" id="stories-levels-grid">
               <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $levelGrade = $level->grade;
                    $levelProgress = $level->progress;
                    $levelStarted = $level->progress > 0;
                    $isCompleted = isset($completed_levels) && in_array($level->id, $completed_levels);
                ?>
                <!-- Story Level Card -->
            <?php if($isCompleted): ?>
                <article class="story-level-card story-level-card--completed" data-url="<?php echo e(route('story.stories-by-level', $levelGrade)); ?>" style="animation-delay: 0s;">
                    <div class="story-level-icon-container">
                        <img class="story-level-icon" src="<?php echo e(asset("web_assets/img/levels/new/completed_$levelGrade.svg")); ?>" alt="<?php if(app()->getLocale()=='ar'): ?>
                                        المستوى <?php echo e($levelGrade == 15 ? 'التأسيسي':$levelGrade); ?>

                                    <?php else: ?>
                                        Level <?php echo e($levelGrade == 15 ? 'Foundation':$levelGrade); ?>

                                    <?php endif; ?>" />
                    </div>
                    <div class="story-level-content">
                        <h3 class="story-level-title"><?php if(app()->getLocale()=='ar'): ?>
                                المستوى <?php echo e($levelGrade == 15 ? 'التأسيسي':$levelGrade); ?>

                            <?php else: ?>
                                Level <?php echo e($levelGrade == 15 ? 'Foundation':$levelGrade); ?>

                            <?php endif; ?></h3>
                        <p class="story-level-description">

                        </p>
                        <div class="story-level-progress-container">
                            <div class="story-level-progress-bar story-level-progress-bar--completed" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" aria-label="Story progress: 100%">
                                <div class="story-level-progress-bar__fill story-level-progress-bar__fill--completed" style="width: 100%;"></div>
                            </div>
                            <div class="story-level-check-icon">
                                <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5L4.5 8.5L11 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </article>
            <?php else: ?>
                        <article class="story-level-card story-level-card--current" data-url="<?php echo e(route('story.stories-by-level', $levelGrade)); ?>" style="animation-delay: 0.1s;">
                            <div class="story-level-icon-container">
                                <img class="story-level-icon" src="<?php echo e($levelStarted ? asset("web_assets/img/levels/new/started_$levelGrade.svg"):asset("web_assets/img/levels/new/not_started_$levelGrade.svg")); ?>" alt="<?php if(app()->getLocale()=='ar'): ?>
                                        المستوى <?php echo e($levelGrade == 15 ? 'التأسيسي':$levelGrade); ?>

                                    <?php else: ?>
                                        Level <?php echo e($levelGrade == 15 ? 'Foundation':$levelGrade); ?>

                                    <?php endif; ?>" />
                            </div>
                            <div class="story-level-content">
                                <h3 class="story-level-title"><?php if(app()->getLocale()=='ar'): ?>
                                        المستوى <?php echo e($levelGrade == 15 ? 'التأسيسي':$levelGrade); ?>

                                    <?php else: ?>
                                        Level <?php echo e($levelGrade == 15 ? 'Foundation':$levelGrade); ?>

                                    <?php endif; ?></h3>
                                <p class="story-level-description"></p>
                                <div class="story-level-progress-container">
                                    <div class="story-level-progress-bar story-level-progress-bar--current" role="progressbar" aria-valuenow="<?php echo e($levelProgress); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Story progress: <?php echo e($levelProgress); ?>% completed">
                                        <div class="story-level-progress-bar__fill story-level-progress-bar__fill--current" style="width: <?php echo e($levelProgress); ?>%;"></div>
                                    </div>
                                    <span class="story-level-progress-bar__percentage"><?php echo e($levelProgress); ?>%</span>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>















               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/stories-levels-grid.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/stories/levels.blade.php ENDPATH**/ ?>