<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/css/pages/lesson.css')); ?>"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'story-watch'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle"> <?php echo e($story->name); ?></h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo e(Redirect::back()); ?>" class="breadcrumb-item">القصص</a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active"><?php echo e($story->name); ?></span>
                </nav>
            </div>
            <a href="<?php echo e(route('story.story-index',['id'=>$story->id,'key' => 'read'])); ?>" class="practice-btn" >
                اذهب الى القراءة
            </a>
        </header>

        <!-- Tabs and Content Wrapper -->
        <div class="lesson-tabs-wrapper">
            <div id="video<?php echo e($story->id); ?>"></div>
            <?php if(!is_null($story->alternative_video)): ?>
                <div id="alternative_video<?php echo e($story->id); ?>" style="margin-top: 40px"></div>
            <?php endif; ?>
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/lib/player-js.js')); ?>"></script>

    <script>


        // تهيئة المشغل بعد تحميل LayoutManager
        function initializePlayer() {
            var player_<?php echo e($story->id); ?> = new Playerjs({
                id: "video<?php echo e($story->id); ?>",
                file: '<?php echo e(asset($story->video)); ?>',
            });
            <?php if(!is_null($story->alternative_video)): ?>
            var player_alternative_video_<?php echo e($story->id); ?> = new Playerjs({
                id: "alternative_video<?php echo e($story->id); ?>",
                file: '<?php echo e(asset($story->alternative_video)); ?>',
            });
            <?php endif; ?>
        }

        $(document).ready(function () {
            // تأجيل تهيئة المشغل حتى ينتهي LayoutManager من الرندر
            setTimeout(function () {
                initializePlayer();
            }, 200);

            // تتبع المشاهدة
            setTimeout(function () {
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '<?php echo e(route('story.tracking', [$story->id, 'watching'])); ?>';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function (errMsg) {
                    }
                });

            }, 10000);
        });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/stories/pages/watch.blade.php ENDPATH**/ ?>