/**
 * Training Quiz Page - Interactive Controls Only
 * All content is now static in HTML
 */

// Quiz configuration
// const QUIZ_CONFIG = QUIZ_DATA;
const QUIZ_CONFIG = QUIZ_DATA;
// Training quiz state
let currentQuestionIndex = 0;
let userAnswers = {};
let timerInterval = null;
let remainingTime = 0; // in seconds
let selectedItem = null;

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    remainingTime = QUIZ_CONFIG.duration * 60;

    // // Set start time
    // const startTime = new Date().toISOString().slice(0, 19).replace('T', ' ');
    // document.getElementById('startTimeInput').value = startTime;

    startTimer();
    setupQuestionNavigation();
    setupCurrentQuestionControls();
    updateProgressBar();
    setupFormSubmission();
});

/**
 * Start the quiz timer
 */
function startTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
    }

    timerInterval = setInterval(() => {
        remainingTime--;
        updateTimerDisplay();

        if (remainingTime <= 0) {
            clearInterval(timerInterval);
            handleTimeUp();
        }
    }, 1000);
}

/**
 * Update timer display
 */
function updateTimerDisplay() {
    const timerElement = document.getElementById('quizTimer');
    if (timerElement) {
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        const timeDisplay = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        const timerText = timerElement.querySelector('.timer-text');
        if (timerText) {
            timerText.textContent = timeDisplay;

            if (remainingTime < 60) {
                timerElement.classList.add('timer-warning');
            } else {
                timerElement.classList.remove('timer-warning');
            }
        }
    }
}

/**
 * Handle when time is up
 */
function handleTimeUp() {
    alert('انتهى الوقت! سيتم تقديم الاختبار الآن.\nTime is up! The quiz will be submitted now.');
    submitTestForm();
}

/**
 * Stop the timer
 */
function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

/**
 * Setup question navigation (Previous/Next buttons)
 */
function setupQuestionNavigation() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                switchToQuestion(QUIZ_CONFIG.questions[currentQuestionIndex].id,currentQuestionIndex);
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < QUIZ_CONFIG.totalQuestions - 1) {
                currentQuestionIndex++;
                switchToQuestion(QUIZ_CONFIG.questions[currentQuestionIndex].id,currentQuestionIndex);
            }
        });
    }
}

/**
 * Switch to a specific question
 */
function switchToQuestion(id,index) {
    // Find currently visible question
    const currentQuestion = document.querySelector('.question-container[style*="display: block"]');

    // Fade out current question if exists
    if (currentQuestion) {
        currentQuestion.classList.add('fade-out');

        // Wait for fade out animation to complete
        setTimeout(() => {
            // Hide all questions and remove animation classes
            document.querySelectorAll('.question-container').forEach(q => {
                q.style.display = 'none';
                q.classList.remove('fade-out', 'fade-in');
            });

            // Show selected question with fade in animation
            const questionId = `question${id}`;
            const questionEl = document.getElementById(questionId);
            if (questionEl) {
                questionEl.style.display = 'block';
                questionEl.classList.add('fade-in');
            }
            console.log('id',questionId);

            // Update navigation buttons
            updateNavigationButtons(index);

            // Update progress bar
            updateProgressBar();

            // Setup controls for current question
            setupCurrentQuestionControls();
        }, 300);
    } else {
        // First load - no animation needed
        document.querySelectorAll('.question-container').forEach(q => {
            q.style.display = 'none';
            q.classList.remove('fade-out', 'fade-in');
        });

        const questionId = `question${id}`;
        const questionEl = document.getElementById(questionId);
        if (questionEl) {
            questionEl.style.display = 'block';
            questionEl.classList.add('fade-in');
        }
        console.log('id',questionId);

        // Update navigation buttons
        updateNavigationButtons(index);

        // Update progress bar
        updateProgressBar();

        // Setup controls for current question
        setupCurrentQuestionControls();
    }
}

/**
 * Update navigation buttons state
 */
function updateNavigationButtons(index) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const isLastQuestion = index === QUIZ_CONFIG.totalQuestions - 1;

    if (prevBtn) {
        prevBtn.disabled = index === 0;
    }

    if (nextBtn) {
        if (isLastQuestion) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'flex';
            nextBtn.textContent = 'Next';
        }
    }

    if (submitBtn) {
        submitBtn.style.display = isLastQuestion ? 'flex' : 'none';
    }
}

