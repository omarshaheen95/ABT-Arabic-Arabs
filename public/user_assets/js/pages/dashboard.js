/**
 * Dashboard page interactivity and animations
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize all dashboard functionality
  initNavigation();
  initProgressAnimations();
  initStatsAnimations();
  initChallengeItems();
  initWelcomeBanner();
  initLearningPath();
  initCalendarInteractions();
  initNotificationBadge();
  initUserProfile();
  initPageLoadAnimations();
  initConnectorAnimations();
  watchCourseCardHeight();
});

// Page load animations
function initPageLoadAnimations() {
  // Animate elements on page load with staggered delays
  const animateElements = [
    { selector: '.navbar', delay: 0 },
    { selector: '.welcome-banner', delay: 100 },
    { selector: '.course-card', delay: 200 },
    { selector: '.user-profile-card', delay: 300 },
    { selector: '.calendar', delay: 400 },
    { selector: '.daily-competitions', delay: 500 }
  ];

  animateElements.forEach(({ selector, delay }) => {
    const elements = document.querySelectorAll(selector);
    elements.forEach((element, index) => {
      element.style.opacity = '0';
      element.style.transform = 'translateY(20px)';

      setTimeout(() => {
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
      }, delay + (index * 100));
    });
  });
}

// Navigation functionality
function initNavigation() {
  const navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(item => {
    // Add hover effects
    item.addEventListener('mouseenter', () => {
      if (!item.classList.contains('active')) {
        item.style.transform = 'translateX(5px)';
        item.style.transition = 'transform 0.2s ease';
      }
    });

    item.addEventListener('mouseleave', () => {
      if (!item.classList.contains('active')) {
        item.style.transform = 'translateX(0)';
      }
    });

    // Note: Click handler removed - navigation is handled by sidenav.js
    // Only hover effects are managed here for visual feedback
  });
}

// Handle logout
function handleLogout() {
  if (confirm('Are you sure you want to logout?')) {
    // Add logout animation
    document.body.style.transition = 'opacity 0.3s ease';
    document.body.style.opacity = '0';

    setTimeout(() => {
      // Redirect to login page
      window.location.href = 'login.html';
    }, 300);
  }
}

// Progress animations
function initProgressAnimations() {
  // Animate circular progress in sidebar
  const progressRing = document.querySelector('.progress-ring');
  if (progressRing) {
    setTimeout(() => {
      progressRing.style.transition = 'transform 0.3s ease';
      progressRing.style.transform = 'scale(1.05)';
      setTimeout(() => {
        progressRing.style.transform = 'scale(1)';
      }, 200);
    }, 800);
  }

  // Animate level progress bar
  const levelProgressBar = document.querySelector('.level-progress-bar');
  if (levelProgressBar) {
    const progressText = levelProgressBar.querySelector('.level-progress-text');
    if (progressText) {
      const match = progressText.textContent.match(/(\d+)\/(\d+)/);
      if (match) {
        const current = parseInt(match[1]);
        const total = parseInt(match[2]);
        const percentage = (current / total) * 100;

        levelProgressBar.style.width = '0%';
        setTimeout(() => {
          levelProgressBar.style.transition = 'width 1.5s ease-out';
          levelProgressBar.style.width = `${percentage}%`;
        }, 600);
      }
    }
  }

  // Animate course progress bars
  const courseProgressBars = document.querySelectorAll('.course-progress');
  courseProgressBars.forEach((bar, index) => {
    setTimeout(() => {
      bar.style.transition = 'width 1s ease-out';
      if (bar.classList.contains('course-progress--full')) {
        bar.style.width = '100%';
      }
    }, 400 + (index * 200));
  });

  // Animate challenge progress bars
  const challengeProgressBars = document.querySelectorAll('.challenge-progress');
  challengeProgressBars.forEach((bar, index) => {
    const progressText = bar.querySelector('.challenge-progress-text');
    if (progressText) {
      const match = progressText.textContent.match(/(\d+)\/(\d+)/);
      if (match) {
        const current = parseInt(match[1]);
        const total = parseInt(match[2]);
        const percentage = (current / total) * 100;

        bar.style.width = '0%';
        setTimeout(() => {
          bar.style.transition = 'width 1.2s ease-out';
          bar.style.width = `${percentage}%`;
        }, 800 + (index * 150));
      }
    }
  });
}

// Stats counter animations
function initStatsAnimations() {
  // Animate streak counter
  const streakValue = document.querySelector('.streak-stat .stat-value');
  if (streakValue) {
    animateCounter(streakValue, 0);
  }

  // Animate XP counter
  const xpValue = document.querySelector('.xp-stat .stat-value');
  if (xpValue) {
    const match = xpValue.textContent.match(/(\d+)/);
    if (match) {
      const finalValue = parseInt(match[1]);
      animateCounter(xpValue, finalValue, ' xp');
    }
  }

  // Animate notification badge
  const badgeNumber = document.querySelector('.badge-number');
  if (badgeNumber) {
    animateCounter(badgeNumber, 500);
  }
}

// Counter animation utility
function animateCounter(element, delay, suffix = '') {
  const finalText = element.textContent;
  const finalValue = parseInt(finalText);

  if (isNaN(finalValue)) return;

  let currentValue = 0;
  const increment = Math.max(1, Math.ceil(finalValue / 40));

  setTimeout(() => {
    const counter = setInterval(() => {
      currentValue += increment;
      if (currentValue >= finalValue) {
        currentValue = finalValue;
        clearInterval(counter);
      }
      element.textContent = currentValue + suffix;
    }, 30);
  }, delay);
}

// Challenge items functionality
function initChallengeItems() {
  const challengeItems = document.querySelectorAll('.challenge-item');

  challengeItems.forEach((item, index) => {
    // Add hover effects
    item.addEventListener('mouseenter', () => {
      item.style.transform = 'translateX(4px)';
      item.style.transition = 'transform 0.2s ease';
    });

    item.addEventListener('mouseleave', () => {
      item.style.transform = 'translateX(0)';
    });

    // Add click functionality
    item.addEventListener('click', () => {
      const challengeText = item.querySelector('.challenge-text').textContent;
      console.log(`Challenge clicked: ${challengeText}`);

      // Add click animation
      item.style.transform = 'scale(0.98)';
      setTimeout(() => {
        item.style.transform = 'scale(1)';
      }, 150);
    });
  });
}

// Welcome banner functionality
function initWelcomeBanner() {
  const continueBtn = document.querySelector('.continue-lesson-btn');

  if (continueBtn) {
    continueBtn.addEventListener('click', () => {
      // Add loading state
      const originalText = continueBtn.querySelector('.btn-text').textContent;
      continueBtn.querySelector('.btn-text').textContent = 'Loading...';
      //continueBtn.disabled = true;

      // Add loading animation
      continueBtn.style.transform = 'scale(0.95)';

      window.location.href=continueBtn.dataset.url;
      // Simulate loading
      // setTimeout(() => {
      //   continueBtn.querySelector('.btn-text').textContent = originalText;
      //   continueBtn.disabled = false;
      //   continueBtn.style.transform = 'scale(1)';
      //
      //   // Simulate navigation to lesson
      //   console.log('Continuing to lesson...');
      // }, 1500);
    });

    // Add hover effect
    continueBtn.addEventListener('mouseenter', () => {
      continueBtn.style.transform = 'translateY(-2px)';
      continueBtn.style.transition = 'transform 0.2s ease';
    });

    continueBtn.addEventListener('mouseleave', () => {
      continueBtn.style.transform = 'translateY(0)';
    });
  }
}

// Learning path interactions
function initLearningPath() {
  const courseCards = document.querySelectorAll('.course-card');

  courseCards.forEach(card => {
    // Only add interactions to unlocked courses
    if (!card.classList.contains('course-card--locked')) {
      card.addEventListener('click', () => {
        const title = card.querySelector('.course-title').textContent;
        const url = card.getAttribute('data-redirect-url');
        console.log(`Starting course: ${title}`);

        // Add selection animation
        card.style.transform = 'scale(1.02)';
        setTimeout(() => {
          card.style.transform = 'scale(1)';
          // Redirect to course roadmap
          window.location.href = url;
        }, 200);
      });

      // Add hover effects for unlocked courses
      card.addEventListener('mouseenter', () => {
        if (!card.classList.contains('course-card--current')) {
          card.style.transform = 'translateY(-4px)';
          card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
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
  const lessonDots = document.querySelectorAll('.lesson-dot');
  lessonDots.forEach((dot, index) => {
    setTimeout(() => {
      dot.style.transform = 'scale(1.2)';
      setTimeout(() => {
        dot.style.transform = 'scale(1)';
      }, 200);
    }, index * 50);
  });
}

// Calendar interactions
function initCalendarInteractions() {
  const dayActivities = document.querySelectorAll('.day-activity');

  dayActivities.forEach(day => {
    const dayIcon = day.querySelector('.day-icon');
    const dayLabel = day.querySelector('.day-label');

    // Add hover effects
    day.addEventListener('mouseenter', () => {
      dayIcon.style.transform = 'scale(1.1)';
      dayIcon.style.transition = 'transform 0.2s ease';
      dayLabel.style.color = '#4a90e2';
      dayLabel.style.transition = 'color 0.2s ease';
    });

    day.addEventListener('mouseleave', () => {
      if (!dayLabel.classList.contains('day-label--current')) {
        dayIcon.style.transform = 'scale(1)';
        dayLabel.style.color = '';
      }
    });

    // Add click functionality
    day.addEventListener('click', () => {
      // Remove active state from all days
      dayActivities.forEach(d => {
        d.querySelector('.day-label').classList.remove('day-label--current');
        d.querySelector('.day-icon').classList.remove('day-icon--current');
      });

      // Add active state to clicked day
      dayLabel.classList.add('day-label--current');
      dayIcon.classList.add('day-icon--current');

      console.log(`Selected day: ${dayLabel.textContent}`);

      // Add click animation
      dayIcon.style.transform = 'scale(0.9)';
      setTimeout(() => {
        dayIcon.style.transform = 'scale(1)';
      }, 150);
    });
  });

  // Animate streak counter in calendar
  const streakCountCalendar = document.querySelector('.streack-count-calendar span');
  if (streakCountCalendar) {
    setTimeout(() => {
      animateCounter(streakCountCalendar, 1000);
    }, 1200);
  }
}

// Notification badge interactions
function initNotificationBadge() {
  const notificationBadge = document.querySelector('.notification-badge');

  if (notificationBadge) {
    notificationBadge.addEventListener('click', () => {
      console.log('Opening notifications...');

      // Add click animation
      notificationBadge.style.transform = 'scale(0.9)';
      setTimeout(() => {
        notificationBadge.style.transform = 'scale(1)';
      }, 150);

      // Simulate badge number reset
      const badgeNumber = notificationBadge.querySelector('.badge-number');
      if (badgeNumber) {
        setTimeout(() => {
          badgeNumber.style.opacity = '0.5';
          setTimeout(() => {
            badgeNumber.style.opacity = '1';
          }, 300);
        }, 500);
      }
    });

    // Add hover effect
    notificationBadge.addEventListener('mouseenter', () => {
      notificationBadge.style.transform = 'scale(1.05)';
      notificationBadge.style.transition = 'transform 0.2s ease';
    });

    notificationBadge.addEventListener('mouseleave', () => {
      notificationBadge.style.transform = 'scale(1)';
    });
  }
}

// User profile interactions
function initUserProfile() {
  const userAvatar = document.querySelector('.user-avatar');
  const dropdownBtn = document.querySelector('.dropdown-btn');

  // User avatar click
  if (userAvatar) {
    userAvatar.addEventListener('click', () => {
      console.log('Opening user profile...');

      // Add click animation
      userAvatar.style.transform = 'scale(1.05)';
      setTimeout(() => {
        userAvatar.style.transform = 'scale(1)';
      }, 150);
    });

    // Add hover effect
    userAvatar.addEventListener('mouseenter', () => {
      userAvatar.style.transform = 'scale(1.02)';
      userAvatar.style.transition = 'transform 0.2s ease';
    });

    userAvatar.addEventListener('mouseleave', () => {
      userAvatar.style.transform = 'scale(1)';
    });
  }

  // Dropdown button
  if (dropdownBtn) {
    dropdownBtn.addEventListener('click', () => {
      console.log('Opening profile menu...');

      // Rotate arrow animation
      const arrow = dropdownBtn.querySelector('.dropdown-arrow');
      if (arrow) {
        arrow.style.transform = arrow.style.transform === 'rotate(90deg)'
          ? 'rotate(0deg)'
          : 'rotate(90deg)';
        arrow.style.transition = 'transform 0.3s ease';
      }

      // Add click animation to button
      dropdownBtn.style.transform = 'scale(0.95)';
      setTimeout(() => {
        dropdownBtn.style.transform = 'scale(1)';
      }, 150);
    });
  }

  // Level badge animation
  const levelBadge = document.querySelector('.level-badge');
  if (levelBadge) {
    setTimeout(() => {
      levelBadge.style.transform = 'rotate(5deg)';
      setTimeout(() => {
        levelBadge.style.transform = 'rotate(-5deg)';
        setTimeout(() => {
          levelBadge.style.transform = 'rotate(0deg)';
        }, 200);
      }, 200);
    }, 2000);
  }
}

// Utility functions
function smoothScrollTo(element) {
  element.scrollIntoView({
    behavior: 'smooth',
    block: 'start'
  });
}

/**
 * Animate continuous path line with growing effect
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
      incompleteLine.style.transition = 'opacity 1.5s ease-out';
      incompleteLine.style.opacity = '1';
    }, 1500);
  }
}

/**
 * Animate path dots appearing
 */
