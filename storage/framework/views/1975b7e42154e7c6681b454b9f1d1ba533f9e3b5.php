<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> <?php echo e($title); ?> </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> <?php echo e($title); ?> </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td style="font-weight: bold">الدرس</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">تعيين اختبار</td>
                                    <td style="font-weight: bold">موعد التسليم</td>
                                </tr>
                                <?php $__currentLoopData = $student_assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student_assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('lesson', [$student_assignment->lesson_id, 'learn'])); ?>"><?php echo e($student_assignment->lesson->name); ?></a>
                                        </td>
                                        <td>الصف <?php echo e($student_assignment->lesson->grade_name); ?></td>

                                        <td><?php if($student_assignment->done_test_assignment): ?>
                                                مكتمل
                                            <?php elseif($student_assignment->test_assignment != 0): ?>
                                                <a href="<?php echo e(route('lesson', [$student_assignment->lesson_id, 'test'])); ?>">الذهاب للاختبار</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($student_assignment->deadline); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>

                        <div class="table-footer">
                            <?php echo $student_assignments->links(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/assignments.blade.php ENDPATH**/ ?>