<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('stories.list', $story->grade)); ?>" >القصص </a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h1 class="title"> <?php echo e($story->name); ?> </h1>
                        <h1 class="title"> <p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p>  </h1>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('stories.list', $story->grade)); ?>"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page"> القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card py-4">
                    <div class="card-header bg-white text-center">
                        <h4 style="font-weight: bold">نتيجة الاختبار</h4>
                    </div>
                    <div class="card-body pb-0">

                        <div class="row justify-content-center">
                            <div class="col-md-12 justify-content-center text-center">
                                <?php if($student_test): ?>
                                    <?php if($student_test->status == "Pass"): ?>
                                        <img src="<?php echo e(asset('s_website/img/good_icon.png')); ?>" class="my-3" width="15%">
                                        <br>

                                        <p class="text-center my-3 mb-4" style="font-weight: bold">
                                            تهانينا ، نتيجتك في هذا التقييم هي  <?php echo e($student_test->total_per); ?>  وأنت مؤهل للحصول على الشهادة .
                                        </p>
                                        <div class="text-center">
                                            <a href="<?php echo e(route('story.certificate', $student_test->id)); ?>" target="_blank" class="btn btn-theme mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">
                                            الحصول على الشهادة
                                        </span>
                                            </a>
                                            <a href="<?php echo e(route('home')); ?>" class="btn btn-theme mx-4 my-4" >
                                        <span class="txt" style="font-size: 18px">الرئيسة</span>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('s_website/img/sad.png')); ?>" class="my-3" width="10%">
                                        <br>
                                        <p class="text-center my-3 mb-4" style="font-weight: bold">
                                            نتيجتك في هذا التقييم هي <?php echo e($student_test->total_per); ?> ولست مؤهلاً للحصول على الشهادة  - يرجى إعادة تعلم هذا الدرس مرة أخرى للوصول إلى
                                            <span class="text-danger">50٪</span> أو أعلى.                                    </p>
                                        <div class="text-center">

                                            <a href="<?php echo e(route('stories.show', [$student_test->story_id, 'test'])); ?>" class="btn btn-theme my-4" >
                                        <span class="txt" style="font-size: 18px">اعادة الاختبار</span>
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
            </div>
        </div>
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

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/story/story_test_result.blade.php ENDPATH**/ ?>