
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
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user_old/lesson/questions/media.blade.php ENDPATH**/ ?>