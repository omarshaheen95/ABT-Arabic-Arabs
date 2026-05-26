<div id="<?php echo e($counter); ?>"
     class="exercise-box <?php if($loop->first): ?> active <?php endif; ?> question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> <?php echo e($counter); ?> : </span>
        <span class="title">   أكد إذا كانت هذه الجمل صواب أم خطأ - Confirm whether these sentences
                                                are true or false  </span>
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

                <div class="true-false-question">
                    <div class="answer-box">
                        <input
                            type="radio" question-grop-name="q<?php echo e($counter); ?>"
                            name="tf[<?php echo e($question->id); ?>]" value="1"
                            id="true-<?php echo e($question->id); ?>" class="d-none"
                            <?php echo e(isset($question->true_false_results) && optional($question->true_false_results->first())->result==1?'checked':''); ?>>
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
                                        <?php if(isset($question->true_false_results) && optional($question->true_false_results->first())->result!=1 && $question->trueFalse->result==1|| !isset($question->true_false_results) &&
                                        $question->trueFalse->result==1): ?> style="fill: #ffcc03;stroke: #ffcc03" <?php else: ?> fill="#2ecc71" <?php endif; ?>/>
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
                            id="false-<?php echo e($question->id); ?>" class="d-none"
                            <?php echo e(isset($question->true_false_results) && optional($question->true_false_results->first())->result==0?'checked':''); ?>>
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
                                        <?php if(isset($question->true_false_results) && optional($question->true_false_results->first())->result!=0 && $question->trueFalse->result==0 || !isset($question->true_false_results) &&
                                        $question->trueFalse->result==0): ?> style="fill: #ffcc03;stroke: #ffcc03" <?php else: ?> fill="#dc3545" <?php endif; ?>/>

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
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/general/user/test_preview/questions/true_false.blade.php ENDPATH**/ ?>