/**
 * Update progress bar
 */
function updateProgressBar() {
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        const progress = ((currentQuestionIndex + 1) / QUIZ_CONFIG.totalQuestions) * 100;
        progressBar.style.width = `${progress}%`;
    }
}
/**
 * Setup controls for current question
 */
function setupCurrentQuestionControls() {
  const question = QUIZ_CONFIG.questions[currentQuestionIndex];
    setupAudioPlayer();

  switch (question.type) {
    case 'true-false':
      setupTrueFalseControls(question);
      break;
    case 'multiple-choice':
      setupMultipleChoiceControls(question);
      break;
    case 'matching':
      setupDragDropPlaceControls(question);
      break;
    case 'sorting':
      setupDragDropOrderControls(question);
      break;
  }
}

/**
 * Setup True/False controls with instant feedback
 */
function setupTrueFalseControls(question) {
  const buttons = document.querySelectorAll(`button[data-question-id="${question.id}"].true-false-btn`);

  buttons.forEach(button => {
    const newButton = button.cloneNode(true);
    button.parentNode.replaceChild(newButton, button);

    newButton.addEventListener('click', () => {
      const answer = newButton.getAttribute('data-answer') === 'true';
      userAnswers[question.id] = answer;

      // Re-query buttons after DOM changes to get fresh NodeList
      const currentButtons = document.querySelectorAll(`button[data-question-id="${question.id}"].true-false-btn`);

      // Update button states
      currentButtons.forEach(btn => {
        const btnAnswer = btn.getAttribute('data-answer') === 'true';
        if (btnAnswer === answer) {
          btn.classList.add('selected');
        }else {
            btn.classList.remove('selected')
        }
      });

    });
  });
}

/**
 * Setup Multiple Choice controls with instant feedback
 */
function setupMultipleChoiceControls(question) {
  const options = document.querySelectorAll(`.choice-option[data-question-id="${question.id}"]`);

  options.forEach(option => {
    const newOption = option.cloneNode(true);
    option.parentNode.replaceChild(newOption, option);

    newOption.addEventListener('click', () => {
      const choiceId = newOption.getAttribute('data-choice-id');
      userAnswers[question.id] = choiceId;

        // Re-query options after DOM changes to get fresh NodeList
        const currentOptions = document.querySelectorAll(`.choice-option[data-question-id="${question.id}"]`);

        // Update option states
        currentOptions.forEach(opt => {
            if (opt.getAttribute('data-choice-id') === choiceId) {
                opt.classList.add('selected');
            }else {
                opt.classList.remove('selected');
            }
        });
    });
  });
}

/**
 * Setup Audio Player with animation
 */
function setupAudioPlayer() {
  const playButtons = document.querySelectorAll('.audio-play-button');
  let currentAudio = null;
  let currentButton = null;

  playButtons.forEach(button => {
    const newButton = button.cloneNode(true);
    button.parentNode.replaceChild(newButton, button);

    newButton.addEventListener('click', () => {
      const audioUrl = newButton.getAttribute('data-audio-url');

      // If clicking the same button while playing, pause it
      if (currentAudio && currentButton === newButton && !currentAudio.paused) {
        currentAudio.pause();
        newButton.classList.remove('playing');
        return;
      }

      // Stop any currently playing audio
      if (currentAudio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        if (currentButton) {
          currentButton.classList.remove('playing');
        }
      }

      // Create and play new audio
      currentAudio = new Audio(audioUrl);
      currentButton = newButton;

      // Add playing animation
      newButton.classList.add('playing');

      // Play the audio
      currentAudio.play().catch(err => {
        console.error('Audio play failed:', err);
        newButton.classList.remove('playing');
      });

      // Remove animation when audio ends
      currentAudio.addEventListener('ended', () => {
        newButton.classList.remove('playing');
        currentAudio = null;
        currentButton = null;
      });

      // Handle audio errors
      currentAudio.addEventListener('error', (e) => {
        console.error('Audio error:', e);
        newButton.classList.remove('playing');
        currentAudio = null;
        currentButton = null;
      });
    });
  });
}

/**
 * Setup Drag and Drop for "Right Place" questions
 */
