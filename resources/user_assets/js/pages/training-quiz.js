/**
 * Training Quiz Page - Interactive Controls Only
 * All content is now static in HTML
 */

// Quiz configuration
const QUIZ_CONFIG = QUIZ_DATA;

// Training quiz state
let currentQuestionIndex = 0;
let userAnswers = {};
let answerFeedback = {};
let selectedItem = null;

// Sound effects
const correctSound = new Audio(`${BASE_URL}/user_assets/sounds/correct.mp3`);
const wrongSound = new Audio(`${BASE_URL}/user_assets/sounds/wrong.mp3`);

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
  setupQuestionNavigation();
  setupQuestionNumbersNavigation();
  setupCurrentQuestionControls();
});

/**
 * Setup question navigation (Previous/Next buttons)
 */
function setupQuestionNavigation() {
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');

  console.log('current index',currentQuestionIndex)
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        switchToQuestion(currentQuestionIndex);
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (currentQuestionIndex < QUIZ_CONFIG.totalQuestions - 1) {
        currentQuestionIndex++;
        switchToQuestion(currentQuestionIndex);
      } else {
        finishPractice();
      }
    });
  }
}

/**
 * Setup question numbers navigation
 */
function setupQuestionNumbersNavigation() {
  const numberButtons = document.querySelectorAll('.question-number-btn');

  numberButtons.forEach(button => {
    button.addEventListener('click', () => {
      const questionIndex = parseInt(button.getAttribute('data-question-index'));
      currentQuestionIndex = questionIndex;
      switchToQuestion(currentQuestionIndex);
    });
  });
}

/**
 * Switch to a specific question
 */
function switchToQuestion(index) {
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
            const id = QUIZ_CONFIG.questions[currentQuestionIndex].id;
            const questionId = `question${id}`;
            const questionEl = document.getElementById(questionId);
            if (questionEl) {
                questionEl.style.display = 'block';
                questionEl.classList.add('fade-in');
            }

            // Update navigation buttons
            updateNavigationButtons(index);

            // Update question number buttons
            updateQuestionNumbers();

            // Setup controls for current question
            setupCurrentQuestionControls();
        }, 300);
    } else {
        // First load - no animation needed
        document.querySelectorAll('.question-container').forEach(q => {
            q.style.display = 'none';
            q.classList.remove('fade-out', 'fade-in');
        });

        const id = QUIZ_CONFIG.questions[currentQuestionIndex].id;
        const questionId = `question${id}`;
        const questionEl = document.getElementById(questionId);
        if (questionEl) {
            questionEl.style.display = 'block';
            questionEl.classList.add('fade-in');
        }

        // Update navigation buttons
        updateNavigationButtons(index);

        // Update question number buttons
        updateQuestionNumbers();

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

    if (prevBtn) {
        prevBtn.disabled = index === 0;
    }

    if (nextBtn) {
        const isLastQuestion = index === QUIZ_CONFIG.totalQuestions - 1;
        nextBtn.textContent = isLastQuestion ? 'Finish Practice' : 'Next';
    }
}

/**
 * Update question number buttons styling
 */
function updateQuestionNumbers() {
  const numberButtons = document.querySelectorAll('.question-number-btn');

  numberButtons.forEach((button, index) => {
    button.classList.remove('active', 'correct', 'incorrect', 'answered');

    if (index === currentQuestionIndex) {
      button.classList.add('active');
    } else {
      const questionId = QUIZ_CONFIG.questions[index].id;
      const feedback = answerFeedback[questionId];

      if (feedback !== undefined) {
        const isCorrect = typeof feedback === 'boolean'
          ? feedback
          : Object.values(feedback).every(v => v === true);
        button.classList.add(isCorrect ? 'correct' : 'incorrect');
      } else if (userAnswers[questionId] !== undefined) {
        button.classList.add('answered');
      }
    }
  });
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
  const isAlreadyAnswered = answerFeedback[question.id] !== undefined;

  // Always hide feedback first, then show if needed
  hideFeedback(question.id);

  if (isAlreadyAnswered) {
    // buttons.forEach(btn => btn.disabled = true);
    showFeedback(question.id, answerFeedback[question.id]);
    return;
  }

  buttons.forEach(button => {
    const newButton = button.cloneNode(true);
    button.parentNode.replaceChild(newButton, button);

    newButton.addEventListener('click', () => {
      const answer = newButton.getAttribute('data-answer') === 'true';
      userAnswers[question.id] = answer;

      const isCorrect = answer === question.correctAnswer;
      answerFeedback[question.id] = isCorrect;

      // Re-query buttons after DOM changes to get fresh NodeList
      const currentButtons = document.querySelectorAll(`button[data-question-id="${question.id}"].true-false-btn`);

      // Update button states
      currentButtons.forEach(btn => {
        const btnAnswer = btn.getAttribute('data-answer') === 'true';
        if (btnAnswer === answer) {
          btn.classList.add('selected');
          btn.classList.add(isCorrect ? 'correct' : 'incorrect');
        }
       // btn.disabled = true;
      });

      playFeedbackSound(isCorrect);
      showFeedback(question.id, isCorrect);
      updateQuestionNumbers();
    });
  });
}

