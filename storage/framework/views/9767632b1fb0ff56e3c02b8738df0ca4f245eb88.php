<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/lessons-grid.css')); ?>"/>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lessons-grid-container">
        <header class="lessons-page-header">

        </header>
        <?php if(isset($grades) && count($grades)>0): ?>
            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Get the enriched grade data from $allGrades with skills_progress
                    $enrichedGrade = $allGrades->firstWhere('id', $grade->id);
                ?>
                <header class="lessons-page-header">
                                <h2 class="lessons-page-title">الصف <?php echo e($grade->grade_name); ?></h2>
                </header>

        <div class="lessons-grid" id="lessons-grid">

                <?php $__currentLoopData = $grade->grade_skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Get skill-specific progress data from enriched grade
                    $skillProgress = $enrichedGrade->skills_progress[$data['skill']] ?? ['progress' => 0, 'isCompleted' => false];
                    $isCompleted = $skillProgress['isCompleted'];
                    $gradeProgress = $skillProgress['progress'];
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
                                <img class="lesson-icon-grid" src="<?php echo e(asset('steps/'.$data['skill'].'.svg')); ?>"
                                     alt="<?php echo e($data['skill']); ?>"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid"><?php echo e($data['title']); ?></h5>
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
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="<?php echo e($gradeProgress); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: <?php echo e($gradeProgress); ?>%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: <?php echo e($gradeProgress); ?>%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage"><?php echo e($gradeProgress); ?>%</span>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <?php
                                    if (in_array($data['skill'],['grammar','dictation','rhetoric'])){
                                        $url = route('lessons.sub-levels', ['id'=>$grade->id, 'type'=>$data['skill']]);
                                    }else{
                                        $url = route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$data['skill']]);
                                    }
                                ?>

                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="<?php echo e($url); ?>">
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if(isset($alternate_grades) && count($alternate_grades)>0): ?>
            <?php $__currentLoopData = $alternate_grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Get the enriched grade data from $allGrades with skills_progress
                    $enrichedGrade = $allGrades->firstWhere('id', $grade->id);
                ?>
                <header class="lessons-page-header">
                                <h2 class="lessons-page-title">الصف <?php echo e($grade->grade_name); ?></h2>
                </header>
        <div class="lessons-grid" id="lessons-grid">

                <?php $__currentLoopData = $grade->grade_skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // Get skill-specific progress data from enriched grade
                    $skillProgress = $enrichedGrade->skills_progress[$data['skill']] ?? ['progress' => 0, 'isCompleted' => false];
                    $isCompleted = $skillProgress['isCompleted'];
                    $gradeProgress = $skillProgress['progress'];
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
                                <img class="lesson-icon-grid" src="<?php echo e(asset('steps/'.$data['skill'].'.svg')); ?>"
                                     alt="<?php echo e($data['skill']); ?>"/>
                            </div>
                            <div class="lesson-content-grid">
                                <h5 class="lesson-title-grid"><?php echo e($data['title']); ?></h5>
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
                                        <div class="lesson-level-progress-bar lesson-level-progress-bar--current" role="progressbar" aria-valuenow="<?php echo e($gradeProgress); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress: <?php echo e($gradeProgress); ?>%">
                                            <div class="lesson-level-progress-bar__fill lesson-level-progress-bar__fill--current" style="width: <?php echo e($gradeProgress); ?>%;"></div>
                                        </div>
                                        <span class="lesson-level-progress-bar__percentage"><?php echo e($gradeProgress); ?>%</span>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div class="lesson-actions">
                                <?php
                                    if (in_array($data['skill'],['grammar','dictation','rhetoric'])){
                                        $url = route('lessons.sub-levels', ['id'=>$grade->id, 'type'=>$data['skill']]);
                                    }else{
                                        $url = route('lesson.lessons-by-level', ['id'=>$grade->id, 'type'=>$data['skill']]);
                                    }
                                ?>
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="test"
                                        data-url="<?php echo e($url); ?>">
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/lessons-grid.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/levels.blade.php ENDPATH**/ ?>