function setupDragDropPlaceControls(question) {
  const dragItems = document.querySelectorAll(`#question${question.id} .drag-item`);
  const dropZones = document.querySelectorAll(`#question${question.id} .drop-zone`);
  const dragItemsContainer = document.querySelector(`#question${question.id} .drag-items-container`);

  // Max items per zone (default: 1, can be overridden via data-max-items attribute)
  const getMaxItems = (zone) => {
    return parseInt(zone.getAttribute('data-max-items')) || 1;
  };

  // Find first empty zone
  const findFirstEmptyZone = () => {
    for (let zone of dropZones) {
      const zoneItems = zone.querySelector('.drop-zone-items');
      const maxItems = getMaxItems(zone);
      const currentItems = zoneItems.querySelectorAll('.drag-item').length;

      if (currentItems < maxItems) {
        return zone;
      }
    }
    return null;
  };

  let draggedItem = null;

  // Setup both click and drag for each item
  dragItems.forEach(item => {
    const newItem = item.cloneNode(true);
    item.parentNode.replaceChild(newItem, item);

    // Click interaction
    newItem.addEventListener('click', () => {
      const parentContainer = newItem.closest('.drag-items-container');
      const parentZone = newItem.closest('.drop-zone');

      if (parentZone) {
        // Item is in a zone - return it to container
        dragItemsContainer.appendChild(newItem);
        updateDragDropPlaceAnswer(question);
      } else if (parentContainer) {
        // Item is in container - place in first empty zone
        const emptyZone = findFirstEmptyZone();
        if (emptyZone) {
          const zoneItems = emptyZone.querySelector('.drop-zone-items');
          zoneItems.appendChild(newItem);
          updateDragDropPlaceAnswer(question);
        }
      }
    });

    // Drag interaction (always enabled)
    newItem.setAttribute('draggable', 'true');

    newItem.addEventListener('dragstart', (e) => {
      draggedItem = newItem;
      newItem.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
    });

    newItem.addEventListener('dragend', () => {
      newItem.classList.remove('dragging');
      draggedItem = null;
    });
  });

  // Setup drop zones for drag and drop
  if (QUIZ_CONFIG.enableDragAndDrop) {

    dropZones.forEach(zone => {
      zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        const zoneItems = zone.querySelector('.drop-zone-items');
        const maxItems = getMaxItems(zone);
        const currentItems = zoneItems.querySelectorAll('.drag-item').length;

        if (currentItems < maxItems) {
          zone.classList.add('drag-over');
        } else {
          zone.classList.add('drag-over-full');
        }
      });

      zone.addEventListener('dragleave', () => {
        zone.classList.remove('drag-over', 'drag-over-full');
      });

      zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('drag-over', 'drag-over-full');

        if (draggedItem) {
          const zoneItems = zone.querySelector('.drop-zone-items');
          const maxItems = getMaxItems(zone);
          const currentItems = zoneItems.querySelectorAll('.drag-item');

          if (currentItems.length < maxItems) {
            // Zone has space - just add the item
            zoneItems.appendChild(draggedItem);
            updateDragDropPlaceAnswer(question);
          } else if (currentItems.length >= maxItems) {
            // Zone is full - swap: move existing items back to container, add new item
            currentItems.forEach(existingItem => {
              dragItemsContainer.appendChild(existingItem);
            });
            zoneItems.appendChild(draggedItem);
            updateDragDropPlaceAnswer(question);
          }
        }
      });
    });

    if (typeof dragItemsContainer !=='undefined' && dragItemsContainer){
        dragItemsContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragItemsContainer.classList.add('drag-over');
        });

        dragItemsContainer.addEventListener('dragleave', () => {
            dragItemsContainer.classList.remove('drag-over');
        });

        dragItemsContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            dragItemsContainer.classList.remove('drag-over');
            if (draggedItem) {
                dragItemsContainer.appendChild(draggedItem);
                updateDragDropPlaceAnswer(question);
            }
        });
    }
  }
}

/**
 * Update drag and drop place answer and check correctness
 */
function updateDragDropPlaceAnswer(question) {
  const answer = {};
  const dropZones = document.querySelectorAll(`#question${question.id} .drop-zone`);

  dropZones.forEach(zone => {
    const zoneId = zone.getAttribute('data-zone-id');
    const items = zone.querySelectorAll('.drag-item');
    answer[zoneId] = Array.from(items).map(item => item.getAttribute('data-item-id'));
  });

  userAnswers[question.id] = answer;
    console.log('question',question)

}

