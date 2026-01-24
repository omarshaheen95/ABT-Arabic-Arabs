/**
 * Library Page - Interactive Controls Only
 * All content is now static in HTML
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize library functionality
  initLibraryInteractions();
  animatePathLine();
});

/**
 * Animate path line with growing effect
 */
function animatePathLine() {
  const completedLine = document.querySelector('.path-line--completed');
  const incompleteLine = document.querySelector('.path-line--incomplete');

  if (completedLine) {
    completedLine.style.opacity = '0';
    completedLine.style.transform = 'scaleY(0)';
    completedLine.style.transformOrigin = 'top';

    setTimeout(() => {
      completedLine.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
      completedLine.style.opacity = '1';
      completedLine.style.transform = 'scaleY(1)';
    }, 300);
  }

  if (incompleteLine) {
    incompleteLine.style.opacity = '0';

    setTimeout(() => {
      incompleteLine.style.transition = 'opacity 0.6s ease-out';
      incompleteLine.style.opacity = '1';
    }, 800);
  }
}

/**
 * Initialize library interactions
 */
function initLibraryInteractions() {
  const courseCards = document.querySelectorAll('.course-card');

  courseCards.forEach((card, index) => {
    // Add fade-in animation with stagger
    card.style.opacity = '0';
    card.style.transition = 'opacity 0.6s ease, transform 0.3s ease';
    setTimeout(() => {
      card.style.opacity = '1';
    }, index * 200);

    // Add click handlers
    card.addEventListener('click', () => {
      handleCardClick(card);
    });

    // Add hover effects for unlocked courses
    if (!card.classList.contains('course-card--locked')) {
      card.addEventListener('mouseenter', () => {
        if (!card.classList.contains('course-card--current')) {
          card.style.transform = 'translateY(-4px)';
          card.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
        }
      });

      card.addEventListener('mouseleave', () => {
        if (!card.classList.contains('course-card--current')) {
          card.style.transform = 'translateY(0)';
          card.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
        }
      });
    }

    // Add pulse animation to current course
    if (card.classList.contains('course-card--current')) {
      setInterval(() => {
        card.style.transform = 'scale(1.02)';
        setTimeout(() => {
          card.style.transform = 'scale(1)';
        }, 300);
      }, 3000);
    }
  });

  // Animate lesson dots
  const lessonDots = document.querySelectorAll('.lesson-dot, .lesson-dot-completed');
  lessonDots.forEach((dot, index) => {
    // Add hover tooltip
    dot.addEventListener('mouseenter', () => {
      const lessonNumber = (index % 16) + 1;
      showLessonTooltip(dot, lessonNumber);
    });

    dot.addEventListener('mouseleave', () => {
      hideLessonTooltip();
    });

    // Add click handler
    dot.addEventListener('click', (e) => {
      e.stopPropagation();
      handleLessonDotClick(dot, index);
    });

    // Initial animation
    setTimeout(() => {
      dot.style.transform = 'scale(1.2)';
      setTimeout(() => {
        dot.style.transform = 'scale(1)';
      }, 200);
    }, index * 50);
  });
}

/**
 * Handle course card click
 */
function handleCardClick(card) {
  const courseId = card.getAttribute('data-course-id');

  if (card.classList.contains('course-card--completed')) {
    console.log('Navigating to completed course...');
    animateCardClick(card);
    // Navigate to course roadmap page after animation
    setTimeout(() => {
      window.location.href = `course-roadmap.html?courseId=${courseId}`;
    }, 200);
  } else if (card.classList.contains('course-card--current')) {
    console.log('Continuing current course...');
    animateCardClick(card);
    // Navigate to course roadmap page after animation
    setTimeout(() => {
      window.location.href = `course-roadmap.html?courseId=${courseId}`;
    }, 200);
  } else if (card.classList.contains('course-card--locked')) {
    console.log('Course is locked');
    shakeCard(card);
  }
}

/**
 * Animate card click
 */
function animateCardClick(card) {
  card.style.transform = 'scale(0.98)';
  setTimeout(() => {
    card.style.transform = 'scale(1)';
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

/**
 * Handle lesson dot click
 */
function handleLessonDotClick(dot, index) {
  const lessonNumber = (index % 16) + 1;

  if (dot.classList.contains('lesson-dot--complete') || dot.classList.contains('lesson-dot-completed')) {
    console.log(`Opening lesson ${lessonNumber}...`);
    animateDotClick(dot);
  } else {
    console.log(`Lesson ${lessonNumber} is locked`);
  }
}

/**
 * Animate dot click
 */
function animateDotClick(dot) {
  const originalTransform = dot.style.transform;
  dot.style.transform = 'scale(1.3)';
  setTimeout(() => {
    dot.style.transform = originalTransform || 'scale(1)';
  }, 200);
}

/**
 * Show lesson tooltip
 */
let currentTooltip = null;

function showLessonTooltip(dot, lessonNumber) {
  // Remove any existing tooltip
  hideLessonTooltip();

  // Create tooltip element
  const tooltip = document.createElement('div');
  tooltip.className = 'lesson-tooltip';
  tooltip.textContent = `Lesson ${lessonNumber}`;
  tooltip.style.cssText = `
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: clamp(4px, 0.31vw, 6px) clamp(8px, 0.625vw, 12px);
    border-radius: clamp(4px, 0.31vw, 6px);
    font-size: clamp(10px, 0.625vw, 12px);
    font-family: var(--font-family);
    pointer-events: none;
    z-index: 1000;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.2s ease;
  `;

  document.body.appendChild(tooltip);

  // Position tooltip
  const rect = dot.getBoundingClientRect();
  const tooltipOffset = Math.max(4, Math.min(8, window.innerWidth * 0.00416));
  tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
  tooltip.style.top = `${rect.top - tooltip.offsetHeight - tooltipOffset}px`;

  // Fade in
  requestAnimationFrame(() => {
    tooltip.style.opacity = '1';
  });

  currentTooltip = tooltip;
}

/**
 * Hide lesson tooltip
 */
function hideLessonTooltip() {
  if (currentTooltip) {
    currentTooltip.style.opacity = '0';
    setTimeout(() => {
      if (currentTooltip && currentTooltip.parentNode) {
        currentTooltip.parentNode.removeChild(currentTooltip);
      }
      currentTooltip = null;
    }, 200);
  }
}

// Add shake animation CSS
const style = document.createElement('style');
style.textContent = `
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
    20%, 40%, 60%, 80% { transform: translateX(10px); }
  }
`;
document.head.appendChild(style);
