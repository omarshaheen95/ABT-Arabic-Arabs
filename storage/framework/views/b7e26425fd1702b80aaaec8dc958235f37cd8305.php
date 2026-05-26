<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> الشهادات </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="/home"> الرئيسة </a>
                            <span class="breadcrumb-item active" aria-current="page"> الشهادات </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="title"> نتائج الاختبارات </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td style="font-weight: bold">الدرس</td>
                                    <td style="font-weight: bold">المستوى</td>
                                    <td style="font-weight: bold">الدرجة</td>
                                    <td style="font-weight: bold">استحقت في</td>
                                    <td style="font-weight: bold">الشهادة</td>
                                </tr>
                                <?php $__currentLoopData = $student_tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student_test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($student_test->total >= 50): ?>
                                            <tr>
                                                <td><?php echo e($student_test->lesson->name); ?></td>
                                                <td>الصف <?php echo e($student_test->lesson->grade_name); ?></td>
                                                <td>%<?php echo e($student_test->total); ?></td>
                                                <td><?php echo e($student_test->created_at->format('Y-m-d H:i')); ?></td>
                                                <td>
                                                    <a href="<?php echo e(route('certificate', $student_test->id)); ?>">معاينة</a> -
                                                    <a href="<?php echo e(route('certificate.answers', $student_test->id)); ?>">الإجابات</a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </table>
                        </div>

                        <div class="table-footer">
                            <?php echo $student_tests->links(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/certificates.blade.php ENDPATH**/ ?>