/**
 * Setup Drag and Drop for "Correct Order" questions
 */
function setupDragDropOrderControls(question) {
  const dragItems = document.querySelectorAll(`#question${question.id} .drag-item`);
  const orderDropZone = document.getElementById(`orderDropZone_${question.id}`);
  const dragItemsContainer = document.querySelector(`#question${question.id} .drag-items-container`);

  let draggedItem = null;
  let placeholder = null;

  // Create placeholder element for visual feedback
  const createPlaceholder = () => {
    if (!placeholder) {
      placeholder = document.createElement('li');
      placeholder.className = 'drag-placeholder';
      placeholder.style.cssText = 'min-width: 80px; height: 72px; background: rgba(93, 135, 232, 0.15); border: 3px dashed #5d87e8; border-radius: 18px; margin: 0 4px; list-style: none; flex: 1; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;';
    }
    return placeholder;
  };

  // Get element after cursor position (for horizontal layout)
  const getDragAfterElement = (container, x) => {
    const draggableElements = [...container.querySelectorAll('.drag-item:not(.dragging)')];

    return draggableElements.reduce((closest, child) => {
      const box = child.getBoundingClientRect();
      const offset = x - box.left - box.width / 2;

      if (offset < 0 && offset > closest.offset) {
        return { offset: offset, element: child };
      } else {
        return closest;
      }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
  };

  // Get insert direction from data attribute (default: 'end' for LTR, can be 'start' for RTL)
  const getInsertDirection = () => {
    return orderDropZone.getAttribute('data-insert-direction') || 'end';
  };

  // Setup both click and drag for each item
  dragItems.forEach(item => {
    const newItem = item.cloneNode(true);
    item.parentNode.replaceChild(newItem, item);

    // Click interaction
    newItem.addEventListener('click', () => {
      const parentContainer = newItem.closest('.drag-items-container');
      const isInDropZone = orderDropZone && orderDropZone.contains(newItem);

      if (isInDropZone) {
        // Item is in drop zone - return it to container
        dragItemsContainer.appendChild(newItem);
        if (orderDropZone.querySelectorAll('.drag-item').length === 0) {
          orderDropZone.classList.remove('has-items');
        }
        updateDragDropOrderAnswer(question);
      } else if (parentContainer) {
        // Item is in container - place in drop zone
        const insertDirection = getInsertDirection();

        if (insertDirection === 'start') {
          // Insert at the beginning (for RTL/Arabic)
          const firstItem = orderDropZone.querySelector('.drag-item');
          if (firstItem) {
            orderDropZone.insertBefore(newItem, firstItem);
          } else {
            orderDropZone.appendChild(newItem);
          }
        } else {
          // Insert at the end (default - for LTR/English)
          orderDropZone.appendChild(newItem);
        }

        orderDropZone.classList.add('has-items');
        updateDragDropOrderAnswer(question);
      }
    });

    // Drag interaction (always enabled)
    newItem.setAttribute('draggable', 'true');

    newItem.addEventListener('dragstart', (e) => {
      draggedItem = newItem;
      newItem.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
        // Add class to containers to minimize other items
        if (orderDropZone) {
            orderDropZone.classList.add('drag-active');
        }
        if (dragItemsContainer) {
            dragItemsContainer.classList.add('drag-active');
        }
    });

    newItem.addEventListener('dragend', () => {
      newItem.classList.remove('dragging');
      draggedItem = null;
        // Remove drag-active class from containers
        if (orderDropZone) {
            orderDropZone.classList.remove('drag-active');
        }
        if (dragItemsContainer) {
            dragItemsContainer.classList.remove('drag-active');
        }
      // Remove placeholder if exists
      if (placeholder && placeholder.parentNode) {
        placeholder.parentNode.removeChild(placeholder);
      }
    });

    // Allow dragging over other items in drop zone to reorder
    newItem.addEventListener('dragover', (e) => {
      if (draggedItem && draggedItem !== newItem && orderDropZone.contains(newItem)) {
        e.preventDefault();
        const afterElement = getDragAfterElement(orderDropZone, e.clientX);
        if (afterElement == null) {
          orderDropZone.appendChild(createPlaceholder());
        } else {
          orderDropZone.insertBefore(createPlaceholder(), afterElement);
        }
      }
    });

    // Drop on another item - swap it back to container
    newItem.addEventListener('drop', (e) => {
      if (draggedItem && draggedItem !== newItem && orderDropZone.contains(newItem)) {
        e.preventDefault();
        e.stopPropagation();

        // Move the item being dropped on back to container
        dragItemsContainer.appendChild(newItem);

        // Place dragged item where the old item was
        if (placeholder && placeholder.parentNode) {
          placeholder.parentNode.replaceChild(draggedItem, placeholder);
        }

        updateDragDropOrderAnswer(question);
      }
    });
  });

  // Setup drop zones for drag and drop
  if (QUIZ_CONFIG.enableDragAndDrop && orderDropZone && dragItemsContainer) {

    orderDropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      orderDropZone.classList.add('drag-over');

      if (draggedItem) {
        const afterElement = getDragAfterElement(orderDropZone, e.clientX);
        if (afterElement == null) {
          orderDropZone.appendChild(createPlaceholder());
        } else {
          orderDropZone.insertBefore(createPlaceholder(), afterElement);
        }
      }
    });

    orderDropZone.addEventListener('dragleave', (e) => {
      // Only remove if actually leaving the drop zone (not hovering over child)
      if (!orderDropZone.contains(e.relatedTarget)) {
        orderDropZone.classList.remove('drag-over');
        if (placeholder && placeholder.parentNode) {
          placeholder.parentNode.removeChild(placeholder);
        }
      }
    });

    orderDropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      orderDropZone.classList.remove('drag-over');

      if (draggedItem) {
        // Replace placeholder with actual item
        if (placeholder && placeholder.parentNode) {
          placeholder.parentNode.replaceChild(draggedItem, placeholder);
        } else {
          orderDropZone.appendChild(draggedItem);
        }

        orderDropZone.classList.add('has-items');
        updateDragDropOrderAnswer(question);
      }
    });

    dragItemsContainer.addEventListener('dragover', (e) => {
      e.preventDefault();
      dragItemsContainer.classList.add('drag-over');
    });

    dragItemsContainer.addEventListener('dragleave', () => {
      dragItemsContainer.classList.remove('drag-over');
    });

    dragItemsContainer.addEventListener('drop', (e) => {
      e.preventDefault();
      dragItemsContainer.classList.remove('drag-over');
      if (draggedItem) {
        dragItemsContainer.appendChild(draggedItem);
        if (orderDropZone.querySelectorAll('.drag-item').length === 0) {
          orderDropZone.classList.remove('has-items');
        }
        updateDragDropOrderAnswer(question);
      }
    });
  }
}

