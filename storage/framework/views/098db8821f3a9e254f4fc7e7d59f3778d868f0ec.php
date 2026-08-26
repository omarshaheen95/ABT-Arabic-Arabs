<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('user_assets/css/pages/lessons-grid.css')); ?>"/>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-name', 'dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lessons-grid-container">
        <header class="lessons-page-header">
            <h1 class="lessons-page-title"><?php echo e($grade->name); ?></h1>
            
        </header>

        <div class="lessons-grid" id="lessons-grid">
            <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isCompleted = isset($completedLessonIds) && in_array($lesson->id, $completedLessonIds);
                ?>
                <article class="lesson-card-grid <?php echo e($isCompleted ? 'lesson-card--completed' : ''); ?>"
                         style="animation-delay: 0.1s;">
                    <?php if($isCompleted): ?>
                        <div class="lesson-completed-badge">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 7L5.5 10.5L12 3.5" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>مكتمل ومصحح</span>
                        </div>
                    <?php endif; ?>
                    <div class="lesson-icon-container">
                        <?php if($lesson->lesson_type == 'writing' || $lesson->lesson_type == 'speaking'): ?>
                            <img class="lesson-icon-grid" src="<?php echo e(asset('web_assets/img/'.$lesson->lesson_type . '.jpg')); ?>" alt="">
                        <?php elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric'])): ?>
                            <img class="lesson-icon-grid" src="<?php echo e(asset('steps/'.$lesson->lesson_type.'.svg')); ?>" alt="">
                        <?php else: ?>
                            <img class="lesson-icon-grid" src="<?php echo e(isset($lesson) ? $lesson->getFirstMediaUrl('imageLessons'):''); ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="lesson-content-grid">
                        <h5 class="lesson-title-grid"><?php echo e($lesson->name); ?></h5>
                        <h5 class="lesson-title-grid"><?php echo e($lesson->section_type_name ? "الدَّرسُ  $lesson->section_type_name":null); ?></h5>
                    </div>
                    <div class="lesson-actions">
                        <?php if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking'): ?>
                            <div class="lesson-actions-row">
                                <button class="lesson-action-btn lesson-action-btn--success" data-action="learn"
                                        data-url="<?php echo e(route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'learn'])); ?>"
                                        class="btn btn-soft-success">

                                    <?php if($lesson->lesson_type == 'reading'): ?>
                                        اقرأ
                                    <?php elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric'])): ?>
                                        تعلم
                                    <?php else: ?>
                                        استمع
                                    <?php endif; ?>
                                </button>
                                <button class="lesson-action-btn lesson-action-btn--secondary" data-action="training"
                                        data-url="<?php echo e(route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'training'])); ?>">
                                    <span> تدرب</span>
                                </button>
                            </div>
                        <?php endif; ?>

                            <button class="lesson-action-btn lesson-action-btn--accent" data-action="test"
                                    data-url="<?php echo e(route('lesson.lesson-index',['id'=>$lesson->id,'key'=>'test'])); ?>">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 1L8.5 5H12.5L9.5 7.5L10.5 11.5L7 9L3.5 11.5L4.5 7.5L1.5 5H5.5L7 1Z"
                                          stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span>
                                     <?php if($lesson->lesson_type == 'writing'): ?>
                                        اكتب
                                    <?php elseif($lesson->lesson_type == 'speaking'): ?>
                                        تحدث
                                    <?php else: ?>
                                        اختبر نفسك
                                    <?php endif; ?>


                                    <?php if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking'): ?>
                                        <?php if($lesson->student_tested): ?>- مكتمل   <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($lesson->student_tested_task): ?>- مكتمل   <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </button>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/js/pages/lessons-grid.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/lessons_by_level.blade.php ENDPATH**/ ?>