/**
 * Setup Multiple Choice controls with instant feedback
 */
function setupMultipleChoiceControls(question) {
  const options = document.querySelectorAll(`.choice-option[data-question-id="${question.id}"]`);
  const isAlreadyAnswered = answerFeedback[question.id] !== undefined;

  // Always hide feedback first, then show if needed
  hideFeedback(question.id);

  if (isAlreadyAnswered) {
    // options.forEach(opt => opt.classList.add('disabled'));
    showFeedback(question.id, answerFeedback[question.id]);
    return;
  }

  options.forEach(option => {
    const newOption = option.cloneNode(true);
    option.parentNode.replaceChild(newOption, option);

    newOption.addEventListener('click', () => {
      const choiceId = newOption.getAttribute('data-choice-id');
      userAnswers[question.id] = choiceId;

      const isCorrect = parseInt(choiceId) === parseInt(question.correctAnswer);
      answerFeedback[question.id] = isCorrect;

      // Re-query options after DOM changes to get fresh NodeList
      const currentOptions = document.querySelectorAll(`.choice-option[data-question-id="${question.id}"]`);

      // Update option states
      currentOptions.forEach(opt => {
        if (opt.getAttribute('data-choice-id') === choiceId) {
          opt.classList.add(isCorrect ? 'correct' : 'incorrect');
        }
       // opt.classList.add('disabled');
      });

      playFeedbackSound(isCorrect);
      showFeedback(question.id, isCorrect);
      updateQuestionNumbers();
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
  const isAlreadyAnswered = answerFeedback[question.id] !== undefined;

  // Always hide feedback first, then show if needed
  hideFeedback(question.id);

  // If already answered, show the feedback
  if (isAlreadyAnswered) {
    const allCorrect = Object.values(answerFeedback[question.id]).every(v => v === true);
    showFeedback(question.id, allCorrect);
  }

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

  // userAnswers[question.id] = answer;
  //   console.log('question',question)
  //   console.log('answers',answer)
  //   console.log('user answers',userAnswers)

  const allZonesFilled = Object.keys(question.correctItems).every(
    zoneId => answer[zoneId] && answer[zoneId].length > 0
  );

  if (allZonesFilled) {
    const zoneFeedback = {};
    Object.keys(question.correctItems).forEach(zoneId => {
      const userItems = answer[zoneId] || [];
      const correctItems = question.correctItems[zoneId];
      const isCorrect = userItems.length === correctItems.length &&
                       userItems.every(item => correctItems.includes(item));
      zoneFeedback[zoneId] = isCorrect;
      // console.log('length',userItems.length === correctItems.length)
      // console.log('user items',userItems)
      // console.log('correct items',correctItems)
      // console.log('is correct zone:'+zoneId,isCorrect)
    });

    answerFeedback[question.id] = zoneFeedback;

    // Visual feedback
    dropZones.forEach(zone => {
      const zoneId = zone.getAttribute('data-zone-id');
      zone.classList.remove('correct' , 'incorrect');
      zone.classList.add(zoneFeedback[zoneId] ? 'correct' : 'incorrect');
    });

    const allCorrect = Object.values(zoneFeedback).every(v => v === true);
    playFeedbackSound(allCorrect);
    showFeedback(question.id, allCorrect);
    updateQuestionNumbers();
  }
}

/**
 * Setup Drag and Drop for "Correct Order" questions
 */
function setupDragDropOrderControls(question) {
  const dragItems = document.querySelectorAll(`#question${question.id} .drag-item`);
  const orderDropZone = document.getElementById(`orderDropZone_${question.id}`);
  const dragItemsContainer = document.querySelector(`#question${question.id} .drag-items-container`);
  const isAlreadyAnswered = answerFeedback[question.id] !== undefined;

  // Always hide feedback first, then show if needed
  hideFeedback(question.id);

  // If already answered, show the feedback
  if (isAlreadyAnswered) {
    showFeedback(question.id, answerFeedback[question.id]);
  }

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
  if (QUIZ_CONFIG.enableDragAndDrop) {

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

  // Check if all letters are placed
  if (answer.length === question.correctOrder.length) {
    // Check both LTR (left-to-right) and RTL (right-to-left) orders
    // This handles both English and Arabic word ordering
    // const correctOrderLTR = JSON.stringify(question.correctOrder);
    // const correctOrderRTL = JSON.stringify([...question.correctOrder].reverse());
    // const userAnswer = JSON.stringify(answer);
    //
    // const isCorrect = userAnswer === correctOrderLTR || userAnswer === correctOrderRTL;
    // console.log('User answer:', userAnswer);
      // console.log('Correct LTR (left-to-right):', correctOrderLTR);
      // console.log('Correct RTL (right-to-left):', correctOrderRTL);
      // console.log('Is correct:', isCorrect);

      let correctOrder = JSON.stringify(question.correctOrder);
      if (insertDirection==='start'){
          correctOrder = JSON.stringify([...question.correctOrder].reverse());
      }
      let userAnswer = JSON.stringify(answer);
    const isCorrect = userAnswer === correctOrder;

    // console.log('User answer:', userAnswer);
    // console.log('Correct Order', correctOrder);


    answerFeedback[question.id] = isCorrect;
    orderDropZone.classList.remove('correct','incorrect');
    orderDropZone.classList.add(isCorrect ? 'correct' : 'incorrect');
    playFeedbackSound(isCorrect);
    showFeedback(question.id, isCorrect);
    updateQuestionNumbers();
  }
}

/**
 * Play feedback sound
 */
function playFeedbackSound(isCorrect) {
  if (isCorrect) {
    correctSound.play().catch(err => console.log('Audio play failed:', err));
  } else {
    wrongSound.play().catch(err => console.log('Audio play failed:', err));
  }
}

/**
 * Show answer feedback message for a specific question
 */
function showFeedback(questionId, feedback) {
  const feedbackEl = document.getElementById(`answerFeedback_${questionId}`);
  if (!feedbackEl) return;

  const isCorrect = typeof feedback === 'boolean'
    ? feedback
    : Object.values(feedback).every(v => v === true);

  if (isCorrect) {
    feedbackEl.className = 'answer-feedback correct';
    feedbackEl.innerHTML = '<span class="answer-feedback-icon">✓</span><span>صحيح! إجابة ممتازة - Correct! Excellent answer</span>';
  } else {
    feedbackEl.className = 'answer-feedback incorrect';
    feedbackEl.innerHTML = '<span class="answer-feedback-icon">✗</span><span>خطأ، حاول مرة أخرى - Incorrect, try again</span>';
  }

  feedbackEl.style.display = 'flex';
}

/**
 * Hide answer feedback message for a specific question
 */
function hideFeedback(questionId) {
  const feedbackEl = document.getElementById(`answerFeedback_${questionId}`);
  if (feedbackEl) {
    feedbackEl.style.display = 'none';
  }
}

/**
 * Finish practice session
 */
function finishPractice() {
    let csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    showLoadingDialog()

    $.ajax({
        type: "POST",
        url: LESSON_TRACKING_URL,
        data:{
            '_token':csrf,
        },
        success: function () {
            console.log('Lesson tracked successfully');
            hideLoadingDialog()
            showCompletionDialog();
        },
        error: function (e) {
            console.error('Error tracking lesson:', e);
            hideLoadingDialog()

            // Show dialog anyway on error
            showCompletionDialog();
        }
    });
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
