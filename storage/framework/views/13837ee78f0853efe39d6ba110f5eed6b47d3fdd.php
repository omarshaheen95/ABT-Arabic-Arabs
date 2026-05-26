<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('lessons', $lesson->level_id)); ?>" <?php if(isset($level) && !is_null($level->text_color)): ?> style="color: <?php echo e($level->text_color); ?> !important; font-weight: bold" <?php endif; ?>><?php echo e(t('Lessons')); ?></a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="inner-page">
        <section>
            <div class="container ">
                <div class="row">
                    <div class="col-12 justify-content-center">
                        <div class="card mb-4 border-0">
                            <div class="card-body pb-0 text-center">
                                <?php if(!$game): ?>
                                    <div class="card-header bg-white text-center font-weight-bold">
                                        <h5 class="w-100 text-center" style="color: #FFF;font-weight: bold;background-color: #F00;padding: 10px;border-radius: 25px;"> لا يوجد لعبة متاحة لهذا الدرس - There is no game available for this lesson</h5>
                                    </div>
                                <?php else: ?>
                                    <iframe src="<?php echo e(asset($game->game)); ?>" style="width: 100%; min-height: 1000px">
                                    </iframe>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script>

        $(document).ready(function(){
            setTimeout(function(){
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var url = '<?php echo e(route('track_lesson', [$lesson->id, 'play'])); ?>';
                $.ajax({
                    url : url,
                    type: 'POST',
                    data : {
                        '_token': csrf,
                    },
                    success: function (data) {
                    },
                    error: function(errMsg) {
                    }
                });

            }, 10000);
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/lesson/play.blade.php ENDPATH**/ ?>