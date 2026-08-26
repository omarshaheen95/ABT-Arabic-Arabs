<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/css/pages/lesson.css')); ?>?v=3"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/css/pages/lesson-responsive.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/lib/green-audio-player/green-audio-player.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'lesson-learn'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle"><?php echo e($lesson->name); ?></h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo e(Redirect::back()); ?>" class="breadcrumb-item"><?php echo e(t('Lessons')); ?></a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active"><?php echo e($lesson->name); ?></span>
                </nav>
            </div>
            <a href="<?php echo e(route('lesson.lesson-index',['id'=>$lesson->id,'key' => 'training'])); ?>" class="practice-btn" >
                اذهب إلى التدريب
            </a>
        </header>

        <!-- Lesson Content -->
        <div class="lesson-content">
            <?php if($lesson->lesson_type == 'reading'): ?>
                <div class="lesson-instruction">
                    <h2>اقرأ النَّص التالي للفَهم والاستيعاب</h2>
                </div>
            <?php elseif($lesson->lesson_type == 'listening'): ?>
                <div class="lesson-instruction">
                    <h2>استمع للنص التاليٍ</h2>
                </div>
            <?php endif; ?>

            <!-- Audio Player Section -->
            <?php if($lesson->getFirstMediaUrl('audioLessons')): ?>
                <div class="lesson-audio-section" dir="ltr">
                    <div class="audio-player" style="width: 100% !important;">
                        <audio>
                            <source src="<?php echo e(asset($lesson->getFirstMediaUrl('audioLessons'))); ?>" type="audio/mpeg"/>
                        </audio>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Video Players Section -->
            <?php if($lesson->getMedia('videoLessons')->count() > 0): ?>
                <div class="lesson-videos-section">
                    <?php $__currentLoopData = $lesson->getMedia('videoLessons'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="lesson-video-player">
                            <div id="vid_player_<?php echo e($video->id); ?>"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Lesson Text Content -->
            <?php if($lesson->content): ?>
                <div class="lesson-text-content">
                    <?php echo $lesson->content; ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/lib/green-audio-player/green-audio-player.min.js')); ?>"></script>
    <script src="<?php echo e(asset('user_assets/lib/player-js.js')); ?>"></script>

    <script>
        // Initialize video players
        <?php $__currentLoopData = $lesson->getMedia('videoLessons'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        var player_<?php echo e($video->id); ?> = new Playerjs({
            id: "vid_player_<?php echo e($video->id); ?>",
            file: '<?php echo e(asset($video->getUrl())); ?>',
        });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        $(document).ready(function () {
            // Initialize audio player
            GreenAudioPlayer.init({
                selector: '.audio-player',
                stopOthersOnPlay: true
            });

            // Track lesson visit after 10 seconds
            setTimeout(function () {
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '<?php echo e(route('lesson.tracking', [$lesson->id, 'learn'])); ?>';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                    },
                    success: function (data) {
                        console.log('Lesson tracking recorded');
                    },
                    error: function (errMsg) {
                        console.error('Error tracking lesson:', errMsg);
                    }
                });
            }, 10000);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/pages/learn.blade.php ENDPATH**/ ?>