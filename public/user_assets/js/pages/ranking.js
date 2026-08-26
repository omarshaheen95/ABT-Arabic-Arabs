// Ranking Page JavaScript

class RankingPage {
  constructor() {
    this.init();
  }

  init() {
    console.log('Ranking page initialized');
    this.setupEventListeners();
    this.setupAnimations();
  }

  setupEventListeners() {
    // Setup participant card click handlers
    this.setupParticipantCardClickHandlers();
  }

  setupParticipantCardClickHandlers() {
    // Wait for DOM to be fully loaded
    const setupHandlers = () => {
      const participantCards = document.querySelectorAll('.participant-card');

      console.log('Found participant cards:', participantCards.length);

      participantCards.forEach(card => {
        // Make cards keyboard accessible
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.style.cursor = 'pointer';

        // Click handler
        card.addEventListener('click', () => {
          console.log('Card clicked!', card);
          this.navigateToParticipantInfo(card);
        });

        // Keyboard support (Enter and Space)
        card.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.navigateToParticipantInfo(card);
          }
        });

        // Add hover effect class
        card.addEventListener('mouseenter', () => {
          card.style.transform = 'scale(1.02)';
          card.style.transition = 'transform 0.2s ease';
        });

        card.addEventListener('mouseleave', () => {
          card.style.transform = 'scale(1)';
        });
      });
    };

    // If DOM is already loaded, setup immediately
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', setupHandlers);
    } else {
      setupHandlers();
    }
  }

  navigateToParticipantInfo(card) {
    // Extract participant data from card
    const nameElement = card.querySelector('.participant-name, .participant-name-first, .participant-name-current');
    const scoreElement = card.querySelector('.participant-score, .participant-score-current');
    const medalElement = card.querySelector('.medal-icon');

    const name = nameElement ? nameElement.textContent.trim() : 'Unknown';
    const xp = scoreElement ? scoreElement.textContent.replace(' XP', '').trim() : '0';

    // Determine rank based on medal or position
    let rank = '0';
    let badgePath = '../assets/images/illustrations/first.svg';

    if (medalElement) {
      const medalSrc = medalElement.src;
      if (medalSrc.includes('first')) {
        rank = '1';
        badgePath = `${BASE_URL}/user_assets/images/illustrations/first.svg`;
      } else if (medalSrc.includes('second')) {
        rank = '2';
        badgePath = `${BASE_URL}/user_assets/images/illustrations/second.svg`;
      } else if (medalSrc.includes('third')) {
        rank = '3';
        badgePath = `${BASE_URL}/user_assets/images/illustrations/third.svg`;
      }
    } else {
      // No medal, assign a default rank
      const allCards = Array.from(document.querySelectorAll('.participant-card'));
      rank = (allCards.indexOf(card) + 1).toString();
      badgePath = '../assets/images/illustrations/first.svg';
    }

    // Build URL with query parameters
    const params = new URLSearchParams({
      name: name,
      rank: rank,
      xp: xp,
      badge: badgePath
    });

    // Navigate to participant info page
    window.location.href = `participant-info.html?${params.toString()}`;
  }

  setupAnimations() {
    // Initialize score animations with delay
    this.initScoreAnimations();
  }

  observeScoreElements() {
    const scoreElements = document.querySelectorAll('.participant-score, .participant-score-current');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.animateScore(entry.target);
          observer.unobserve(entry.target); // Only animate once
        }
      });
    }, {
      threshold: 0.5,
      rootMargin: '0px 0px -10% 0px'
    });

    scoreElements.forEach(element => {
      observer.observe(element);
    });
  }

  animateScore(element) {
    const finalScore = parseInt(element.textContent.replace(' XP', ''));
    const duration = 1500; // 1.5 seconds
    const startTime = performance.now();

    // Add counting-up class for visual effect
    element.classList.add('counting-up');

    const animate = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Use easeOutQuart for smooth deceleration
      const easeProgress = 1 - Math.pow(1 - progress, 4);
      const currentScore = Math.floor(finalScore * easeProgress);

      element.textContent = `${currentScore} XP`;

      if (progress < 1) {
        requestAnimationFrame(animate);
      } else {
        // Animation complete - remove counting class
        element.classList.remove('counting-up');
        element.textContent = `${finalScore} XP`;
      }
    };

    // Start from 0
    element.textContent = '0 XP';
    requestAnimationFrame(animate);
  }

  initScoreAnimations() {
    // Delay the initial observation to allow page animations to complete
    setTimeout(() => {
      this.observeScoreElements();
    }, 2000);
  }

  // Future methods for ranking functionality
  loadRankingData() {
    // Will fetch ranking data from API
  }

  renderRankingList(data) {
    // Will render the ranking list
  }

  updateFilters() {
    // Will handle filter changes
  }
}

// Initialize ranking page
document.addEventListener('DOMContentLoaded', () => {
  new RankingPage();
});
