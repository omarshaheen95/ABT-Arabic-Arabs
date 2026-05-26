

<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" type="text/css" href="https://www.arabic-keyboard.org/keyboard/keyboard.css">


    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }

        .progress {
            position: relative;
            width: 100%;
            height: 30px;
            border: 1px solid #7F98B2;
            border-radius: 3px;
            border-radius: 36px !important;
            display: none;
        }

        .bar {
            background-color: #17C41A;
            width: 0%;
            height: 30px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 4px;
            left: 48%;
            color: #000;
        }

        .text-success {
            color: #0F0 !important;
        }

        .box-style {
            color: #FF0000;
            font-size: 15px;
            font-weight: bold;
            background-color: #EFEFEF;
            padding: 10px;
            border-left: 5px solid #F00;;
            border-right: 5px solid #F00;
            border-top: 5px solid #000;
            border-bottom: 5px solid #000;
        }

        #recordingslist {
            list-style: none;
        }

        #keyboardInputLayout {
            direction: ltr !important;
        }

        #keyboardInputMaster tbody tr td div#keyboardInputLayout table tbody tr td {
            font: normal 30px 'Lucida Console', monospace;
        }

        .keyboardInputInitiator {
            width: 50px
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('lessons', [$lesson->grade_id, $lesson->lesson_type])); ?>"
           <?php if(isset($lesson->grade) && !is_null($lesson->grade->text_color)): ?> style="color: <?php echo e($level->text_color); ?> !important; font-weight: bold" <?php endif; ?>>الدروس</a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : <?php echo e($lesson->grade_name); ?> </h3>
                        <nav class="breadcrumb fw-bold">
                            <?php echo e(t('Lesson Learn Preview')); ?>

                        </nav>
                    </div>
                </div>
            </div>
            <div class="exercise-box" id="exercise-1">







                <?php if($lesson->lesson_type == 'reading'): ?>
                    <div class="exercise-box-header text-center">
                        <span class="title">اقرأ النَّص التالي للفَهم والاستيعابٍ</span>
                    </div>
                <?php elseif($lesson->lesson_type == 'listening'): ?>
                    <div class="exercise-box-header text-center">
                        <span class="title">استمع للنص التاليٍ</span>
                    </div>
                <?php endif; ?>

                <div class="exercise-box-body">
                    <div class="table-responsove">
                        <table class="table">
                            <?php if($lesson->getFirstMediaUrl('audioLessons')): ?>
                                <tr>
                                    <td>
                                        <div class="audio-player">
                                            <audio>
                                                <source
                                                    src="<?php echo e(asset($lesson->getFirstMediaUrl('audioLessons'))); ?>"
                                                    type="audio/mpeg"/>
                                            </audio>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="exercise-question">
                        <div class="row justify-content-center my-5">
                            <?php $__currentLoopData = $lesson->getMedia('videoLessons'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-8">
                                    <div id="vid_player_<?php echo e($video->id); ?>" class="mb-4"></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="exercise-question">
                        <?php echo $lesson->content; ?>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <audio class="play-and-listen-audio" src=""></audio>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('s_website/js/playerjs.js')); ?>"></script>

    <script>
        <?php $__currentLoopData = $lesson->getMedia('videoLessons'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        var player_<?php echo e($video->id); ?> = new Playerjs({
            id: "vid_player_<?php echo e($video->id); ?>",
            file: '<?php echo e(asset($video->getUrl())); ?>',
        });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </script>
    <script>


        $(document).ready(function () {
            $(document).on("click", ".play-and-listen", function (e) {
                e.preventDefault();
                var audio_ID = $(this).data("recorde-id"),
                    audio_url = $(this).data("recorde-url");
                if ($(this).hasClass("active")) {
                    $(".play-and-listen-audio").attr("src", audio_url);
                    $(".play-and-listen-audio").trigger("pause");
                    $(".play-and-listen").removeClass("active");
                } else {
                    $(".play-and-listen-audio").attr("src", audio_url);
                    $(".play-and-listen-audio").trigger("play");

                    $(".play-and-listen").removeClass("active");
                    $(this).addClass("active");
                }
            });
        });


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('general.user.std_container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/lesson/preview/learn.blade.php ENDPATH**/ ?>