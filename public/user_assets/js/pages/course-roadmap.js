// Course Road Map Page JavaScript
// Interactive functionality for static roadmap

class CourseRoadMapPage {
  constructor() {
    this.init();
  }

  init() {
    this.drawSnakePath();
    this.bindEvents();
    // this.initializeAnimations(); // Commented out by user
  }

  drawSnakePath() {
    // Draw SVG path connecting all lesson steps
    const steps = document.querySelectorAll('.lesson-step');
    const svg = document.querySelector('.roadmap-path');

    if (!svg || steps.length === 0) return;

    // Collect all step positions
    const positions = [];
    steps.forEach(step => {
      const top = parseFloat(step.style.top) || 0;
      const left = parseFloat(step.style.left) || 0;

      // Check if this is a large milestone (treasure/trophy)
      const isLarge = step.classList.contains('last-step--last');
      const centerOffset = isLarge ? { x: 60, y: 60 } : { x: 38, y: 35 };

      positions.push({
        x: left + centerOffset.x,
        y: top + centerOffset.y
      });
    });

    // Create smooth curved path
    if (positions.length > 0) {
      let pathD = `M ${positions[0].x} ${positions[0].y}`;

      for (let i = 1; i < positions.length; i++) {
        const current = positions[i];
        const previous = positions[i - 1];

        // Simple smooth curve
        const midX = (previous.x + current.x) / 2;
        const midY = (previous.y + current.y) / 2;

        // Use quadratic curve for smooth connection
        pathD += ` Q ${midX} ${previous.y}, ${midX} ${midY}`;
        pathD += ` Q ${midX} ${current.y}, ${current.x} ${current.y}`;
      }

      // Create main visible path
      const mainPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      mainPath.setAttribute('d', pathD);
      mainPath.setAttribute('stroke', '#CBD5E1');
      mainPath.setAttribute('stroke-width', '4');
      mainPath.setAttribute('fill', 'none');
      mainPath.setAttribute('stroke-linecap', 'round');
      mainPath.setAttribute('stroke-linejoin', 'round');
      mainPath.setAttribute('opacity', '0.6');
      svg.appendChild(mainPath);
    }
  }

  bindEvents() {
    // Use event delegation for dynamically created elements
    const container = document.querySelector('.roadmap-content');
    if (!container) return;

    container.addEventListener('click', (e) => {
      // Handle CTA button clicks
      if (e.target.classList.contains('cta-button')) {
        this.handleStartLesson(e);
      }

      // Handle lesson card close button (original and dynamic)
      if (e.target.classList.contains('lesson-card-close-btn') || e.target.closest('.lesson-card-close-btn')) {
        const closeBtn = e.target.closest('.lesson-card-close-btn');
        const cardId = closeBtn?.dataset.cardId;

        if (cardId) {
          this.closeDynamicLessonCard(cardId);
        } else {
          this.closeLessonCard();
        }
      }

      // Handle lesson card learn button (original and dynamic)
      if (e.target.classList.contains('lesson-card-learn-btn')) {
          let id = e.target.dataset.lessonId;
        console.log('Starting lesson...');
        window.location.href = LESSON_PAGES_URL.replace(':id',id).replace(':key','learn');
      }

      // Handle lesson card action buttons (dynamic)
      if (e.target.classList.contains('lesson-card-action-btn')) {
        const action = e.target.dataset.action;
          let id = e.target.dataset.lessonId;

          if (action === 'assess') {
            console.log('Opening assessment...');
            window.location.href = LESSON_PAGES_URL.replace(':id',id).replace(':key','test');
        } else if (action === 'practice') {
          console.log('Opening practice...');
            window.location.href = LESSON_PAGES_URL.replace(':id',id).replace(':key','training');
        }
      }

      // Handle step clicks
      const step = e.target.closest('.lesson-step');
      if (step) {
        this.handleStepClick(e, step);
      }
    });
  }

  closeDynamicLessonCard(cardId) {
    const lessonCard = document.getElementById(cardId);
    if (!lessonCard) return;

    // Hide lesson card with animation
    lessonCard.style.opacity = '0';
    lessonCard.style.transform = 'scale(0.8)';

    setTimeout(() => {
      lessonCard.classList.add('lesson-card--hidden');
    }, 200);
  }

  handleStartLesson(e) {
    e.preventDefault();
    console.log('Showing lesson card...');

    const ctaBubble = document.getElementById('ctaBubble');
    const lessonCard = document.getElementById('lessonCard');

    if (ctaBubble && lessonCard) {
      // Hide CTA
      ctaBubble.style.opacity = '0';
      ctaBubble.style.transform = 'scale(0.8)';

      setTimeout(() => {
        ctaBubble.style.display = 'none';

        // Show lesson card
        lessonCard.classList.remove('lesson-card--hidden');
        lessonCard.style.opacity = '0';
        lessonCard.style.transform = 'scale(0.8)';

        setTimeout(() => {
          lessonCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          lessonCard.style.opacity = '1';
          lessonCard.style.transform = 'scale(1)';
        }, 10);
      }, 200);
    }
  }

