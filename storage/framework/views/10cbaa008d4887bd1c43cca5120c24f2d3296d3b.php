<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('lessons', [$lesson->grade_id, $lesson->lesson_type])); ?>" >الدروس</a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="inner-page">
        <section>
            <div class="card mt-3 mb-4 border-0">
                <div class="card-header bg-white text-center">
                    <h4 style="font-weight: bold">نتيجة الاختبار  - Test result</h4>
                </div>
                <div class="card-body pb-0">

                    <div class="row justify-content-center">
                        <div class="col-md-12 justify-content-center text-center">
                        <?php if($student_test): ?>
                            <?php if($student_test->total >= $student_test->lesson->success_mark): ?>
                                <img src="<?php echo e(asset('s_website/img/good_icon.png')); ?>" width="15%">
                                <p class="text-center my-3 mb-4" style="font-weight: bold">
                                    تهانينا ، نتيجتك في هذا التقييم هي  <?php echo e($student_test->total); ?>%  وأنت مؤهل للحصول على الشهادة .
                                </p>
                                <div class="text-center">
                                    <a href="<?php echo e(route('certificate', $student_test->id)); ?>" target="_blank" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            الحصول على الشهادة
                                        </span>
                                    </a>
                                    <a href="<?php echo e(route('home')); ?>" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            الرئيسة
                                        </span>
                                    </a>
                                </div>
                            <?php else: ?>
                                    <img src="<?php echo e(asset('s_website/img/sad.png')); ?>" width="10%">
                                    <p class="text-center my-3 mb-4" style="font-weight: bold">
    نتيجتك في هذا التقييم هي <?php echo e($student_test->total); ?>% ولست مؤهلاً للحصول على الشهادة - يرجى إعادة تعلم هذا الدرس مرة أخرى للوصول إلى
                                        <span class="text-danger"><?php echo e($student_test->lesson->success_mark); ?>٪</span> أو أعلى.                                    </p>
                                    <div class="text-center">

                                        <a href="<?php echo e(route('lesson', [$student_test->lesson_id, 'test'])); ?>" class="theme-btn btn-style-one mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            اعادة الاختبار
                                        </span>
                                        </a>
                                    </div>
                            <?php endif; ?>


                            <?php else: ?>
                            <img src="<?php echo e(asset('s_website/img/sad_icon.png')); ?>" width="10%">
                            <p class="text-center my-3 mb-4" style="font-weight: bold">
                                لا يوجد أي بيانات عن الاختبار المحدد
                            </p>
                        <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(function() {
            if (window.history && window.history.pushState) {
                window.history.pushState('', null, './');
                $(window).on('popstate', function() {
                    // alert('Back button was pressed.');
                    document.location.href = '#';
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/lesson/lesson_test_result.blade.php ENDPATH**/ ?>