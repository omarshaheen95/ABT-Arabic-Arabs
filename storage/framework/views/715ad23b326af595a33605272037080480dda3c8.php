<div id="<?php echo e($counter); ?>" class="exercise-box <?php if($loop->first): ?> active <?php endif; ?>  question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> <?php echo e($counter); ?> : </span>
        <span
            class="title"> اختر الإجابة الصحيحة - Choose the correct answer</span>
    </div>
    <div class="exercise-box-body">
        <div class="exercise-question">
            <div class="exercise-question-data border-0">
                <div class="info">
                    <?php echo e($question->content); ?>

                </div>
            </div>
            <div class="exercise-question-answer text-center my-4">

                <?php if(!is_null($question->attachment)): ?>

                    <div class="row justify-content-center py-3">
                        <div class="col-lg-6 col-md-8">
                            <?php if(\Illuminate\Support\Str::contains($question->attachment, '.mp3')): ?>
                                <div class="recorder-player" id="voice_audio_2">
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
                                    <img src="<?php echo e(asset($question->attachment)); ?>"
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
                                       class="co_q d-none"
                                    <?php echo e(isset($question->option_results) && optional($question->option_results->first())->option_id==$option->id?'checked':''); ?>>

                                <label for="option-<?php echo e($option->id); ?>"
                                       class="option option-true"
                                       <?php if(isset($question->option_results) && optional($question->option_results->first())->option_id!=$option->id && $question->options->where('result',1)->first()->id==$option->id): ?> style="background-color: #00b300" <?php endif; ?>
                                >
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
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/test_preview/questions/options.blade.php ENDPATH**/ ?>