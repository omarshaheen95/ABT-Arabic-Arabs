<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : <?php echo e($lesson->grade_name); ?> </h3>
                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('teacher.lessons', [$lesson->grade_id, $lesson->lesson_type])); ?>">
                                <?php echo e($lesson->type_name); ?> </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row" dir="rtl">
                <div class="col-lg-12">
                    <div class="exam-card box-shado-question">
                        <div class="exam-body question-list">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="<?php echo e(route('teacher.lesson', [$lesson->id,'learn'])); ?>" class=" btn btn-theme px-5">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    التعلم
                                </a>
                                
                                
                                
                                
                                
                                <a href="<?php echo e(route('teacher.lesson', [$lesson->id,'test'])); ?>" class=" btn btn-theme px-5">
                                    الاختبار
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </div>
                            <?php
                                $counter = 1
                            ?>
                            <?php $__currentLoopData = $tf_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tf_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <div id="<?php echo e($counter); ?>"
                                     class="exercise-box <?php if($loop->first): ?> active <?php endif; ?> question-item">
                                    <div class="exercise-box-header text-center">
                                        <span class="number"> <?php echo e($counter); ?> : </span>
                                        <span class="title">ضع علامة (صح) أو (خطأ)</span>
                                    </div>
                                    <div class="exercise-box-body">
                                        <div class="exercise-question">
                                            <div class="exercise-question-data border-0">
                                                <div class="info">
                                                    <?php echo e($tf_question->content); ?>

                                                </div>
                                            </div>
                                            <div class="exercise-question-answer text-center my-4">

                                                <?php if($tf_question->getFirstMediaUrl('t_imageQuestion')): ?>

                                                    <div class="row justify-content-center py-3">
                                                        <div class="col-lg-6 col-md-8">
                                                            <?php if(\Illuminate\Support\Str::contains($tf_question->getFirstMediaUrl('t_imageQuestion'), '.mp3')): ?>
                                                                <div class="recorder-player" id="voice_audio_2">
                                                                    <div class="audio-player">
                                                                        <audio>
                                                                            <source
                                                                                src="<?php echo e(asset($tf_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                                type="audio/mpeg">
                                                                        </audio>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="w-100 text-center">
                                                                    <img src="<?php echo e(asset($tf_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                         width="300px">
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="true-false-question">
                                                    <div class="answer-box">
                                                        <input
                                                            data-ans="<?php echo e($tf_question->trueFalse->result == 1 ? 'true':'false'); ?>"
                                                            type="radio" question-grop-name="q<?php echo e($counter); ?>"
                                                            name="tf[<?php echo e($tf_question->id); ?>]" value="1"
                                                            id="true-<?php echo e($tf_question->id); ?>" class="d-none">
                                                        <label for="true-<?php echo e($tf_question->id); ?>"
                                                               class="option option-true">
                                                            <svg id="Group_68450" data-name="Group 68450"
                                                                 xmlns="http://www.w3.org/2000/svg" width="80"
                                                                 height="80" viewBox="0 0 102 102">
                                                                <g id="Ellipse_360" data-name="Ellipse 360" fill="#fff"
                                                                   stroke="#2ecc71" stroke-width="1">
                                                                    <circle cx="51" cy="51" r="51" stroke="none"/>
                                                                    <circle cx="51" cy="51" r="50.5" fill="none"/>
                                                                </g>
                                                                <circle id="Ellipse_180" data-name="Ellipse 180" cx="41"
                                                                        cy="41" r="41" transform="translate(10 10)"
                                                                        fill="#2ecc71"/>
                                                                <path id="Shape"
                                                                      d="M12.176,23.045,3.093,13.962,0,17.033,12.176,29.209,38.314,3.071,35.243,0Z"
                                                                      transform="translate(32.856 37.409)" fill="#fff"/>
                                                            </svg>
                                                        </label>
                                                    </div>
                                                    <div class="answer-box">
                                                        <input
                                                            data-ans="<?php echo e($tf_question->trueFalse->result == 0 ? 'true':'false'); ?>"
                                                            type="radio" question-grop-name="q<?php echo e($counter); ?>"
                                                            name="tf[<?php echo e($tf_question->id); ?>]" value="0"
                                                            id="false-<?php echo e($tf_question->id); ?>" class="d-none">
                                                        <label for="false-<?php echo e($tf_question->id); ?>"
                                                               class="option option-false">
                                                            <svg id="Group_68451" data-name="Group 68451"
                                                                 xmlns="http://www.w3.org/2000/svg" width="80"
                                                                 height="80" viewBox="0 0 102 102">
                                                                <g id="Ellipse_361" data-name="Ellipse 361" fill="#fff"
                                                                   stroke="#dc3545" stroke-width="1">
                                                                    <circle cx="51" cy="51" r="51" stroke="none"/>
                                                                    <circle cx="51" cy="51" r="50.5" fill="none"/>
                                                                </g>
                                                                <circle id="Ellipse_362" data-name="Ellipse 362" cx="41"
                                                                        cy="41" r="41" transform="translate(10 10)"
                                                                        fill="#dc3545"/>
                                                                <g id="close" transform="translate(38.938 38.938)">
                                                                    <line id="Line_1" data-name="Line 1" x2="26" y2="26"
                                                                          transform="translate(-0.938 -0.938)"
                                                                          fill="none" stroke="#fff"
                                                                          stroke-linecap="round" stroke-width="6"/>
                                                                    <line id="Line_2" data-name="Line 2" x1="26" y2="26"
                                                                          transform="translate(-0.938 -0.938)"
                                                                          fill="none" stroke="#fff"
                                                                          stroke-linecap="round" stroke-width="6"/>
                                                                </g>
                                                            </svg>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $counter ++;
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $c_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $c_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div id="<?php echo e($counter); ?>" class="exercise-box question-item">
                                    <div class="exercise-box-header text-center">
                                        <span class="number"> <?php echo e($counter); ?> : </span>
                                        <span class="title"> اختر الإجابة الصحيحة</span>
                                    </div>
                                    <div class="exercise-box-body">
                                        <div class="exercise-question">
                                            <div class="exercise-question-data border-0">
                                                <div class="info">
                                                    <?php echo e($c_question->content); ?>

                                                </div>
                                            </div>
                                            <div class="exercise-question-answer text-center my-4">

                                                <?php if(!$c_question->getFirstMediaUrl('t_imageQuestion')): ?>

                                                    <div class="row justify-content-center py-3">
                                                        <div class="col-lg-6 col-md-8">
                                                            <?php if(\Illuminate\Support\Str::contains($c_question->getFirstMediaUrl('t_imageQuestion'), '.mp3')): ?>
                                                                <div class="recorder-player" id="voice_audio_2">
                                                                    <div class="audio-player">
                                                                        <audio >
                                                                            <source
                                                                                src="<?php echo e(asset($c_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                                type="audio/mpeg">
                                                                        </audio>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="w-100 text-center">
                                                                    <img src="<?php echo e(asset($c_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                         width="300px">
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="exercise-question-answer text-center my-4">

                                                    <div class="multi-choice-question">
                                                        <?php $__currentLoopData = $c_question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="answer-box">
                                                                <input id="option-<?php echo e($option->id); ?>"
                                                                       data-ans="<?php echo e($option->result == 1 ? 'true':'false'); ?>"
                                                                       type="radio" question-grop-name="q<?php echo e($counter); ?>"
                                                                       name="option[<?php echo e($c_question->id); ?>]"
                                                                       value="<?php echo e($option->id); ?>" class="co_q d-none">

                                                                <label for="option-<?php echo e($option->id); ?>"
                                                                       class="option option-true">
                                                                    <?php echo e($option->content); ?>

                                                                </label>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                    $counter ++
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $m_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $m_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div id="<?php echo e($counter); ?>" class="exercise-box question-item">
                                    <div class="exercise-box-header text-center">
                                        <span class="number"> <?php echo e($counter); ?> : </span>
                                        <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل</span>
                                    </div>
                                    <div class="exercise-box-body">
                                        <div class="exercise-question">
                                            <div class="exercise-question-data border-0">
                                                <div class="info">
                                                    <?php echo e($m_question->content); ?>

                                                </div>
                                                <div class="exercise-question-answer text-center my-4">
                                                    <?php if($m_question->getFirstMediaUrl('t_imageQuestion')): ?>

                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                <?php if(\Illuminate\Support\Str::contains($m_question->getFirstMediaUrl('t_imageQuestion'), '.mp3')): ?>
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio >
                                                                                <source
                                                                                    src="<?php echo e(asset($m_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                                    type="audio/mpeg">
                                                                            </audio>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="w-100 text-center">
                                                                        <img src="<?php echo e(asset($m_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                             width="300px">
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="multi-choice-question">
                                                        <div class="w-100 answers">
                                                            <div class="row justify-content-center">

                                                                <div class="col-md-12">
                                                                    <ul id="match_<?php echo e($m_question->id); ?>_result"
                                                                        class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                        <?php $__currentLoopData = $m_question->matches()->inRandomOrder()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li class="ui-state-default mb-2"
                                                                                data-id="<?php echo e($match->id); ?>">
                                                                                <div>
                                                                                    <text><?php echo e($match->result); ?> </text>
                                                                                    <span class="float-right"></span>
                                                                                    <input type="hidden"
                                                                                           name="re[<?php echo e($m_question->id); ?>][<?php echo e($match->id); ?>]"
                                                                                           id="" value="">
                                                                                </div>
                                                                            </li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </ul>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="ewewe">
                                                                        <div class="row">
                                                                            <div class="col-lg-8">
                                                                                <ul class="connectedNoSortable list-unstyled m-0 font-bold active text-right m-0 p-0">
                                                                                    <?php
                                                                                        $n_counter = 1;
                                                                                    ?>
                                                                                    <?php $__currentLoopData = $m_question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <li class="ui-state-default mb-2">
                                                                                            <?php if($match->getFirstMediaUrl('t_match')): ?>
                                                                                                <div
                                                                                                    class="row justify-content-center ">
                                                                                                    <div
                                                                                                        class="col-md-12 text-center">
                                                                                                        <img
                                                                                                            src="<?php echo e(asset($match->getFirstMediaUrl('t_match'))); ?>"
                                                                                                            style="width:100%; max-width: 100px"/>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php else: ?>
                                                                                                <span
                                                                                                    class="ml-3"></span>

                                                                                                <text><?php echo e($match->content); ?></text>
                                                                                            <?php endif; ?>
                                                                                        </li>
                                                                                        <?php
                                                                                            $n_counter ++;
                                                                                        ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-lg-4 ">
                                                                                <div class="position-relative">
                                                                                    <ul class="m-0 p-0 list-unstyled add-ansar position-absolute w-100">
                                                                                        <?php $__currentLoopData = $m_question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if(!$match->getFirstMediaUrl('t_match')): ?>
                                                                                                <?php
                                                                                                    $styleClass = "textOnly";
                                                                                                ?>
                                                                                                <li></li>
                                                                                            <?php else: ?>
                                                                                                <?php
                                                                                                    $styleClass = "imageOnly";
                                                                                                ?>
                                                                                                <li class="ui-state-default mb-2"
                                                                                                    style="height: auto !important;">
                                                                                                    <div
                                                                                                        class="row justify-content-center">
                                                                                                        <div
                                                                                                            class="col-12">
                                                                                                            <div>
                                                                                                                <div
                                                                                                                    style="width: 100px; height: 122px"></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </li>
                                                                                            <?php endif; ?>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </ul>
                                                                                    <ul id="match_<?php echo e($m_question->id); ?>_answer"
                                                                                        question-id="<?php echo e($counter); ?>"
                                                                                        class="sortable2 connectedSortable list-unstyled m-0 font-bold <?php echo e($styleClass); ?> active text-center m-0 p-0">
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="button" data-id="<?php echo e($m_question->id); ?>"
                                                                class="checkAnswer  btn btn-danger mx-4"
                                                                style="font-weight: bold;background-color: #dc3545;">
                                                    <span class="txt"
                                                          style="font-size: 18px">  تحقق من إجابتك </span>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php
                                    $counter ++
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $s_questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s_question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div id="<?php echo e($counter); ?>" class="exercise-box question-item">
                                    <div class="exercise-box-header text-center">
                                        <span class="number"> <?php echo e($counter); ?> : </span>
                                        <span class="title">  اسحب ورتب الإجابات في
                                        المكان أدناه </span>
                                    </div>
                                    <div class="exercise-box-body">
                                        <div class="exercise-question">
                                            <div class="exercise-question-data border-0">
                                                <div class="info">
                                                    <?php echo e($s_question->content); ?>

                                                </div>
                                                <div class="exercise-question-answer text-center my-4">
                                                    <?php if($s_question->getFirstMediaUrl('t_imageQuestion')): ?>

                                                        <div class="row justify-content-center py-3">
                                                            <div class="col-lg-6 col-md-8">
                                                                <?php if(\Illuminate\Support\Str::contains($s_question->getFirstMediaUrl('t_imageQuestion'), '.mp3')): ?>
                                                                    <div class="recorder-player" id="voice_audio_2">
                                                                        <div class="audio-player">
                                                                            <audio >
                                                                                <source
                                                                                    src="<?php echo e(asset($s_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                                    type="audio/mpeg">
                                                                            </audio>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="w-100 text-center">
                                                                        <img src="<?php echo e(asset($s_question->getFirstMediaUrl('t_imageQuestion'))); ?>"
                                                                             width="300px">
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="answers">
                                                        <div class="row justify-content-center">

                                                            <div class="col-md-12">
                                                                <ul id="sort_<?php echo e($s_question->id); ?>_answer"
                                                                    class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                    <?php $__currentLoopData = $s_question->sortWords()->inRandomOrder()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <li class="ui-state-default mb-2"
                                                                            data-id="<?php echo e($word->id); ?>">
                                                                            <div>
                                                                                <text><?php echo e($word->content); ?> </text>
                                                                                <span class="float-right"></span>
                                                                                <input type="hidden"
                                                                                       name="re[<?php echo e($s_question->id); ?>][<?php echo e($word->id); ?>]"
                                                                                       id="" value="">
                                                                            </div>
                                                                        </li>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </ul>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="ewewe">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="position-relative">
                                                                                <ul class="m-0 p-0 list-unstyled add-ansar  position-absolute w-100">

                                                                                </ul>
                                                                                <ul id="sort_<?php echo e($s_question->id); ?>_result"
                                                                                    question-id="<?php echo e($counter); ?>"
                                                                                    class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="button" data-id="<?php echo e($s_question->id); ?>"
                                                                class="checkSort btn btn-danger mx-4"
                                                                style="font-weight: bold;background-color: #dc3545;">
                                                        <span class="txt"
                                                              style="font-size: 18px">  تحقق من إجابتك </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <?php
                                    $counter ++
                                ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </div>

                        <div class="d-flex justify-content-center question-control btn-wizard">
                            <div class="text-center">
                                <button type="button" class="d-none btn btn-light border"
                                        id="previousQuestion"><span class="txt"
                                                                    style="font-size: 18px">  السابق </span>
                                </button>
                            </div>
                            <div class="text-center">




                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-theme"
                                        id="nextQuestion">
                                    <span class="txt" style="font-size: 18px"> التالي  </span></button>
                            </div>
                        </div>


                        <div class="table-footer font-weight-bold">
                            <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <audio controls style="display:none"
           id="false_audio"
           src="<?php echo e(asset('website/false_result.mp3')); ?>"></audio>
    <audio controls style="display:none"
           id="ture_audio"
           src="<?php echo e(asset('website/true_result.mp3')); ?>"></audio>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>

    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(asset('s_website/js/jquery.ui.touch-punch.js')); ?>"></script>

    <script>
        $(document).ready(function () {
            $('.audio').click(function () {
                var elem = $(this);
                var data_id = $(this).attr('data-id');
                $('audio').each(function () {
                    this.pause(); // Stop playing
                    this.currentTime = 0; // Reset time
                    console.log('pause');
                });
                console.log('#audio' + data_id);
                $('#audio' + data_id)[0].currentTime = 0;
                $('#audio' + data_id)[0].play();
                console.log('play');


            });

            $('.tf_q').click(function () {
                var ans = $(this).attr('data-ans');
                console.log(ans);
            });
            $('.co_q').click(function () {
                var ans = $(this).attr('data-ans');
                console.log(ans);
            });


        });
        $(function () {
            $(".sortable1, .sortable2").sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        });

        $(".sortable2").droppable({
            drop: function () {

                $questionId = $(this).attr('question-id');
                //alert($questionId);


                setTimeout(function () {
                    $i = 1;
                    $('.sortable2[question-id = ' + $questionId + '] li span').each(function () {
                        //$(this).html($i++ );
                    });
                }, 1);
                setTimeout(function () {
                    $i = 1;
                    $('.sortable2[question-id = ' + $questionId + '] li input').each(function () {
                        $(this).val($i++);
                    });
                }, 1);
            }
        });

        $(".sortable1").droppable({
            drop: function () {
                setTimeout(function () {
                    $('.sortable1 li span').each(function (i) {
                        var humanNum = i + 1;
                        $(this).html('');
                    });
                }, 1);
                setTimeout(function () {
                    $('.sortable1 li input').each(function (i) {
                        var humanNum = i + 1;
                        $(this).val('');
                    });
                }, 1);
            }
        });


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('teacher.user_curriculum.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/teacher/user_curriculum/lesson/training.blade.php ENDPATH**/ ?>