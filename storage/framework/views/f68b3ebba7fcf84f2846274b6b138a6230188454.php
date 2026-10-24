
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> الشهادات </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('certificates')); ?>"> الشهادات </a>
                            <span class="breadcrumb-item active" aria-current="page"> إجابات الأسئلة </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php if(!is_null($student_test->feedback_message) || !is_null($student_test->feedback_record)): ?>
                        <div class="table-card">
                            <div class="table-header">
                                <h3 class="title text-danger">تغذية راجعة</h3>
                            </div>
                            <div class="table-responsive text-center">
                                <?php if($student_test && !is_null($student_test->feedback_message)): ?>
                                    <h4 class="text-center p-3">
                                        <?php echo e($student_test->feedback_message); ?>

                                    </h4>
                                <?php endif; ?>
                                <?php if($student_test && !is_null($student_test->feedback_record)): ?>
                                    <audio src="<?php echo e(asset($student_test->feedback_record)); ?>" controls></audio>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title">إجابات الأسئلة</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <?php if($student_test->lesson->lesson_type == 'writing' || $student_test->lesson->lesson_type == 'speaking'): ?>
                                    <tr>
                                        <td style="font-weight: bold">#</td>
                                        <td style="font-weight: bold">السؤال</td>
                                        <td style="font-weight: bold">إجابة الطالب</td>
                                    </tr>
                                    <?php if($student_test->lesson->lesson_type == 'writing'): ?>
                                        <?php $__currentLoopData = $student_test->writingResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $writingResult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="text-center">
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($writingResult->question->content); ?></td>
                                                <td>
                                                    <?php echo e($writingResult->result); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <?php $__currentLoopData = $student_test->speakingResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $speakingResult): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="text-center">
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($speakingResult->question->content); ?></td>
                                                <td>
                                                    <audio src="<?php echo e(asset($speakingResult->attachment)); ?>" controls></audio>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td style="font-weight: bold">#</td>
                                        <td style="font-weight: bold">السؤال</td>
                                        <td style="font-weight: bold">تصنيف السؤال</td>
                                        <td style="font-weight: bold">إجابة الطالب</td>

                                        <td style="font-weight: bold">الإجابة الصحيحة</td>
                                    </tr>
                                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="text-center">
                                            <?php
                                                $data = $question->studentAnswer($student_test->id, $student_test->user_id);
                                            ?>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($question->content); ?></td>
                                            <td><?php echo e($question->type_eng_name); ?></td>
                                            <td class="<?php echo e(isset($data['class']) ? $data['class']:''); ?>">
                                                <?php echo isset($data['student_answer']) ? $data['student_answer']:''; ?>

                                            </td>
                                            <td>
                                                <?php echo isset($data['question_answer']) ? $data['question_answer']:''; ?>

                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/school/lessons_tests/student_test_results.blade.php ENDPATH**/ ?>