$(function() {
    $('.keyboard')
        .keyboard({
            layout: "ms-Arabic (101)",
            usePreview: false,
            autoAccept: true,
            openOn: '',  // Prevent keyboard from opening on focus
            change: function(event, keyboard, el) {
                // Method to perform on keyup
                saveCache($(el).attr('id'));
                onSpacePress($(el).val(), $(el).attr('id'));
            },
            initialized: function(event, keyboard, el) {
                // Append the icon to the keyboard's input
                var $icon = $('<i class="fas fa-keyboard keyboard-open-icon" style="cursor: pointer; font-size: 2em; margin-left: 10px;"></i>')
                    .insertAfter($(el))
                    .click(function() {
                        $(el).getkeyboard().reveal();
                    });
            }

        })
        // activate the typing extension
        .addTyping({
            showTyping: true,
            delay: 250
        });

});
// Custom method to count words
function onSpacePress(value, elementId) {
    var wordCount = countWords(value);
    console.log('Word count for element with ID', elementId, ':', wordCount);
    // You can return the word count or perform additional actions here
    var textareaDiv = $('#' + elementId).closest('.textarea');
    textareaDiv.find('.word-count-value').text(wordCount);
    return wordCount;
}

// Function to count words in a string
function countWords(str) {
    var trimmedStr = str.trim();
    if (trimmedStr === "") {
        return 0;
    }
    return trimmedStr.split(/\s+/).length;
}

//check textarea words count for all textareas
$(document).ready(function () {
    $('.textarea textarea').each(function () {
        onSpacePress($(this).val(), $(this).attr('id'));
    });
});