  closeLessonCard() {
    const ctaBubble = document.getElementById('ctaBubble');
    const lessonCard = document.getElementById('lessonCard');

    if (ctaBubble && lessonCard) {
      // Hide lesson card
      lessonCard.style.opacity = '0';
      lessonCard.style.transform = 'scale(0.8)';

      setTimeout(() => {
        lessonCard.classList.add('lesson-card--hidden');

        // Show CTA
        ctaBubble.style.display = '';
        ctaBubble.style.opacity = '0';
        ctaBubble.style.transform = 'scale(0.8)';

        setTimeout(() => {
          ctaBubble.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          ctaBubble.style.opacity = '1';
          ctaBubble.style.transform = 'scale(1)';
        }, 10);
      }, 200);
    }
  }

  handleStepClick(e, step) {
    e.preventDefault();

    // Get step status and position
    const status = step.className.match(/lesson-step--(\w+)/)?.[1];
    const stepOrder = step.dataset.stepOrder;
    const levelIndex = step.dataset.level;

    if (!status) return;

    // Don't show card for locked steps
    if (status === 'locked') {
      alert('This lesson is locked. Complete previous lessons to unlock it.');
      return;
    }

    // Hide any existing lesson cards first
    this.hideAllLessonCards();

    // Create and show lesson card for this step
    this.showLessonCardForStep(step, status);
  }

  hideAllLessonCards() {
    const existingCards = document.querySelectorAll('.lesson-card:not(.lesson-card--hidden)');
    existingCards.forEach(card => {
      card.classList.add('lesson-card--hidden');
    });
  }

  showLessonCardForStep(step, status) {
    // Get step position
    const stepRect = step.getBoundingClientRect();
    const containerRect = step.parentElement.getBoundingClientRect();

    const stepTop = parseInt(step.style.top);
    const stepLeft = parseInt(step.style.left);

    // Create unique ID for this lesson card
    const stepOrder = step.dataset.stepOrder;
    const levelIndex = step.dataset.level;
    const lessonId = step.dataset.lessonId;
    const cardId = `lessonCard-${levelIndex}-${stepOrder}`;

    // Get lesson names from data attributes
    const nameArabic = step.dataset.nameArabic || 'أخلاقي';
    const nameEnglish = step.dataset.nameEnglish || 'My Manners';

    // Check if card already exists
    let lessonCard = document.getElementById(cardId);

    if (!lessonCard) {
      // Create new lesson card
      const stepHeight = 70;
      const cardOffset = { top: stepHeight + 30, left: -172 };
      const cardTop = stepTop + cardOffset.top;
      const cardLeft = stepLeft + cardOffset.left;
      const cardStyle = `top: ${cardTop}px; left: ${cardLeft}px;`;

      // Customize content based on status
      let buttonText = 'Learn now ! 20xp';

      if (status === 'complete') {
        buttonText = 'Review lesson';
      } else if (status === 'progress') {
        buttonText = 'Continue learning ! 20xp';
      }

      const cardHTML = `
        <div class="lesson-card" id="${cardId}" style="${cardStyle}">
          <div class="lesson-card-arrow"></div>
          <button class="lesson-card-close-btn" data-card-id="${cardId}" aria-label="Close"></button>
          <h2 class="lesson-card-title">${nameArabic}</h2>
          <p class="lesson-card-subtitle">${nameEnglish}</p>
          <button class="lesson-card-learn-btn" data-lesson-id="${lessonId}" data-card-id="${cardId}">${buttonText}</button>
          <div class="lesson-card-actions">
            <button class="lesson-card-action-btn" data-card-id="${cardId}" data-lesson-id="${lessonId}" data-action="assess">Assess yourself</button>
            <button class="lesson-card-action-btn" data-card-id="${cardId}" data-lesson-id="${lessonId}" data-action="practice">Practice</button>
          </div>
        </div>
      `;

      // Insert the card into the lesson-steps container
      const lessonStepsContainer = step.parentElement;
      lessonStepsContainer.insertAdjacentHTML('beforeend', cardHTML);

      lessonCard = document.getElementById(cardId);

      // Animate card appearance
      lessonCard.style.opacity = '0';
      lessonCard.style.transform = 'scale(0.8)';

      setTimeout(() => {
        lessonCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        lessonCard.style.opacity = '1';
        lessonCard.style.transform = 'scale(1)';
      }, 10);
    } else {
      // Show existing card
      lessonCard.classList.remove('lesson-card--hidden');
      lessonCard.style.opacity = '0';
      lessonCard.style.transform = 'scale(0.8)';

      setTimeout(() => {
        lessonCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        lessonCard.style.opacity = '1';
        lessonCard.style.transform = 'scale(1)';
      }, 10);
    }
  }

