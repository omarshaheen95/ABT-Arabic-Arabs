$(document).ready(function () {
    // Ensure success dialog is hidden on page load
    $('#successDialog').hide();

    // Initialize green audio player
    if (typeof GreenAudioPlayer !== 'undefined') {
        GreenAudioPlayer.init({
            selector: '.audio-player',
            stopOthersOnPlay: true
        });
    }

    // Initialize Keyboard for all textareas
    if (typeof $.fn.keyboard !== 'undefined') {
        $('.keyboard').each(function() {
            var $textarea = $(this);
            var textareaId = $textarea.attr('id');
            var questionId = textareaId.replace('writing_', '');

            $textarea.keyboard({
                layout: "ms-Arabic (101)",
                usePreview: false,
                autoAccept: true,
                openOn: '',
                change: function(event, keyboard, el) {
                    // Update word count
                    var text = $(el).val().trim();
                    var wordCount = text === '' ? 0 : text.split(/\s+/).length;
                    $('#wordCount-' + questionId).text(wordCount);
                },
                initialized: function(event, keyboard, el) {
                    // Add keyboard toggle button
                    var toggleBtn = $('<button type="button" class="keyboard-toggle-btn">' +
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                        '<rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor" stroke-width="2"/>' +
                        '<line x1="6" y1="8" x2="8" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="10" y1="8" x2="12" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="14" y1="8" x2="16" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="18" y1="8" x2="18" y2="8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="6" y1="12" x2="8" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="10" y1="12" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="14" y1="12" x2="16" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="18" y1="12" x2="18" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '<line x1="8" y1="16" x2="16" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                        '</svg>' +
                        '</button>');

                    toggleBtn.insertAfter($(el)).click(function() {
                        $(el).getkeyboard().reveal();
                    });
                }
            }).addTyping({
                showTyping: true,
                delay: 250
            });
        });
    }

    // File upload display
    $('input[type="file"]').on('change', function() {
        var fileInput = $(this);
        var fileId = fileInput.attr('id');
        var fileName = '';

        if (fileInput[0].files && fileInput[0].files.length > 0) {
            fileName = fileInput[0].files[0].name;
        }

        // Extract question ID from fileUpload-{questionId}
        var questionId = fileId.replace('fileUpload-', '');
        $('#fileName-' + questionId).text(fileName);
    });

    // Question Navigation
    var totalQuestions = $('.question-item').length;

    // Generate question navigation list
    if (totalQuestions > 1) {
        for (var i = 1; i <= totalQuestions; i++) {
            $('#questionListLink').append(
                '<li>' +
                '<div class="questionListLinkItem ' + (i === 1 ? 'active' : '') + '" ' +
                'data-id="' + i + '" style="padding: 8px 12px;">' +
                i + '</div></li>'
            );
        }
    }

    // Next button
    $('#nextQuestion').click(function() {
        var currentQuestion = $('.question-item.active');
        var currentId = parseInt(currentQuestion.attr('id').replace('question-', ''));

        if (currentId < totalQuestions) {
            var nextId = currentId + 1;

            $('.question-item').removeClass('active').addClass('hidden');
            $('#question-' + nextId).addClass('active').removeClass('hidden');

            $('#questionListLink .questionListLinkItem').removeClass('active');
            $('#questionListLink [data-id=' + nextId + ']').addClass('active');

            if (nextId === totalQuestions) {
                $('.endExam').removeClass('hidden');
                $('#nextQuestion').addClass('hidden');
            }

            $('#previousQuestion').removeClass('hidden');

            // Scroll to top
            window.scrollTo(0, 0);
        }
    });

    // Previous button
    $('#previousQuestion').click(function() {
        var currentQuestion = $('.question-item.active');
        var currentId = parseInt(currentQuestion.attr('id').replace('question-', ''));

        if (currentId > 1) {
            var prevId = currentId - 1;

            $('.question-item').removeClass('active').addClass('hidden');
            $('#question-' + prevId).addClass('active').removeClass('hidden');

            $('#questionListLink .questionListLinkItem').removeClass('active');
            $('#questionListLink [data-id=' + prevId + ']').addClass('active');

            $('.endExam').addClass('hidden');
            $('#nextQuestion').removeClass('hidden');

            if (prevId === 1) {
                $('#previousQuestion').addClass('hidden');
            }

            // Scroll to top
            window.scrollTo(0, 0);
        }
    });

    // Question list item click
    $(document).on('click', '.questionListLinkItem', function() {
        var targetId = $(this).data('id');

        $('.question-item').removeClass('active').addClass('hidden');
        $('#question-' + targetId).addClass('active').removeClass('hidden');

        $('#questionListLink .questionListLinkItem').removeClass('active');
        $(this).addClass('active');

        if (targetId === totalQuestions) {
            $('.endExam').removeClass('hidden');
            $('#nextQuestion').addClass('hidden');
        } else {
            $('.endExam').addClass('hidden');
            $('#nextQuestion').removeClass('hidden');
        }

        if (targetId === 1) {
            $('#previousQuestion').addClass('hidden');
        } else {
            $('#previousQuestion').removeClass('hidden');
        }

        // Scroll to top
        window.scrollTo(0, 0);
    });

    // Word counter functionality
    $('.answer-textarea').on('input keyup change', function() {
        var textareaId = $(this).attr('id');
        var questionId = textareaId.replace('writing_', '');
        var text = $(this).val().trim();
        var wordCount = text === '' ? 0 : text.split(/\s+/).length;
        $('#wordCount-' + questionId).text(wordCount);
    });

    // Confirm save button - show confirmation dialog
    $('#confirmSaveBtn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Show custom confirmation dialog
        showConfirmDialog();
    });

    // Show confirmation dialog
    function showConfirmDialog() {
        // Update dialog text for confirmation
        $('#successText').text('هل أنت متأكد من حفظ الاختبار؟ سيتم إرسال إجاباتك إلى المدرس للتصحيح');
        $('#successTitle').text('تأكيد الحفظ');

        // Change button text to confirm
        $('#okBtn').text('احفظ الاختبار');

        // Show cancel button
        $('#cancelBtn').show();

        // Show dialog
        $('#successDialog').fadeIn(400);

        // Set flag to know this is confirmation mode
        $('#successDialog').data('mode', 'confirm');
    }

    // Show success dialog after submission
    function showSuccessDialog() {
        $('#successText').text('تم حفظ إجاباتك بنجاح');
        $('#successTitle').text('نجاح العملية !');
        $('#okBtn').text('متابعة');
        $('#cancelBtn').hide();
        $('#successDialog').fadeIn(400);
        $('#successDialog').data('mode', 'success');
    }

    // OK button handler
    $('#okBtn').on('click', function() {
        var mode = $('#successDialog').data('mode');

        if (mode === 'confirm') {
            // Hide dialog
            $('#successDialog').fadeOut(400);

            // Disable button to prevent double submission
            $('#confirmSaveBtn').attr('disabled', true);

            // Show loading dialog
            if (typeof showLoadingDialog === 'function') {
                showLoadingDialog();
            }

            // Submit form
            $('#writing_test_form').submit();
        } else {
            // Success mode - redirect
            $('#successDialog').fadeOut(400, function() {
                var redirectUrl = $('#writing_test_form').data('redirect-url');
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            });
        }
    });

    // Cancel button handler
    $('#cancelBtn').on('click', function() {
        $('#successDialog').fadeOut(400);
    });

    // Close dialog on overlay click
    $('.success-dialog-overlay').on('click', function(e) {
        if ($(e.target).hasClass('success-dialog-overlay')) {
            $(this).fadeOut(400);
        }
    });

    // Edit answer button handler
    $('.edit-answer-btn').on('click', function() {
        var questionId = $(this).data('question-id');

        // Hide existing answer container
        $('#existingAnswer-' + questionId).hide();

        // Show answer input container
        $('#answerContainer-' + questionId).removeClass('hidden').show();

        // Show submit button if hidden
        $('#confirmSaveBtn').removeClass('hidden').show();
    });
});