function animatePathDots() {
  const dots = document.querySelectorAll('.path-dot');

  dots.forEach((dot, index) => {
    dot.style.opacity = '0';
    dot.style.transform = 'scale(0)';
    dot.style.transformOrigin = 'center';

    setTimeout(() => {
      dot.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
      dot.style.opacity = '1';
      dot.style.transform = 'scale(1)';
    }, 500 + (index * 300));
  });
}

/**
 * Initialize connector animations
 */
function initConnectorAnimations() {
  animatePathLine();
  animatePathDots();
}

// Add floating animation to glass buttons
function initGlassButtonAnimations() {
  const glassButtons = document.querySelectorAll('.glass-button');

  glassButtons.forEach((button, index) => {
    // Add subtle floating animation with different delays
    setTimeout(() => {
      button.style.animation = `float 3s ease-in-out infinite ${index * 0.5}s`;
    }, index * 100);
  });
}

// CSS keyframe animation for floating effect
const floatingKeyframes = `
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-3px); }
}
`;

// Inject floating animation CSS
const style = document.createElement('style');
style.textContent = floatingKeyframes;
document.head.appendChild(style);

// Initialize floating animations
setTimeout(() => {
  initGlassButtonAnimations();
}, 2000);

/**
 * Watch course-card height and log to console
 * Uses ResizeObserver to detect height changes
 */
