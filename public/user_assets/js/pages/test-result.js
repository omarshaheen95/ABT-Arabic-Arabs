/**
 * Quiz Result Page - Interactive Controls Only
 * All content is static in HTML
 */

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
  // Animate cards on load
  animateCards();

  // Setup button handlers
  setupButtonHandlers();
});

/**
 * Animate result cards on load
 */
function animateCards() {
  const cards = document.querySelectorAll('.result-card');

  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';

    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 150);
  });
}

/**
 * Setup button click handlers
 */
function setupButtonHandlers() {
  const certificateBtn = document.querySelector('.certificate-btn');
  const nextLessonBtn = document.querySelector('.next-lesson-btn');

  if (certificateBtn) {
    certificateBtn.addEventListener('click', () => {
      // Navigate to certificates page
      window.location.href = certificateBtn.dataset.url;
    });
  }

  if (nextLessonBtn) {
    nextLessonBtn.addEventListener('click', () => {
      // Navigate to next lesson
      window.location.href = nextLessonBtn.dataset.url;
    });
  }
}
