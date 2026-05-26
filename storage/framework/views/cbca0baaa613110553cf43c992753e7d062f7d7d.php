<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> الإجابات </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('certificates')); ?>">الإجابات</a>
                            <span class="breadcrumb-item active" aria-current="page"> إجابات الأسئلة </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title"> إجابات الاختبار </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
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
                                            $data = $question->studentAnswer($student_test->id);
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

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/story/certificate_result.blade.php ENDPATH**/ ?>