/**
 * Update drag and drop order answer and check correctness
 */
function updateDragDropOrderAnswer(question) {
  const orderDropZone = document.getElementById(`orderDropZone_${question.id}`);
  const insertDirection = orderDropZone.dataset.insertDirection??'end';
  const items = orderDropZone.querySelectorAll(`#question${question.id} .drag-item`);
  const answer = Array.from(items).map(item => item.getAttribute('data-item-id'));

  userAnswers[question.id] = answer;

  if (answer.length > 0) {
    orderDropZone.classList.add('has-items');
  } else {
    orderDropZone.classList.remove('has-items');
  }

}

/**
 * Show completion dialog with navigation options
 */
function showCompletionDialog() {
    const dialog = document.getElementById('completionDialog');
    const goToTestBtn = document.getElementById('goToTestBtn');
    const goToLessonsBtn = document.getElementById('goToLessonsBtn');

    // Show dialog
    dialog.style.display = 'flex';

    // Prevent closing dialog by clicking outside
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
            e.preventDefault();
            e.stopPropagation();
        }
    });

    // Prevent closing with Escape key
    document.addEventListener('keydown', function preventEscape(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
        }
    });

    // Go to Test button
    goToTestBtn.addEventListener('click', () => {
        window.location.href = GO_TO_TEST_URL;
    });

    // Go to Lessons button
    goToLessonsBtn.addEventListener('click', () => {
        window.location.href = GO_TO_LESSONS_URL;
    });
}

/**
 * Setup form submission handler
 */
