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
                        <div class="answer-box justify-content-center mt-2">
                            <div class="answers" data-question="<?php echo e($question->id); ?>">
                                <div class="row justify-content-center">

                                    
                                    <div class="col-md-12 mb-3">
                                        <div data-question="<?php echo e($question->id); ?>" id=""
                                             class="sortOptions sortConnected list-unstyled font-bold text-center d-flex justify-content-around">
                                            <?php
                                                if (isset($story)){
                                                    $words = $question->sort_words->whereNotIn('uid',$question->sort_results->pluck('story_sort_answer_uid'));
                                                }elseif(isset($lesson)){
                                                    $words = $question->sortWords->whereNotIn('uid',$question->sort_results->pluck('sort_answer_uid'));
                                                }
                                            ?>
                                            <?php $__currentLoopData = $words; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sort): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div data-question="<?php echo e($question->id); ?>" class="ui-state-default add-answer me-1"
                                                     data-id="<?php echo e($sort->uid); ?>">
                                                    <text><?php echo e($sort->content); ?> </text>
                                                    <span class="float-right"></span>
                                                    <input class="sort-answer-input" type="hidden" name="sorting[<?php echo e($question->id); ?>][<?php echo e($sort->uid); ?>]" id="" value="">
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-12">
                                        <div class="row item-container">
                                            <div data-question="<?php echo e($question->id); ?>" class="list-unstyled m-0 font-bold active text-right m-0 p-0">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="item">
                                                            <ul data-question="<?php echo e($question->id); ?>" data-index="<?php echo e($loop->iteration); ?>" class="d-flex align-items-center gap-3 px-3 sortAnswers sortWords sortConnected list-unstyled m-0 font-bold active textOnly text-center m-0 p-0">
                                                                <?php $__currentLoopData = $question->sort_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        if (isset($story)){
                                                                            $uid = $answer->story_sort_answer_uid;
                                                                            $content = $question->sort_words->where('uid',$uid)->first()->content;
                                                                        }elseif(isset($lesson)){
                                                                            $uid = $answer->sort_answer_uid;
                                                                            $content = $question->sortWords->where('uid',$uid)->first()->content;
                                                                        }
                                                                    ?>
                                                                    <div data-question="<?php echo e($question->id); ?>" class="ui-state-default add-answer me-1 ui-sortable-handle" data-id="<?php echo e($uid); ?>" style="">
                                                                        <text><?php echo e($content); ?> </text>
                                                                        <span class="float-right"></span>
                                                                        <input type="hidden" name="sorting[<?php echo e($question->id); ?>][<?php echo e($uid); ?>]" id="" value="<?php echo e($loop->iteration); ?>">
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        </div>
    </div>


</div>

<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/correcting_lesson_and_story_test/questions/sort.blade.php ENDPATH**/ ?>