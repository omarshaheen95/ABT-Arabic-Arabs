<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/exam_questions.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : <?php echo e($lesson->grade_name); ?> </h3>
                        <h1 class="title"><p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p></h1>

                        <nav class="breadcrumb">
                            <a class="breadcrumb-item" href="<?php echo e(route('lessons', [$lesson->grade_id, $lesson->lesson_type])); ?>">
                                <?php echo e($lesson->type_name); ?> </a>
                            <span class="breadcrumb-item active" aria-current="page">الدروس </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form action="<?php echo e(route('lesson_test', $lesson->id)); ?>" id="term_form" method="post">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="start_at" value="<?php echo e(\Carbon\Carbon::now()); ?>">
                            <div class="justify-content-between align-items-center mb-4">



                            </div>

                            <div class="question-list">
                                <?php
                                    $counter = 1;
                                ?>
                                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($question->type == 1): ?>
                                        <div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?> question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> <?php echo e($counter); ?> : </span>
                                                <span class="title">ضع علامة (صح) أو (خطأ)</span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            <?php echo e($question->content); ?>

                                                        </div>
                                                    </div>
                                                    <div class="exercise-question-answer text-center my-4">

                                                        <?php if($question->getFirstMediaUrl('imageQuestion')): ?>

                                                            <div class="row justify-content-center py-3">
                                                                <div class="col-lg-6 col-md-8">
                                                                    <?php if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3')): ?>
                                                                        <div class="recorder-player" id="voice_audio_2">
                                                                            <div class="audio-player">
                                                                                <audio >
                                                                                    <source
                                                                                        src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>"
                                                                                        type="audio/mpeg">
                                                                                </audio>
                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>"
                                                                                 width="300px">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="true-false-question">
                                                            <div class="answer-box">
                                                                <input
                                                                    type="radio" question-grop-name="q<?php echo e($counter); ?>"
                                                                    name="tf[<?php echo e($question->id); ?>]" value="1"
                                                                    id="true-<?php echo e($question->id); ?>" class="d-none">
                                                                <label for="true-<?php echo e($question->id); ?>"
                                                                       class="option option-true">
                                                                    <svg id="Group_68450" data-name="Group 68450"
                                                                         xmlns="http://www.w3.org/2000/svg" width="80"
                                                                         height="80" viewBox="0 0 102 102">
                                                                        <g id="Ellipse_360" data-name="Ellipse 360"
                                                                           fill="#fff"
                                                                           stroke="#2ecc71" stroke-width="1">
                                                                            <circle cx="51" cy="51" r="51"
                                                                                    stroke="none"/>
                                                                            <circle cx="51" cy="51" r="50.5"
                                                                                    fill="none"/>
                                                                        </g>
                                                                        <circle id="Ellipse_180" data-name="Ellipse 180"
                                                                                cx="41"
                                                                                cy="41" r="41"
                                                                                transform="translate(10 10)"
                                                                                fill="#2ecc71"/>
                                                                        <path id="Shape"
                                                                              d="M12.176,23.045,3.093,13.962,0,17.033,12.176,29.209,38.314,3.071,35.243,0Z"
                                                                              transform="translate(32.856 37.409)"
                                                                              fill="#fff"/>
                                                                    </svg>
                                                                </label>
                                                            </div>
                                                            <div class="answer-box">
                                                                <input
                                                                    type="radio" question-grop-name="q<?php echo e($counter); ?>"
                                                                    name="tf[<?php echo e($question->id); ?>]" value="0"
                                                                    id="false-<?php echo e($question->id); ?>" class="d-none">
                                                                <label for="false-<?php echo e($question->id); ?>"
                                                                       class="option option-false">
                                                                    <svg id="Group_68451" data-name="Group 68451"
                                                                         xmlns="http://www.w3.org/2000/svg" width="80"
                                                                         height="80" viewBox="0 0 102 102">
                                                                        <g id="Ellipse_361" data-name="Ellipse 361"
                                                                           fill="#fff"
                                                                           stroke="#dc3545" stroke-width="1">
                                                                            <circle cx="51" cy="51" r="51"
                                                                                    stroke="none"/>
                                                                            <circle cx="51" cy="51" r="50.5"
                                                                                    fill="none"/>
                                                                        </g>
                                                                        <circle id="Ellipse_362" data-name="Ellipse 362"
                                                                                cx="41"
                                                                                cy="41" r="41"
                                                                                transform="translate(10 10)"
                                                                                fill="#dc3545"/>
                                                                        <g id="close"
                                                                           transform="translate(38.938 38.938)">
                                                                            <line id="Line_1" data-name="Line 1" x2="26"
                                                                                  y2="26"
                                                                                  transform="translate(-0.938 -0.938)"
                                                                                  fill="none" stroke="#fff"
                                                                                  stroke-linecap="round"
                                                                                  stroke-width="6"/>
                                                                            <line id="Line_2" data-name="Line 2" x1="26"
                                                                                  y2="26"
                                                                                  transform="translate(-0.938 -0.938)"
                                                                                  fill="none" stroke="#fff"
                                                                                  stroke-linecap="round"
                                                                                  stroke-width="6"/>
                                                                        </g>
                                                                    </svg>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($question->type == 2): ?>
                                        <div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?> question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> <?php echo e($counter); ?> : </span>
                                                <span
                                                    class="title"> اختر الإجابة الصحيحة</span>
                                            </div>
                                            <div class="exercise-box-body">
                                                <div class="exercise-question">
                                                    <div class="exercise-question-data border-0">
                                                        <div class="info">
                                                            <?php echo e($question->content); ?>

                                                        </div>
                                                    </div>
                                                    <div class="exercise-question-answer text-center my-4">

                                                        <?php if($question->getFirstMediaUrl('imageQuestion')): ?>

                                                            <div class="row justify-content-center py-3">
                                                                <div class="col-lg-6 col-md-8">
                                                                    <?php if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3')): ?>
                                                                        <div class="recorder-player" id="voice_audio_2">
                                                                            <div class="audio-player">
                                                                                <audio >
                                                                                    <source
                                                                                        src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>"
                                                                                        type="audio/mpeg">
                                                                                </audio>
                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>"
                                                                                 width="300px">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="exercise-question-answer text-center my-4">

                                                            <div class="multi-choice-question">
                                                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="answer-box">
                                                                        <input id="option-<?php echo e($option->id); ?>"
                                                                               type="radio"
                                                                               question-grop-name="q<?php echo e($counter); ?>"
                                                                               name="option[<?php echo e($question->id); ?>]"
                                                                               value="<?php echo e($option->id); ?>"
                                                                               class="co_q d-none">

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
                                    <?php elseif($question->type == 3): ?>
                                        <div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?>  question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> <?php echo e($counter); ?> : </span>
                                                <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل – Drag the answers in the right places below </span>
                                            </div>

                                            <?php echo $__env->make('user.lesson.questions.matching', ['question' => $question], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        </div>
                                    <?php elseif($question->type == 4): ?>
                                        <div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?>  question-item">
                                            <div class="exercise-box-header text-center">
                                                <span class="number"> <?php echo e($counter); ?> : </span>
                                                <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل –  Drag and order the answers in the below box</span>
                                            </div>
                                            <?php echo $__env->make('user.lesson.questions.sorting', ['question' => $question], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                        $counter ++;
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
                                    <button type="button" class="btn btn-theme d-none endExam" id="confirmed_modal"
                                            data-toggle="modal" data-target="#endExam"
                                            style="font-weight: bold;background-color: #0043b3;">
                                        <span class="txt" style="font-size: 18px">إنهاء</span>
                                    </button>
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
                            <!-- Modal -->

                            <div class="modal fade" id="endExam" tabindex="-1" role="dialog"
                                 aria-labelledby="endExamLabel" aria-hidden="true">

                                <div class="modal-dialog">
                                    <div class="modal-content" style="padding: 15px;">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center py-5"><h2 class="mb-0"
                                                                                     style="direction: ltr">
                                                هل أنت متأكد من حفظ الاختبار </h2>
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="submit" class="btn btn-soft-danger me-3"
                                                    id="save_assessment"><span
                                                    class="txt">  نعم احفظ الاختبار</span></button>
                                            <button type="button" class="btn btn-light border"
                                                    data-bs-dismiss="modal"><span
                                                    class="txt"> أريد البقاء في الاختبار </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

    <script src="<?php echo e(asset('s_website/js/jquery-3.6.3.min.js')); ?>"></script>
    <script src="<?php echo e(asset('s_website/js/jquery.ui.touch-punch.js')); ?>"></script>
    <script src="<?php echo e(asset('s_website/js/jquery-ui.js')); ?>"></script>
    <script src="<?php echo e(asset('s_website/js/questions/matching.js')); ?>"></script>
    <script src="<?php echo e(asset('s_website/js/questions/sorting.js')); ?>"></script>

    <script>
        $(document).ready(function () {
            $('#confirmed_modal').click(function (e) {
                $("#endExam").modal("show");
            });
            $('#save_assessment').click(function (e) {
                e.preventDefault();
                $(this).attr('disabled', true);
                $('#confirmed_modal').attr('disabled', true);
                $('#term_form').submit();
            })
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
        });

        Date.prototype.addHours = function (h) {
            this.setTime(this.getTime() + (h * 60 * 60 * 1000));
            return this;
        }
        // Set the date we're counting down to
        var countDownDate = new Date().addHours(0.1666666).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML = minutes + ":" + seconds;
            // console.log(distance);
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $('term_form').submit();
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);

    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('user.layout.container_v2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/lesson/test.blade.php ENDPATH**/ ?>