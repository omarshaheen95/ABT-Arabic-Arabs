<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('s_website/css/animate_cards.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home pt-5 user-home ">
        <div class="container">
            <?php if(isset($grades) && count($grades)>0): ?>
               <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h2>
                        <span style="font-weight: bold; color: red">الصف <?php echo e($grade->grade_name); ?>:</span>
                    </h2>
                    <div class="row justify-content-center">
                        <?php if(isset($grade) && $grade->reading): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/reading.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة القراءة</div>
                                        <a href="<?php echo e(route('lessons', [$grade->id, 'reading'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->writing): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/writing.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة الكتابة</div>
                                        <a href="<?php echo e(route('lessons', [$grade->id, 'writing'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->listening): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/listening.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة الاستماع</div>
                                        <a href="<?php echo e(route('lessons', [$grade->id, 'listening'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->speaking): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/speaking.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title"> مهارة التحدث</div>
                                        <a href="<?php echo e(route('lessons', [$grade->id, 'speaking'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->grammar): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/grammar.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  القواعد النحوية</div>
                                        <a href="<?php echo e(route('lessons_levels', [$grade->id, 'grammar'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->dictation): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/dictation.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  الإملاء</div>
                                        <a href="<?php echo e(route('lessons_levels', [$grade->id, 'dictation'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($grade) && $grade->rhetoric): ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="lesson-box">
                                    <div class="pic">
                                        <img src="<?php echo e(asset('steps/rhetoric.svg')); ?>" alt="">
                                    </div>
                                    <div class="content">
                                        <div class="title">  البلاغة</div>
                                        <a href="<?php echo e(route('lessons_levels', [$grade->id, 'rhetoric'])); ?>"
                                           class="btn   btn-theme w-75 mb-4">
                                            دخول
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(isset($alternate_grades) &&  count($alternate_grades)>0): ?>
                    <?php $__currentLoopData = $alternate_grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alternate_grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <h2>
                            <span style="font-weight: bold; color: red">الصف <?php echo e($alternate_grade->grade_name); ?>:</span>
                        </h2>
                        <div class="row justify-content-center">
                            <?php if(isset($alternate_grade) && $alternate_grade->reading): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/reading.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة القراءة</div>
                                            <a href="<?php echo e(route('lessons', [$alternate_grade->id, 'reading'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->writing): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/writing.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة الكتابة</div>
                                            <a href="<?php echo e(route('lessons', [$alternate_grade->id, 'writing'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->listening): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/listening.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة الاستماع</div>
                                            <a href="<?php echo e(route('lessons', [$alternate_grade->id, 'listening'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->speaking): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/speaking.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> مهارة التحدث</div>
                                            <a href="<?php echo e(route('lessons', [$alternate_grade->id, 'speaking'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->grammar): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/grammar.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> القواعد النحوية</div>
                                            <a href="<?php echo e(route('lessons_levels', [$alternate_grade->id, 'grammar'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->dictation): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/dictation.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> الإملاء</div>
                                            <a href="<?php echo e(route('lessons_levels', [$alternate_grade->id, 'dictation'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($alternate_grade) && $alternate_grade->rhetoric): ?>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="lesson-box">
                                        <div class="pic">
                                            <img src="<?php echo e(asset('steps/rhetoric.svg')); ?>" alt="">
                                        </div>
                                        <div class="content">
                                            <div class="title"> البلاغة</div>
                                            <a href="<?php echo e(route('lessons_levels', [$alternate_grade->id, 'rhetoric'])); ?>"
                                               class="btn   btn-theme w-75 mb-4">
                                                دخول
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/levels.blade.php ENDPATH**/ ?>