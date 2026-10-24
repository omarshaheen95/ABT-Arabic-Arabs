<div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?> question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> <?php echo e($counter); ?> : </span>
        <span class="title"> اسحب ورتب الإجابات في
                                        المكان أدناه </span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    <?php echo e($question->content); ?>

                </div>
                <div class="exercise-question-answer text-center my-4">
                    <?php if($question->getFirstMediaUrl($file_name)): ?>

                        <div class="row justify-content-center py-3">
                            <div class="col-lg-6 col-md-8">
                                <?php if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl($file_name), '.mp3')): ?>
                                    <div class="recorder-player"
                                         id="voice_audio_2">
                                        <div class="audio-player">
                                            <audio >
                                                <source
                                                    src="<?php echo e(asset($question->getFirstMediaUrl($file_name))); ?>"
                                                    type="audio/mpeg">
                                            </audio>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-100 text-center">
                                        <img
                                            src="<?php echo e(asset($question->getFirstMediaUrl($file_name))); ?>"
                                            width="300px">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="answers">
                        <div class="row justify-content-center">

                            <div class="col-md-12">
                                <ul id="sort_<?php echo e($question->id); ?>_answer"
                                    class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                    <?php if(!isset($preview)): ?>
                                        <?php $__currentLoopData = $question->sortWords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="ui-state-default mb-2"
                                                data-id="<?php echo e($word->id); ?>">
                                                <div>
                                                    <text><?php echo e($word->content); ?> </text>
                                                    <span
                                                        class="float-right"></span>
                                                    <input type="hidden"
                                                           name="sort[<?php echo e($question->id); ?>][<?php echo e($word->id); ?>]"
                                                           id="" value="">
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <div class="ewewe">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="position-relative">
                                                <ul class="m-0 p-0 list-unstyled add-ansar  position-absolute w-100">

                                                </ul>
                                                <ul id=""
                                                    question-id="<?php echo e($counter); ?>"
                                                    class="sortable2 sort_words connectedSortable list-unstyled m-0 font-bold active text-center m-0 p-0">
                                                    <?php if(isset($preview)): ?>
                                                        <?php $__currentLoopData = $question->sortWords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li class="ui-state-default mb-2"
                                                                data-id="<?php echo e($word->id); ?>">
                                                                <div>
                                                                    <text><?php echo e($word->content); ?> </text>
                                                                    <span
                                                                        class="float-right"></span>
                                                                    <input type="hidden"
                                                                           name="sort[<?php echo e($question->id); ?>][<?php echo e($word->id); ?>]"
                                                                           id="" value="">
                                                                </div>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/lesson/parts/sort.blade.php ENDPATH**/ ?>