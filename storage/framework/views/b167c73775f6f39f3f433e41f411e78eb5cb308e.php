<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/css/pages/writing-test.css')); ?>?v=9"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets_v1/js/keyboard/keyboard.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets_v1/js/keyboard/jquery-ui.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('user_assets/lib/green-audio-player/green-audio-player.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-name', 'lesson-writing-test'); ?>

<?php $__env->startSection('content'); ?>
    <div class="lesson-container">
        <!-- Lesson Header -->
        <header class="lesson-header">
            <div class="lesson-title-wrapper">
                <h1 class="lesson-title" id="lessonTitle"><?php echo e($lesson->name); ?></h1>
                <nav class="breadcrumbs" id="breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo e(route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])); ?>" class="breadcrumb-item"><?php echo e(t('Lessons')); ?></a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-item active"><?php echo e($lesson->name); ?></span>
                </nav>
            </div>
            <a href="<?php echo e(route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])); ?>" class="practice-btn">
                العودة إلى الدروس
            </a>
        </header>

        <!-- Feedback Section (Corrected Test) -->
        <?php if($isCorrected && isset($existingTest)): ?>
            <div class="feedback-container">
                <div class="feedback-card">
                    <!-- Score Badge -->
                    <div class="feedback-score-section">
                        <div class="score-badge-large">
                            <div class="score-icon-wrapper">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="11" stroke="#138944" stroke-width="2" fill="none"/>
                                    <path d="M9 12l2 2 4-4" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="score-content">
                                <span class="score-label">النتيجة النهائية</span>
                                <div class="score-display">
                                    <span class="score-number"><?php echo e($totalScore); ?></span>
                                    <span class="score-separator">/</span>
                                    <span class="score-max"><?php echo e($maxScore); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Feedback -->
                    <?php if(!empty($existingTest->feedback_message)): ?>
                        <div class="feedback-text-section">
                            <div class="feedback-text-header">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                    <path d="M8 10h8M8 14h4" stroke="#138944" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <h3 class="feedback-text-title">ملاحظات المعلم</h3>
                            </div>
                            <div class="feedback-text-content">
                                <?php echo e($existingTest->feedback_message); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Audio Feedback -->
                    <?php if(!empty($existingTest->feedback_record)): ?>
                        <div class="feedback-audio-section">
                            <div class="feedback-audio-header">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                                    <path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3M8 22h8" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h3 class="feedback-audio-title">تسجيل صوتي من المعلم</h3>
                            </div>
                            <div class="feedback-audio-player-wrapper" dir="ltr">
                                <div class="green-audio-player-feedback">
                                    <audio preload="metadata">
                                        <source src="<?php echo e(asset($existingTest->feedback_record)); ?>" type="audio/mpeg">
                                    </audio>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Questions Container -->
        <div class="lesson-tabs-wrapper">
            <div class="lesson-content show" id="lessonContent">
                <form action="<?php echo e(route('lesson.save-writing-test', $lesson->id)); ?>" enctype="multipart/form-data" id="writing_test_form" method="post" data-redirect-url="<?php echo e(route('lesson.lessons-by-level', [$lesson->grade_id, $lesson->lesson_type])); ?>">
                    <?php echo e(csrf_field()); ?>

                    <input type="hidden" name="start_at" value="<?php echo e(\Carbon\Carbon::now()); ?>">

                    <?php
                        $counter = 1;
                    ?>

                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div id="question-<?php echo e($counter); ?>" class="section-content question-item <?php if($loop->first): ?> active <?php else: ?> hidden <?php endif; ?>" style="background: #ffffff;border-radius: 15px;padding: 15px; margin-bottom: 20px;">
                            <div class="read-section">
                                <div class="section-header" style="margin-bottom: 25px;" dir="rtl">
                                    <div class="section-text">
                                        <p class="section-title"><?php echo e($question->content); ?></p>
                                    </div>
                                </div>

                                <div class="read-text-container">
                                    <!-- Lesson Content (Instructions) -->
                                    <?php if(!is_null($lesson->content)): ?>
                                        <div class="lesson-content-box" style="background: #f8f9fa; border-radius: 10px; padding: 20px; margin-bottom: 25px; border-left: 4px solid #5984E5;">
                                            <div style="margin-bottom: 10px;">
                                                <strong style="color: #1E4396; font-size: 16px;"><?php echo e(t('Instructions')); ?></strong>
                                            </div>
                                            <div style="color: #333; line-height: 1.8;">
                                                <?php echo $lesson->content; ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Question Media -->
                                    <?php if($question->getFirstMediaUrl('imageQuestion')): ?>
                                        <div class="question-media-container" style="margin-bottom: 30px; text-align: center;">
                                            <?php if(\Illuminate\Support\Str::contains($question->getFirstMediaUrl('imageQuestion'), '.mp3')): ?>
                                                <div class="audio-player-wrapper" style="max-width: 500px; margin: 0 auto;">
                                                    <div class="audio-player">
                                                        <audio preload="metadata">
                                                            <source src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>" type="audio/mpeg">
                                                        </audio>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="full-width">
                                                    <img src="<?php echo e(asset($question->getFirstMediaUrl('imageQuestion'))); ?>" style="max-width: 450px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" alt="Question Image">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                        $hasExistingAnswer = isset($existingResults[$question->id]);
                                    ?>

                                    <!-- Existing Answer Display -->
                                    <?php if($hasExistingAnswer): ?>
                                        <div class="existing-answer-container" id="existingAnswer-<?php echo e($question->id); ?>">
                                            <div class="existing-answer-header">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="#138944"/>
                                                </svg>
                                                <span class="existing-answer-text">
                                                    <?php if($isCorrected): ?>
                                                        تم التصحيح
                                                    <?php else: ?>
                                                        تم حفظ إجابتك بنجاح
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <?php if(!empty($existingResults[$question->id]['result'])): ?>
                                                <div class="word-count">عدد الكلمات: <span class="word-count-value"><?php echo e(str_word_count($existingResults[$question->id]['result'])); ?></span></div>
                                                <div style="position: relative;">
                                                    <textarea class="answer-textarea existing-answer-textarea" readonly><?php echo e($existingResults[$question->id]['result']); ?></textarea>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(!empty($existingResults[$question->id]['attachment'])): ?>
                                                <div class="existing-attachment-link">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5-5 5 5M12 5v12" stroke="#138944" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <a href="<?php echo e(asset($existingResults[$question->id]['attachment'])); ?>" target="_blank">عرض الملف المرفق</a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if(!$isCorrected): ?>
                                                <div class="edit-answer-section">
                                                    <button type="button" class="edit-answer-btn" data-question-id="<?php echo e($question->id); ?>">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        تعديل الإجابة
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Writing Answer Input (Always present, hidden if answer exists) -->
                                    <div class="write-answer-container <?php if($hasExistingAnswer): ?> hidden <?php endif; ?>" id="answerContainer-<?php echo e($question->id); ?>">
                                        <div class="word-count">عدد الكلمات: <span class="word-count-value" id="wordCount-<?php echo e($question->id); ?>"><?php if($hasExistingAnswer && !empty($existingResults[$question->id]['result'])): ?><?php echo e(str_word_count($existingResults[$question->id]['result'])); ?><?php else: ?> 0 <?php endif; ?></span></div>
                                        <div style="position: relative;">
                                            <textarea
                                                class="answer-textarea keyboard"
                                                id="writing_<?php echo e($question->id); ?>"
                                                name="writing_answer[<?php echo e($question->id); ?>]"
                                                placeholder="اكتب إجابتك هنا"
                                                onpaste="return false;"
                                                dir="rtl"><?php if($hasExistingAnswer && !empty($existingResults[$question->id]['result'])): ?><?php echo e($existingResults[$question->id]['result']); ?><?php endif; ?></textarea>
                                        </div>
                                        <div class="upload-section">
                                            <label class="upload-btn" for="fileUpload-<?php echo e($question->id); ?>">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                إرفاق ملف
                                            </label>
                                            <input type="file" id="fileUpload-<?php echo e($question->id); ?>" name="writing_attachment[<?php echo e($question->id); ?>]" style="display: none;" accept="image/*,.pdf,.doc,.docx">
                                            <span class="file-name" id="fileName-<?php echo e($question->id); ?>"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                            $counter++;
                        ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Navigation Buttons -->
                    <?php if(!$isCorrected): ?>
                        <?php if(count($questions) > 1): ?>
                            <div class="flex-center navigation-buttons">
                                <button type="button" class="hidden" id="previousQuestion">
                                    <span><?php echo e(t('Previous')); ?></span>
                                </button>
                                <button type="button" class="<?php if(count($questions) == 1): ?> hidden <?php endif; ?>" id="nextQuestion">
                                    <span><?php echo e(t('Next')); ?></span>
                                </button>
                                <button type="button" class="<?php if(count($questions) == 1): ?> <?php else: ?> hidden <?php endif; ?> endExam" id="confirmSaveBtn">
                                    <span>حفظ و إنهاء</span>
                                </button>
                            </div>

                            <!-- Question Navigation List -->
                            <div class="table-footer">
                                <ul class="nav-list" id="questionListLink"></ul>
                            </div>
                        <?php else: ?>
                            <div style="display: flex;justify-content: center;padding-bottom: 30px">
                                <button type="button" class="btn-save endExam <?php if($hasExistingAnswer): ?> hidden <?php endif; ?>" id="confirmSaveBtn">
                                    <span>حفظ و إنهاء</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Question Navigation List for viewing -->
                        <?php if(count($questions) > 1): ?>
                            <div class="flex-center navigation-buttons">
                                <button type="button" class="hidden" id="previousQuestion">
                                    <span><?php echo e(t('Previous')); ?></span>
                                </button>
                                <button type="button" id="nextQuestion">
                                    <span><?php echo e(t('Next')); ?></span>
                                </button>
                            </div>
                            <div class="table-footer">
                                <ul class="nav-list" id="questionListLink"></ul>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Loading Dialog -->
    <?php echo $__env->make('user.general.components.loading_dialog', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Success Dialog -->
    <div class="success-dialog-overlay" id="successDialog" style="display: none;">
        <div class="success-dialog">
            <div class="success-content">
                <div class="success-icon">
                    <svg viewBox="0 0 80 80">
                        <circle class="success-circle" cx="40" cy="40" r="35" fill="none" stroke-width="4"></circle>
                        <path class="success-check" d="M25 40 L35 50 L55 30" fill="none" stroke-width="4"></path>
                    </svg>
                </div>
                <h3 class="success-title" id="successTitle">نجاح العملية !</h3>
                <p class="success-text" id="successText">تم حفظ إجاباتك بنجاح</p>
                <div class="success-buttons">
                    <button class="cancel-btn" id="cancelBtn" style="display: none;">إلغاء</button>
                    <button class="ok-btn" id="okBtn">متابعة</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user_assets/lib/green-audio-player/green-audio-player.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets_v1/js/keyboard/jquery.keyboard.js')); ?>" charset="UTF-8"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets_v1/js/keyboard/jquery.keyboard.extension-typing.js')); ?>" charset="UTF-8"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets_v1/js/keyboard/keyboard-layouts-microsoft.min.js')); ?>" charset="UTF-8"></script>
    <script type="text/javascript" src="<?php echo e(asset('user_assets/js/pages/writing-test.js')); ?>?v=7"></script>
    <script>
        // Initialize green audio player for feedback
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.green-audio-player-feedback')) {
                GreenAudioPlayer.init({
                    selector: '.green-audio-player-feedback',
                    stopOthersOnPlay: true
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('user.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/user/lessons/pages/writing_test.blade.php ENDPATH**/ ?>