<div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?>  question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> <?php echo e($counter); ?> : </span>
        <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل – Drag the answers in the
                                        right places below </span>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="answer-box justify-content-center w-100 mt-2">
                                <div class="answers" data-question="<?php echo e($question->id); ?>">
                                    <div class="row justify-content-center">

                                        
                                        <div class="col-md-12 mb-3">
                                            <div data-question="<?php echo e($question->id); ?>" id=""
                                                 class="matchOptions matchConnected list-unstyled font-bold text-center d-flex justify-content-around">
                                                <?php $__currentLoopData = $question->matches->whereNotIn('uid',$question->match_results->pluck('match_answer_uid'))->shuffle(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div data-question="<?php echo e($question->id); ?>" class="ui-state-default add-answer"
                                                         data-id="<?php echo e($match->uid); ?>">
                                                        <text><?php echo e($match->result); ?></text>
                                                        <span class="float-right"></span>
                                                        <input class="matching-answer-input" type="hidden" name="matching[<?php echo e($question->id); ?>][<?php echo e($match->uid); ?>]" id="" value="">
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-12">
                                            <div class="row item-container">
                                                <div data-question="<?php echo e($question->id); ?>" class="list-unstyled m-0 font-bold active text-right m-0 p-0">
                                                    <?php $__currentLoopData = $question->matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="row">
                                                            <div class="col-md-8 mb-2">
                                                                <div class="ui-state-default mb-2 question-option item">
                                                                    <?php if(!is_null($match->image)): ?>
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-md-12 text-center">
                                                                                <img src="<?php echo e(asset($match->image)); ?>" class="match-img" />
                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <span class="ml-3"></span>
                                                                        <text class="fs-5 fw-bold"><?php echo e($match->content); ?></text>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-2 matching-answers-box">
                                                                <div class="item match-item" style="border: 2px dashed #5d87e8;border-radius: 10px">
                                                                    <ul data-question="<?php echo e($question->id); ?>" data-index="<?php echo e($match->id); ?>"
                                                                        class="matchAnswers m-0 matchWords matchConnected list-unstyled d-flex align-items-center justify-content-center">
                                                                        <?php
                                                                            $match_a = null;
                                                                             if(isset($story)){
                                                                               $match_answer = $question->match_results->where('story_match_id',$match->id)->first();
                                                                             } elseif(isset($lesson)){
                                                                              $match_answer = $question->match_results->where('match_id',$match->id)->first();
                                                                             }

                                                                            if ($match_answer){
                                                                             $match_a = $question->matches->where('uid',$match_answer->match_answer_uid)->first();
                                                                            }
                                                                        ?>
                                                                        <?php if($match_a): ?>
                                                                            <div data-question="<?php echo e($question->id); ?>" class="ui-state-default add-answer ui-sortable-handle" data-id="<?php echo e($match_a->uid); ?>" style="">
                                                                                <text><?php echo e($match_a->result); ?></text>
                                                                                <span class="float-right"></span>
                                                                                <input type="hidden" name="matching[<?php echo e($question->id); ?>][<?php echo e($match_a->uid); ?>]" id="" value="<?php echo e($match->id); ?>">
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/correcting_lesson_and_story_test/questions/match.blade.php ENDPATH**/ ?>