<?php $__env->startSection('style'); ?>
    <style>
        text{
            font-size: 14px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('stories.list', $story->grade)); ?>" ><?php echo e(t('Stories')); ?></a>
    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="inner-page">
        <section>
            <div class="container text-right">
                <div class="row">
                    <div class="col-12">

                        <form action="<?php echo e(route('story_test', $story->id)); ?>" id="term_form" method="post">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="start_at" value="<?php echo e(\Carbon\Carbon::now()); ?>">

                            <div class="box-shado-question">
                                <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 mob-text-center" style="font-size: 20px;color: #000;"> <?php echo e($story->translate('ar')->name); ?> - <?php echo e($story->translate('en')->name); ?></div>
                                        <div class="col-md-4 mob-text-center text-center"><p id="countdown" class="mb-0 text-danger"></p></div>
                                        <div class="col-md-4 mob-text-center text-left" style="color: #000;">
                                            <?php echo e(w('Question')); ?>

                                            <span id="questionNumber"></span>
                                            <?php echo e(w('from')); ?>

                                            <span id="numberQuestions"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-50" style="border: 3px #067af1 solid;">
                                    <div class="question-list">
                                        <?php
                                            $counter = 1;
                                        ?>
                                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($question->type == 1): ?>
                                                    <div class="question-item <?php echo e($loop->first ? 'active':''); ?>" id="<?php echo e($counter); ?>">
                                                        <h3 class="text-danger"> أكد إذا كانت هذه الجملة صواب أم خطأ - Confirm whether these sentence are true or false</h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  <?php echo e($question->content); ?> </h2>
                                                                </div>
                                                            </div>
                                                            <?php if(!is_null($question->attachment)): ?>
                                                                <div class="col-4">
                                                                    <?php if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->attachment)); ?>" width="300px">
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="<?php echo e($question->id); ?>" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio<?php echo e($question->id); ?>" src="<?php echo e(asset($question->attachment)); ?>"></audio>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row text-center justify-content-center">
                                                                <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20"><input type="radio" question-grop-name="q<?php echo e($counter); ?>" name="tf[<?php echo e($question->id); ?>]" value="1"> <i class="fa fa-check-circle fa-fw text-success fa-2x"></i></label></div>
                                                                <div class="col-md-3"><label class="mb-0 d-block py-3 fs-20"><input type="radio" question-grop-name="q<?php echo e($counter); ?>" name="tf[<?php echo e($question->id); ?>]" value="0"> <i class="fa fa-times-circle fa-fw text-danger fa-2x"></i></label></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif($question->type == 2): ?>
                                                    <div class="question-item <?php echo e($loop->first ? 'active':''); ?>" id="<?php echo e($counter); ?>">
                                                        <h3 class="text-danger"> اختر الإجابة الصحيحة - Choose the correct answer</h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  <?php echo e($question->content); ?> </h2>
                                                                </div>
                                                            </div>
                                                            <?php if(!is_null($question->attachment)): ?>
                                                                <div class="col-4">
                                                                    <?php if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->attachment)); ?>" width="300px">
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="<?php echo e($question->id); ?>" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio<?php echo e($question->id); ?>" src="<?php echo e(asset($question->attachment)); ?>"></audio>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row text-center  justify-content-center">
                                                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-md-3"><label class="mb-0 d-block py-3"><input type="radio" question-grop-name="q<?php echo e($counter); ?>" name="option[<?php echo e($question->id); ?>]" value="<?php echo e($option->id); ?>"> <?php echo e($option->content); ?> </label></div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif($question->type == 3): ?>
                                                    <div class="question-item <?php echo e($loop->first ? 'active':''); ?>" id="<?php echo e($counter); ?>">
                                                        <h3 class="text-danger"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل – Drag the answers in the right places below </h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  <?php echo e($question->content); ?> </h2>
                                                                </div>
                                                            </div>
                                                            <?php if(!is_null($question->attachment)): ?>
                                                                <div class="col-4">
                                                                    <?php if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->attachment)); ?>" width="300px">
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="<?php echo e($question->id); ?>" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio<?php echo e($question->id); ?>" src="<?php echo e(asset($question->attachment)); ?>"></audio>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row justify-content-center">

                                                                <div class="col-md-12">
                                                                    <ul id="" class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                        <?php $__currentLoopData = $question->matches()->inRandomOrder()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li class="ui-state-default mb-2" data-id="<?php echo e($match->id); ?>">
                                                                                <div>
                                                                                    <text><?php echo e($match->result); ?> </text>
                                                                                    <span class="float-right"></span>
                                                                                    <input type="hidden" name="re[<?php echo e($question->id); ?>][<?php echo e($match->id); ?>]" id="" value="" >
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
                                                                                    <?php $__currentLoopData = $question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <li class="ui-state-default mb-2">
                                                                                            <?php if(!is_null($match->image)): ?>
                                                                                                <div
                                                                                                    class="row justify-content-center ">
                                                                                                    <div
                                                                                                        class="col-md-12 text-center">
                                                                                                        <img
                                                                                                            src="<?php echo e(asset($match->image)); ?>"
                                                                                                            style="width:100%; max-width: 100px"/>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php else: ?>
                                                                                                <span class="ml-3"></span>

                                                                                                <text><?php echo e($match->content); ?></text>
                                                                                            <?php endif; ?>
                                                                                        </li>
                                                                                        <?php
                                                                                            $n_counter ++;
                                                                                        ?>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-lg-4">
                                                                                <div class="position-relative">
                                                                                    <ul class="m-0 p-0 list-unstyled add-ansar position-absolute w-100">
                                                                                        <?php $__currentLoopData = $question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php if(is_null($match->image)): ?>
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
                                                                                    <ul id="" question-id="<?php echo e($counter); ?>" class="sortable2 connectedSortable list-unstyled m-0 font-bold active <?php echo e($styleClass); ?> text-center m-0 p-0">
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif($question->type == 4): ?>
                                                    <div class="question-item <?php echo e($loop->first ? 'active':''); ?>" id="<?php echo e($counter); ?>">
                                                        <h3 class="text-danger"> اسحب ورتب الإجابات في المكان أدناه – Drag and order the answers in the below box </h3>
                                                        <hr />
                                                        <div class="row mt-4 justify-content-center">
                                                            <div class="col-8">
                                                                <div class="w-100 text-center mt-4">
                                                                    <h2>  <?php echo e($question->content); ?> </h2>
                                                                </div>
                                                            </div>
                                                            <?php if(!is_null($question->attachment)): ?>
                                                                <div class="col-4">
                                                                    <?php if(!\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                                                        <div class="w-100 text-center">
                                                                            <img src="<?php echo e(asset($question->attachment)); ?>" width="300px">
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="w-100 text-center mt-4">
                                                                            <button type="button" data-id="<?php echo e($question->id); ?>" class="audio btn btn-success btn-elevate btn-circle btn-icon">
                                                                                <i class="fa fa-play-circle fa-2x"></i>
                                                                            </button>
                                                                            <audio controls style="display:none" id="audio<?php echo e($question->id); ?>" src="<?php echo e(asset($question->attachment)); ?>"></audio>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="answers">
                                                            <div class="row justify-content-center">

                                                                <div class="col-md-12">
                                                                    <ul id="" class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                                                        <?php $__currentLoopData = $question->sort_words()->inRandomOrder()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li class="ui-state-default mb-2" data-id="<?php echo e($word->id); ?>">
                                                                                <div>
                                                                                    <text><?php echo e($word->content); ?> </text>
                                                                                    <span class="float-right"></span>
                                                                                    <input type="hidden" name="sort[<?php echo e($question->id); ?>][<?php echo e($word->id); ?>]" id="" value="" >
                                                                                </div>
                                                                            </li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </ul>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="ewewe" style="border: #0043b3 solid 2px !important; box-shadow: none;">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="position-relative">
                                                                                    <ul class="m-0 p-0 list-unstyled add-ansar  position-absolute w-100">

                                                                                    </ul>
                                                                                    <ul id="" question-id="<?php echo e($counter); ?>" class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php
                                                $counter ++;
                                            ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="d-flex justify-content-center question-control">
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4 d-none" id="previousQuestion"><i class="far fa-chevron-right position-relative ml-3" style="z-index: 9;"></i><span class="txt" style="font-size: 18px">  <?php echo e(w('Previous question')); ?> </span></button></div>
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4 d-none endExam" id="confirmed_modal" data-toggle="modal" data-target="#endExam"><span class="txt" style="font-size: 18px"><?php echo e(w('Save the assessment')); ?></span></button></div>
                                        <div class="text-center"><button type="button" class="theme-btn btn-style-one mx-4" id="nextQuestion"><span class="txt" style="font-size: 18px"> <?php echo e(w('Next question')); ?>  </span> <i class="far fa-chevron-left position-relative mr-3" style="z-index: 9;"></i> </button></div>
                                    </div>
                                </div>
                                <div class="bg-light text-secondary font-weight-bold py-3 px-4">
                                    <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                    </ul>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="endExam" tabindex="-1" aria-labelledby="endExamLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body text-center py-5"> <h2 class="mb-0" style="direction: ltr"><?php echo e(w('You are going to submitting your assessment,  are you sure ?')); ?> </h2></div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="submit" class="theme-btn btn-style-one btnSecondary" id="save_assessment"><span class="txt"> <?php echo e(w('Yes submit my assessment')); ?></span></button>
                                            <button type="button" class="theme-btn btn-style-one btnSuccess" data-dismiss="modal"><span class="txt"> <?php echo e(w('No i want to continue answering')); ?> </span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </section>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>

    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(asset('s_website/js/jquery.ui.touch-punch.js')); ?>"></script>

    <script>
        $(document).ready(function (){
            $('#save_assessment').click(function (e){
                e.preventDefault();
                $(this).attr('disabled', true);
                $('#confirmed_modal').attr('disabled', true);
                $('#term_form').submit();
            })
            $('.audio').click(function(){
                var elem = $(this);
                var data_id = $(this).attr('data-id');
                $('audio').each(function(){
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

        Date.prototype.addHours = function(h) {
            this.setTime(this.getTime() + (h*60*60*1000));
            return this;
        }
        // Set the date we're counting down to
        var countDownDate = new Date().addHours(0.1666666).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

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
        $( function() {
            $( ".sortable1, .sortable2" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        } );







        $(".sortable2").droppable({
            drop: function() {

                $questionId = $(this).attr('question-id');
                //alert($questionId);


                setTimeout(function(){
                    $i = 1;
                    $('.sortable2[question-id = '+ $questionId +'] li span').each(function () {
                        //$(this).html($i++ );
                    });
                }, 1);
                setTimeout(function(){
                    $i = 1;
                    $('.sortable2[question-id = '+ $questionId +'] li input').each(function () {
                        $(this).val($i++);
                    });
                }, 1);
            }
        });



        $(".sortable1").droppable({
            drop: function() {
                setTimeout(function(){
                    $('.sortable1 li span').each(function (i) {
                        var humanNum = i + 1;
                        $(this).html('');
                    });
                }, 1);
                setTimeout(function(){
                    $('.sortable1 li input').each(function (i) {
                        var humanNum = i + 1;
                        $(this).val('');
                    });
                }, 1);
            }
        });
    </script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('user.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/story/test_old.blade.php ENDPATH**/ ?>