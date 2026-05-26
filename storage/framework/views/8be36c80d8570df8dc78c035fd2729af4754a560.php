<!-- Grid View (Default) -->
<div class="homework-grid-wrapper show" id="gridView">
    <div class="homework-grid-container">

        <?php $__currentLoopData = $student_assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student_assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!-- Assignment -->
            <div class="assignment-card" data-assignment-id="<?php echo e($student_assignment->id); ?>">
                <div class="card-header">
                    <div class="card-header-text">
                        <h3 class="card-title"><?php echo e($student_assignment->story->name); ?></h3>
                        <p class="card-level">الصف <?php echo e($student_assignment->story->grade); ?></p>
                    </div>
                    <?php if((($student_assignment->test_assignment && $student_assignment->done_test_assignment) &&$student_assignment->completed) ||  $student_assignment->completed): ?>
                        <span class="status-badge completed">مكتمل</span>
                    <?php else: ?>
                        <div class="assignment-timer" data-deadline="<?php echo e($student_assignment->deadline ? $student_assignment->deadline->toIso8601String() : ''); ?>">
                            <span class="timer-text">--:--:--</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-divider"></div>
                <div class="card-actions">
                    <button class="btn-outline glass-button-component" onclick="goToTest('<?php echo e(route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'watch'])); ?>')">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                        </svg>
                        <?php echo e(t('Read the story')); ?>

                    </button>
                    <?php if($student_assignment->test_assignment): ?>
                        <button class="btn-outline glass-button-component <?php echo e($student_assignment->done_test_assignment ? 'completed-btn' : ''); ?>"
                                onclick="goToTest('<?php echo e(route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'test'])); ?>')"
                                <?php echo e($student_assignment->done_test_assignment ? 'disabled' : ''); ?>>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                            </svg>
                            <?php echo e($student_assignment->done_test_assignment ? 'الاختبار مكتمل' : 'اذهب الى الاختبار'); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
    <!-- Pagination for Grid View -->
    <div style="grid-column: 1 / -1;">
        <?php echo $__env->make('user.general.components.pagination', ['paginator' => $student_assignments], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<!-- Table View (Hidden by default) -->
<div class="homework-table-container" id="tableView" style="display: none;">
    <div class="homework-table-wraper">
        <table class="homework-table">
            <thead>
            <tr>
                <th>اسم القصة</th>
                <th>الصف</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody id="homeworkTableBody">
            <?php $__currentLoopData = $student_assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student_assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <!-- Assignment -->
                <tr data-assignment-id="tr-<?php echo e($student_assignment->id); ?>">
                    <td class="lesson-name"><?php echo e($student_assignment->story->name); ?></td>
                    <td><?php echo e($student_assignment->story->grade); ?></td>
                    <td>
                        <?php if((($student_assignment->test_assignment && $student_assignment->done_test_assignment) && $student_assignment->completed) ||  $student_assignment->completed): ?>
                            <span class="status-badge completed">مكتمل</span>
                        <?php else: ?>
                            <div class="assignment-timer" data-deadline="<?php echo e($student_assignment->deadline ? $student_assignment->deadline->toIso8601String() : ''); ?>">
                                <span class="timer-text">--:--:--</span>
                            </div>
                        <?php endif; ?>

                    </td>
                    <td>
                        <div class="action-menu-wrapper">
                            <button class="action-btn" aria-label="More actions" onclick="toggleActionMenu(event, 1)">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="5" r="2" fill="currentColor"/>
                                    <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                    <circle cx="12" cy="19" r="2" fill="currentColor"/>
                                </svg>
                            </button>
                            <div class="action-menu" id="actionMenu-1">
                                <button class="action-menu-item" onclick="goToTasks('<?php echo e(route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'watch'])); ?>')">
                                    <?php echo e(t('Read the story')); ?>

                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z" fill="currentColor"/>
                                    </svg>
                                </button>

                                <?php if($student_assignment->test_assignment): ?>
                                    <button class="action-menu-item <?php echo e($student_assignment->done_test_assignment ? 'completed-btn' : ''); ?>"
                                            onclick="goToTest('<?php echo e(route('story.story-index',['id'=>$student_assignment->story_id,'key'=>'test'])); ?>')"
                                            <?php echo e($student_assignment->done_test_assignment ? 'disabled' : ''); ?>>
                                        <?php echo e($student_assignment->done_test_assignment ? 'الاختبار مكتمل' : 'اذهب الى الاختبار'); ?>

                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5C7 5 2.73 8.11 1 12.5 2.73 16.89 7 20 12 20s9.27-3.11 11-7.5C21.27 8.11 17 5 12 5zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination for Grid View -->
    <div style="grid-column: 1 / -1;">
        <?php echo $__env->make('user.general.components.pagination', ['paginator' => $student_assignments], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/assignments/stories_assignments.blade.php ENDPATH**/ ?>