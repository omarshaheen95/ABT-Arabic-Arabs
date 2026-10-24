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
                    <div class="multi-choice-question">
                        <div class="w-100 answers">
                            <div class="row justify-content-center">

                                <div class="col-md-12">
                                    <ul id="match_<?php echo e($question->id); ?>_result"
                                        class="sortable1 connectedSortable list-unstyled font-bold text-center d-flex justify-content-around">
                                        <?php
                                            if (isset($story)){
                                            $matches = $question->matches->whereNotIn('id',$question->match_results->pluck('story_match_id'));                                            }elseif(isset($lesson)){
                                            }
                                            if(isset($lesson)){
                                            $matches = $question->matches->whereNotIn('id',$question->match_results->pluck('match_id'));                                            }elseif(isset($lesson)){
                                            }
                                        ?>

                                        <?php $__currentLoopData = $matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="ui-state-default mb-2"
                                                data-id="<?php echo e($match->id); ?>">
                                                <div>
                                                    <text><?php echo e($match->result); ?> </text>
                                                    <span
                                                        class="float-right"></span>
                                                    <input type="hidden"
                                                           name="re[<?php echo e($question->id); ?>][<?php echo e($match->id); ?>]"
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
                                                    <ul id="match_<?php echo e($question->id); ?>_answer"
                                                        question-id="<?php echo e($counter); ?>"
                                                        class="sortable2 connectedSortable list-unstyled m-0 font-bold <?php echo e($styleClass); ?> active text-center m-0 p-0">
                                                        <?php if(isset($question->match_results)&&count($question->match_results)>0): ?>
                                                            <?php $__currentLoopData = $question->match_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li class="ui-state-default mb-2"
                                                                    data-id="<?php echo e($match->match_id); ?>">
                                                                    <div>
                                                                        <text><?php echo e($match->result->content); ?> </text>
                                                                        <span
                                                                            class="float-right"></span>
                                                                        <input type="hidden"
                                                                               <?php if(isset($story)): ?>
                                                                                   name="re[<?php echo e($question->id); ?>][<?php echo e($match->story_match_id); ?>]"
                                                                               <?php elseif(isset($lesson)): ?>
                                                                                   name="re[<?php echo e($question->id); ?>][<?php echo e($match->match_id); ?>]"
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

</div>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/test_preview/questions/match.blade.php ENDPATH**/ ?>