  // initializeAnimations() {
  //   // Animate steps appearing one by one with path drawing effect
  //   const steps = document.querySelectorAll('.lesson-step');
  //   const cta = document.querySelector('.cta-bubble');
  //   const levelHeaders = document.querySelectorAll('.level-header-inline');
  //   const illustrations = document.querySelectorAll('.level-illustration');
  //
  //   // Set initial hidden state for all elements
  //   steps.forEach(step => {
  //     step.style.opacity = '0';
  //     step.style.transform = 'scale(0.3)';
  //     step.style.transition = 'opacity 0.4s ease, transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
  //   });
  //
  //   if (cta) {
  //     cta.style.opacity = '0';
  //     cta.style.transform = 'scale(0.8) translateY(10px)';
  //     cta.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  //   }
  //
  //   levelHeaders.forEach(header => {
  //     header.style.opacity = '0';
  //     header.style.transform = 'translateY(-20px)';
  //     header.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  //   });
  //
  //   illustrations.forEach(illustration => {
  //     illustration.style.opacity = '0';
  //     illustration.style.transform = 'scale(0.9)';
  //     illustration.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
  //   });
  //
  //   // Animate elements sequentially
  //   let currentDelay = 300; // Start delay
  //   const stepDelay = 150; // Delay between each step
  //   const headerDelay = 400; // Extra delay for headers
  //   const illustrationDelay = 300; // Extra delay for illustrations
  //
  //   let lastLevelIndex = -1;
  //   let headerAnimated = false;
  //
  //   steps.forEach((step, index) => {
  //     const levelIndex = parseInt(step.dataset.level) || 0;
  //
  //     // Check if we're starting a new level
  //     if (levelIndex !== lastLevelIndex) {
  //       // Animate level header first
  //       const header = levelHeaders[levelIndex];
  //       if (header && !headerAnimated) {
  //         setTimeout(() => {
  //           header.style.opacity = '1';
  //           header.style.transform = 'translateY(0)';
  //         }, currentDelay);
  //         currentDelay += headerDelay;
  //
  //         // Animate illustration after header
  //         const illustration = illustrations[levelIndex];
  //         if (illustration) {
  //           setTimeout(() => {
  //             illustration.style.opacity = '1';
  //             illustration.style.transform = 'scale(1)';
  //           }, currentDelay);
  //           currentDelay += illustrationDelay;
  //         }
  //       }
  //
  //       lastLevelIndex = levelIndex;
  //     }
  //
  //     // Animate the step
  //     setTimeout(() => {
  //       step.style.opacity = '1';
  //       step.style.transform = 'scale(1)';
  //
  //       // Add a pulse effect on appearance
  //       setTimeout(() => {
  //         step.style.transform = 'scale(1.1)';
  //         setTimeout(() => {
  //           step.style.transform = 'scale(1)';
  //         }, 200);
  //       }, 100);
  //     }, currentDelay);
  //
  //     currentDelay += stepDelay;
  //   });
  //
  //   // Animate CTA last
  //   if (cta) {
  //     setTimeout(() => {
  //       cta.style.opacity = '1';
  //       cta.style.transform = 'scale(1) translateY(0)';
  //
  //       // Add gentle floating animation to CTA
  //       setTimeout(() => {
  //         cta.style.animation = 'float-cta 2s ease-in-out infinite';
  //       }, 500);
  //     }, currentDelay + 300);
  //   }
  //
  //   // Add floating animation keyframes if not already added
  //   if (!document.getElementById('cta-float-animation')) {
  //     const style = document.createElement('style');
  //     style.id = 'cta-float-animation';
  //     style.textContent = `
  //       @keyframes float-cta {
  //         0%, 100% { transform: scale(1) translateY(0); }
  //         50% { transform: scale(1) translateY(-8px); }
  //       }
  //     `;
  //     document.head.appendChild(style);
  //   }
  //
  //   // Intersection Observer for scroll animations (existing modules)
  //   const modules = document.querySelectorAll('.learning-module');
  //   const observerOptions = {
  //     threshold: 0.1,
  //     rootMargin: '0px 0px -100px 0px'
  //   };
  //
  //   const observer = new IntersectionObserver((entries) => {
  //     entries.forEach(entry => {
  //       if (entry.isIntersecting) {
  //         entry.target.style.opacity = '1';
  //         entry.target.style.transform = 'translateY(0)';
  //       }
  //     });
  //   }, observerOptions);
  //
  //   modules.forEach((module, index) => {
  //     module.style.opacity = '0';
  //     module.style.transform = 'translateY(30px)';
  //     module.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  //     module.style.transitionDelay = `${index * 0.1}s`;
  //     observer.observe(module);
  //   });
  // }
}

// Initialize the page when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  const roadMapPage = new CourseRoadMapPage();

  // Make it globally available for debugging
  window.courseRoadMapPage = roadMapPage;
});
