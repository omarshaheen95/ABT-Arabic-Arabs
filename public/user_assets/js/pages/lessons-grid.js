/**
 * Lessons Grid Page - Interactive Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize lessons grid interactions
  initLessonsGridInteractions();
});

/**
 * Initialize lessons grid interactions
 */
function initLessonsGridInteractions() {
  const lessonCards = document.querySelectorAll('.lesson-card-grid');

  lessonCards.forEach(card => {
    // Add click handlers for lesson action buttons
    const actionButtons = card.querySelectorAll('.lesson-action-btn');

    if (actionButtons.length > 0) {
      // Lesson cards with action buttons
      actionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation();

          // Check if button is disabled
          if (button.disabled || button.hasAttribute('disabled')) {
            return;
          }

          const url = button.getAttribute('data-url');
          const action = button.getAttribute('data-action');

          // Animate button
          button.style.transform = 'scale(0.95)';
          setTimeout(() => {
            button.style.transform = '';
            if (url && url !== 'javascript:void(0)') {
              window.location.href = url;
            }
          }, 150);
        });
      });
    } else {
      // Regular lesson cards
      card.addEventListener('click', () => {
        handleLessonCardClick(card);
      });
    }

    // Add hover effects for unlocked lessons
    if (!card.classList.contains('lesson-card-grid--locked')) {
      card.addEventListener('mouseenter', () => {
        card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
      });
    }

    // Locked cards shake animation
    if (card.classList.contains('lesson-card-grid--locked')) {
      card.addEventListener('click', (e) => {
        e.stopPropagation();
        shakeLessonCard(card);
      });
    }
  });

  // Animate lesson dots
  const lessonDots = document.querySelectorAll('.lesson-dot-grid');
  lessonDots.forEach((dot, index) => {
    // Add hover effect
    dot.addEventListener('mouseenter', () => {
      if (!dot.classList.contains('lesson-dot-grid--incomplete')) {
        dot.style.transform = 'scale(1.2)';
      }
    });

    dot.addEventListener('mouseleave', () => {
      dot.style.transform = 'scale(1)';
    });

    // Initial animation
    setTimeout(() => {
      dot.style.transition = 'transform 0.2s ease';
    }, index * 30);
  });
}

/**
 * Handle lesson card click
 */
function handleLessonCardClick(card) {
  const url = card.getAttribute('data-url');

  if (card.classList.contains('lesson-card-grid--completed')) {
    console.log('Navigating to completed lesson...');
    animateLessonCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('lesson-card-grid--current')) {
    console.log('Continuing current lesson...');
    animateLessonCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('lesson-card-grid--locked')) {
    console.log('Lesson is locked');
    shakeLessonCard(card);
  } else {
    console.log('Navigating to lesson...');
    animateLessonCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  }
}

/**
 * Animate lesson card click
 */
function animateLessonCardClick(card) {
  card.style.transform = 'scale(0.98)';
  setTimeout(() => {
    card.style.transform = 'translateY(-4px)';
  }, 150);
}

/**
 * Shake locked lesson card
 */
function shakeLessonCard(card) {
  card.style.animation = 'lessonShake 0.5s';
  setTimeout(() => {
    card.style.animation = '';
  }, 500);
}

// Add shake animation CSS
const lessonsGridStyle = document.createElement('style');
lessonsGridStyle.textContent = `
  @keyframes lessonShake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
    20%, 40%, 60%, 80% { transform: translateX(8px); }
  }
`;
document.head.appendChild(lessonsGridStyle);
