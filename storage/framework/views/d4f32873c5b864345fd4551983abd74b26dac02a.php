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
                                                <?php $__currentLoopData = $question->matches->shuffle(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/story/questions/matching.blade.php ENDPATH**/ ?>