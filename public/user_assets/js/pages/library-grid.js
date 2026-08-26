/**
 * Library Grid Page - Interactive Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize library grid interactions
  initLibraryGridInteractions();
});

/**
 * Initialize library grid interactions
 */
function initLibraryGridInteractions() {
  const courseCards = document.querySelectorAll('.course-card-grid');

  courseCards.forEach(card => {
    // Add click handlers for story action buttons
    const actionButtons = card.querySelectorAll('.story-action-btn');

    if (actionButtons.length > 0) {
      // Story cards with action buttons
      actionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
          e.stopPropagation();
          const url = button.getAttribute('data-url');
          const action = button.getAttribute('data-action');

          // Animate button
          button.style.transform = 'scale(0.95)';
          setTimeout(() => {
            button.style.transform = '';
            if (url) {
              window.location.href = url;
            }
          }, 150);
        });
      });
    } else {
      // Regular course cards
      card.addEventListener('click', () => {
        handleCardClick(card);
      });
    }

    // Add hover effects for unlocked courses
    if (!card.classList.contains('course-card-grid--locked')) {
      card.addEventListener('mouseenter', () => {
        card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
      });
    }

    // Locked cards shake animation
    if (card.classList.contains('course-card-grid--locked')) {
      card.addEventListener('click', (e) => {
        e.stopPropagation();
        shakeCard(card);
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
 * Handle course card click
 */
function handleCardClick(card) {
  const url = card.getAttribute('data-url');

  if (card.classList.contains('course-card-grid--completed')) {
    console.log('Navigating to completed course...');
    animateCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('course-card-grid--current')) {
    console.log('Continuing current course...');
    animateCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('course-card-grid--locked')) {
    console.log('Course is locked');
    shakeCard(card);
  }else {
      console.log('Navigating to course...');
      animateCardClick(card);
      setTimeout(() => {
          window.location.href = url;
      }, 200);
  }
}

/**
 * Animate card click
 */
function animateCardClick(card) {
  card.style.transform = 'scale(0.98)';
  setTimeout(() => {
    card.style.transform = 'translateY(-4px)';
  }, 150);
}

/**
 * Shake locked card
 */
function shakeCard(card) {
  card.style.animation = 'shake 0.5s';
  setTimeout(() => {
    card.style.animation = '';
  }, 500);
}

// Add shake animation CSS
const grid_style = document.createElement('style');
grid_style.textContent = `
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
    20%, 40%, 60%, 80% { transform: translateX(8px); }
  }
`;
document.head.appendChild(style);
