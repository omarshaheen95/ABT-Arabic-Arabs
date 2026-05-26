<div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?>  question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> <?php echo e($counter); ?> : </span>
        <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل –  Drag and order the answers in the below box</span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    <?php echo e($question->content); ?>

                </div>
                <div class="exercise-question-answer text-center my-4">
                    <?php if(!is_null($question->attachment)): ?>

                        <div class="row justify-content-center py-3">
                            <div class="col-lg-6 col-md-8">
                                <?php if(\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                    <div class="recorder-player"
                                         id="voice_audio_2">
                                        <div class="audio-player">
                                            <audio crossorigin>
                                                <source
                                                    src="<?php echo e(asset($question->attachment)); ?>"
                                                    type="audio/mpeg">
                                            </audio>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-100 text-center">
                                        <img
                                            src="<?php echo e(asset($question->attachment)); ?>"
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
                                    <?php
                                        if (isset($story)){
                                            $words = $question->sort_words->whereNotIn('id',$question->sort_results->pluck('story_sort_word_id'));
                                        }elseif(isset($lesson)){
                                            $words = $question->sortWords->whereNotIn('id',$question->sort_results->pluck('sort_word_id'));
                                        }
                                    ?>
                                    <?php $__currentLoopData = $words; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                    <?php if(isset($question->sort_results) && count($question->sort_results)>0): ?>
                                                        <?php $__currentLoopData = $question->sort_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li class="ui-state-default mb-2"
                                                                <?php if(isset($story)): ?>
                                                                    data-id="<?php echo e($word->story_sort_word_id); ?>"
                                                                <?php elseif(isset($lesson)): ?>
                                                                    data-id="<?php echo e($word->sort_word_id); ?>"
                                                                <?php endif; ?>
                                                            >
                                                                <div>
                                                                    <text><?php echo e($word->sort_word->content); ?> </text>
                                                                    <span
                                                                        class="float-right"></span>
                                                                    <input type="hidden"
                                                                           <?php if(isset($story)): ?>
                                                                               name="sort[<?php echo e($question->id); ?>][<?php echo e($word->story_sort_word_id); ?>]"
                                                                           <?php elseif(isset($lesson)): ?>
                                                                               name="sort[<?php echo e($question->id); ?>][<?php echo e($word->sort_word_id); ?>]"
                                                                           <?php endif; ?>
                                                                           id="" value="">
                                                                </div>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/test_preview/questions/sort.blade.php ENDPATH**/ ?>