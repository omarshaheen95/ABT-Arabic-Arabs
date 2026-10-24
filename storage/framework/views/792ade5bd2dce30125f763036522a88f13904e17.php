

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('s_website/css/bootstrap-rtl.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('s_website/css/lesson_card.css')); ?>" rel="stylesheet">
    <style>

        .lessonCover{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 360px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .lessonCover img{
            width: 100%;
            /* max-width: 100%; */
            /* height: 100%; */
            /* overflow: hidden; */
            /* margin-bottom: 1rem; */
            object-fit: cover;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : <?php echo e(getGradeName($grade)); ?> </h3>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('teacher.levels', $grade)); ?>"> المهارات والدروس </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!$lesson->is_hidden): ?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="lesson-box">
                                <div class="pic">
                                    <?php if($lesson->lesson_type == 'writing' || $lesson->lesson_type == 'speaking'): ?>
                                        <img src="<?php echo e(asset('web_assets/img/'.$lesson->lesson_type . '.jpg')); ?>" alt="">
                                    <?php elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric'])): ?>
                                        <img src="<?php echo e(asset('steps/'.$lesson->lesson_type.'.svg')); ?>" alt="">
                                        <?php else: ?>
                                    <img src="<?php echo e(isset($lesson) ? $lesson->getFirstMediaUrl('imageLessons'):''); ?>" alt="">
                                        <?php endif; ?>
                                </div>
                                <div class="content">
                                    <div class="title text-success"> <?php echo e($lesson->name); ?> </div>
                                    <div class="title text-warning"> <?php echo e($lesson->section_type_name ? "الدَّرسُ  $lesson->section_type_name":null); ?> </div>

                                    <div class="option">
                                        <?php if($lesson->lesson_type != 'writing' && $lesson->lesson_type != 'speaking'): ?>
                                            <a href="<?php echo e(route('teacher.lesson', [$lesson->id, 'learn'])); ?>"
                                               class="btn btn-soft-success">
                                                <?php if($lesson->lesson_type == 'reading'): ?>
                                                    اقرأ
                                                <?php elseif(in_array($lesson->lesson_type, ['grammar','dictation','rhetoric'])): ?>
                                                    تعلم
                                                <?php else: ?>
                                                    استمع
                                                <?php endif; ?>
                                            </a>

                                        <a href="<?php echo e(route('teacher.lesson', [$lesson->id, 'training'])); ?>"
                                           class="btn btn-soft-danger">
                                            تدرب
                                        </a>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('teacher.lesson', [$lesson->id, 'test'])); ?>"
                                           class="btn btn-soft-info">
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


                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/lessons.blade.php ENDPATH**/ ?>