function watchCourseCardHeight() {
  const courseCards = document.querySelectorAll('.course-card');

  if (courseCards.length === 0) {
    //console.log('No course-card elements found');
    return;
  }

  // Log initial heights
  console.log('=== Course Card Heights ===');
  courseCards.forEach((card, index) => {
    const height = card.offsetHeight;
    const computedStyle = window.getComputedStyle(card);
    const paddingTop = parseFloat(computedStyle.paddingTop);
    const paddingBottom = parseFloat(computedStyle.paddingBottom);
    const contentHeight = height - paddingTop - paddingBottom;

    console.log(`Course Card ${index + 1}:`, {
      totalHeight: `${height}px`,
      contentHeight: `${contentHeight}px`,
      padding: `${paddingTop}px + ${paddingBottom}px`,
      element: card
    });
  });

  // Watch for height changes using ResizeObserver
  if (typeof ResizeObserver !== 'undefined') {
    const resizeObserver = new ResizeObserver((entries) => {
      entries.forEach((entry) => {
        const height = entry.contentRect.height;
        const card = entry.target;
        const cardIndex = Array.from(courseCards).indexOf(card) + 1;

        console.log(`Course Card ${cardIndex} height changed:`, {
          newHeight: `${height}px`,
          timestamp: new Date().toLocaleTimeString()
        });
      });
    });

    // Observe all course cards
    courseCards.forEach(card => {
      resizeObserver.observe(card);
    });

    console.log('ResizeObserver watching', courseCards.length, 'course cards');
  } else {
    console.log('ResizeObserver not supported in this browser');
  }
}