function setupFormSubmission() {
    const form = document.getElementById('testForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmDialog = document.getElementById('submitConfirmDialog');
    const confirmBtn = document.getElementById('confirmSubmitBtn');
    const cancelBtn = document.getElementById('cancelSubmitBtn');

    // Prevent default form submission - always go through submitTestForm()
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
        });
    }

    // Handle submit button click - show confirmation dialog
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showSubmitConfirmDialog();
        });
    }

    // Handle confirm button
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            hideSubmitConfirmDialog();
            submitTestForm();
        });
    }

    // Handle cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            hideSubmitConfirmDialog();
        });
    }

    // Prevent closing dialog by clicking outside
    if (confirmDialog) {
        confirmDialog.addEventListener('click', function(e) {
            if (e.target === confirmDialog) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }
}

/**
 * Show submit confirmation dialog
 */
function showSubmitConfirmDialog() {
    const dialog = document.getElementById('submitConfirmDialog');
    if (dialog) {
        dialog.style.display = 'flex';
    }
}

/**
 * Hide submit confirmation dialog
 */
function hideSubmitConfirmDialog() {
    const dialog = document.getElementById('submitConfirmDialog');
    if (dialog) {
        dialog.style.display = 'none';
    }
}

/**
 * Submit the test form
 */
function submitTestForm() {
    stopTimer();
    populateFormData();
    showLoadingDialog();

    //Submit the form
    const form = document.getElementById('testForm');
    if (form) {
        form.submit();
    }
}

/**
 * Populate form data with all answers
 */
function populateFormData() {
    const form = document.getElementById('testForm');

    // Get all questions from QUIZ_DATA
    QUIZ_CONFIG.questions.forEach(question => {
        switch (question.type) {
            case 'true-false':
                populateTrueFalseAnswer(question, form);
                break;
            case 'multiple-choice':
                populateMultipleChoiceAnswer(question, form);
                break;
            case 'matching':
                populateMatchingAnswer(question, form);
                break;
            case 'sorting':
                populateSortingAnswer(question, form);
                break;
        }
    });
}

/**
 * Populate true/false answer
 */
function populateTrueFalseAnswer(question, form) {
    // Check if answer exists in userAnswers object
    if (userAnswers[question.id] !== undefined) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `tf[${question.id}]`;
        input.value = userAnswers[question.id] ? 1 : 0;
        form.appendChild(input);
    }
}

/**
 * Populate multiple choice answer
 */
function populateMultipleChoiceAnswer(question, form) {
    // Check if answer exists in userAnswers object
    if (userAnswers[question.id] !== undefined) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `option[${question.id}]`;
        input.value = userAnswers[question.id];
        form.appendChild(input);
    }
}

/**
 * Populate matching answer
 * Backend expects: matching[question_id][uid] = match_id
 * Where uid is the item's uid, and match_id is the zone's match_id
 */
function populateMatchingAnswer(question, form) {

    // Get userAnswers for this question: { zoneId: [itemId1, itemId2, ...] }
    const answer = userAnswers[question.id];

    if (!answer) return;

    // Convert from zones→items to uid→zone_match_id
    for (const zoneId in answer) {
        const items = answer[zoneId];

        // Find the zone element to get its match_id
        const zone = document.querySelector(`#question${question.id} .drop-zone[data-zone-id="${zoneId}"]`);
        if (!zone) continue;

        const matchId = zone.getAttribute('data-zone-id');

        // For each item in this zone, create an input with uid→match_id
        items.forEach(itemId => {
            // Extract uid from itemId (format: itemXXX or just uid)
            const uid = itemId;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `matching[${question.id}][${uid}]`;
            input.value = matchId;
            form.appendChild(input);
        });
    }
}

/**
 * Populate sorting answer
 * Backend expects: sorting[question_id][uid] = position
 * Where uid is the word's uid, and position is its order (0, 1, 2, ...)
 */
function populateSortingAnswer(question, form) {
    // Get userAnswers for this question: [itemId1, itemId2, itemId3]
    let answer = userAnswers[question.id];

    if (!answer || !Array.isArray(answer)) return;

    //if we not reverse array the answers be wrong because the if we show the first item in order container it be last in html so must reverse array
    answer = answer.reverse(); //reverse array to get correct order

    // Convert array to uid→position format
    answer.forEach((itemId, index) => {
        // Extract uid from itemId (format: itemXXX or just uid)
        const uid = itemId;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `sorting[${question.id}][${uid}]`;
        input.value = index; // Position in sequence (0, 1, 2, ...)
        form.appendChild(input);
    });
}
