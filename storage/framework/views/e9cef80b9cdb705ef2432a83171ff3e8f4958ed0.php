


<?php
if ($type == 'assessment'){
       $file_name = 'imageQuestion';
       $title = t('Lesson Assessment Preview');

}elseif($type == 'training'){
       $file_name = 'imageQuestion';
       $title = t('Lesson Training Preview');

}

?>
<?php $__env->startSection('content'); ?>
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mb-4">
                        <h3 class="title"> الصف : <?php echo e($lesson->grade_name); ?> </h3>
                        <h1 class="title"><p id="countdown" class="mb-0 text-danger" style="font-size:32px"></p></h1>

                        <nav class="breadcrumb fw-bold">
                            <?php echo e($title); ?> : <?php echo e($lesson->name); ?>

                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="exam-card box-shado-question" dir="rtl">
                    <div class="exam-body question-list">
                        <form  id="term_form" method="post">
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
                                        <?php echo $__env->make('general.user.lesson.parts.true_false', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($question->type == 2): ?>
                                        <?php echo $__env->make('general.user.lesson.parts.options', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($question->type == 3): ?>
                                        <?php echo $__env->make('general.user.lesson.parts.match', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif($question->type == 4): ?>
                                        <?php echo $__env->make('general.user.lesson.parts.sort', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                    <button type="button" class="btn btn-theme"
                                            id="nextQuestion">
                                        <span class="txt" style="font-size: 18px"> التالي  </span></button>
                                </div>
                            </div>


                            <div class="table-footer font-weight-bold">
                                <ul class="list-inline m-0 p-0 w-100 text-center" id="questionListLink">
                                </ul>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo e(asset('s_website/js/jquery.ui.touch-punch.js')); ?>"></script>

    <script>

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


<?php echo $__env->make('general.user.std_container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/lesson/preview/assessment_and_training.blade.php ENDPATH**/ ?>