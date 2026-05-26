

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('s_website/css/animate_cards.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home pt-5 user-home ">
        <div class="container">
                <h2>
                    <span style="font-weight: bold; color: red">الصف : <?php echo e($grade_steps->grade_name); ?></span>
                </h2>
                <div class="row justify-content-center">
                    <?php if(isset($grade_steps) && $grade_steps->reading): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="<?php echo e(asset('steps/reading.svg')); ?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة القراءة</div>
                                    <a href="<?php echo e(route('teacher.lessons', [$grade_steps->id, 'reading'])); ?>"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($grade_steps) && $grade_steps->writing): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="<?php echo e(asset('steps/writing.svg')); ?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الكتابة</div>
                                    <a href="<?php echo e(route('teacher.lessons', [$grade_steps->id, 'writing'])); ?>"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($grade_steps) && $grade_steps->listening): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="<?php echo e(asset('steps/listening.svg')); ?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة الاستماع</div>
                                    <a href="<?php echo e(route('teacher.lessons', [$grade_steps->id, 'listening'])); ?>"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($grade_steps) && $grade_steps->speaking): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <img src="<?php echo e(asset('steps/speaking.svg')); ?>" alt="">
                                </div>
                                <div class="content">
                                    <div class="title"> مهارة التحدث</div>
                                    <a href="<?php echo e(route('teacher.lessons', [$grade_steps->id, 'speaking'])); ?>"
                                       class="btn   btn-theme w-75 mb-4">
                                        دخول
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                        <?php if(isset($grade_steps) && $grade_steps->grammar == 1): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/grammar.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  القواعد النحوية</div>
                                        <a href="<?php echo e(route('teacher.lessons_levels', [$grade_steps->id, 'grammar'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade_steps) && $grade_steps->dictation == 1): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/dictation.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  الإملاء</div>
                                        <a href="<?php echo e(route('teacher.lessons_levels', [$grade_steps->id, 'dictation'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade_steps) && $grade_steps->rhetoric == 1): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/rhetoric.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  البلاغة</div>
                                        <a href="<?php echo e(route('teacher.lessons_levels', [$grade_steps->id, 'rhetoric'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/levels.blade.php ENDPATH**/ ?>