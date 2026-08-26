/**
 * Stories Levels Grid Page - Interactive Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize stories levels grid interactions
  initStoriesLevelsGridInteractions();
});

/**
 * Initialize stories levels grid interactions
 */
function initStoriesLevelsGridInteractions() {
  const storyCards = document.querySelectorAll('.story-level-card');

  storyCards.forEach(card => {
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
      // Regular story cards
      card.addEventListener('click', () => {
        handleStoryCardClick(card);
      });
    }

    // Add hover effects for unlocked stories
    if (!card.classList.contains('story-level-card--locked')) {
      card.addEventListener('mouseenter', () => {
        card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
      });
    }

    // Locked cards shake animation
    if (card.classList.contains('story-level-card--locked')) {
      card.addEventListener('click', (e) => {
        e.stopPropagation();
        shakeStoryCard(card);
      });
    }
  });
}

/**
 * Handle story card click
 */
function handleStoryCardClick(card) {
  const url = card.getAttribute('data-url');

  if (card.classList.contains('story-level-card--completed')) {
    console.log('Navigating to completed story level...');
    animateStoryCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('story-level-card--current')) {
    console.log('Continuing current story level...');
    animateStoryCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  } else if (card.classList.contains('story-level-card--locked')) {
    console.log('Story level is locked');
    shakeStoryCard(card);
  } else {
    console.log('Navigating to story level...');
    animateStoryCardClick(card);
    setTimeout(() => {
      window.location.href = url;
    }, 200);
  }
}

/**
 * Animate card click
 */
function animateStoryCardClick(card) {
  card.style.transform = 'scale(0.98)';
  setTimeout(() => {
    card.style.transform = 'translateY(-4px)';
  }, 150);
}

/**
 * Shake locked card
 */
function shakeStoryCard(card) {
  card.style.animation = 'storyLevelShake 0.5s';
  setTimeout(() => {
    card.style.animation = '';
  }, 500);
}

// Add shake animation CSS
const storyLevelGridStyle = document.createElement('style');
storyLevelGridStyle.textContent = `
  @keyframes storyLevelShake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
    20%, 40%, 60%, 80% { transform: translateX(8px); }
  }
`;
document.head.appendChild(storyLevelGridStyle);
