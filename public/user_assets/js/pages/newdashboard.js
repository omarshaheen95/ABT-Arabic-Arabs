document.addEventListener('DOMContentLoaded', function() {
  initNavigation();
  initProgressAnimation();
  initDailyCompetitions();
  initWelcomeBanner();
  initCalendar();
  initUserProfile();
  initCourseCards();
});

function initNavigation() {
  const navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();

      if (item.classList.contains('logout')) {
        handleLogout();
        return;
      }

      navItems.forEach(nav => nav.classList.remove('active'));
      item.classList.add('active');

      const page = item.querySelector('.nav-label').textContent;
      console.log(`Navigating to: ${page}`);

      item.style.transform = 'scale(0.95)';
      setTimeout(() => {
        item.style.transform = 'scale(1)';
      }, 150);
    });
  });
}

function handleLogout() {
  if (confirm('Are you sure you want to logout?')) {
    document.body.style.transition = 'opacity 0.3s ease';
    document.body.style.opacity = '0';

    setTimeout(() => {
      window.location.href = 'login.html';
    }, 300);
  }
}

function initProgressAnimation() {
  const progressCircle = document.querySelector('.circle-progress');
  if (progressCircle) {
    const progress = progressCircle.getAttribute('data-progress');
    const circumference = 2 * Math.PI * 45;

    const circle = document.createElement('svg');
    circle.innerHTML = `
      <circle cx="50" cy="50" r="45" fill="none" stroke="#e6f3ff" stroke-width="8"/>
      <circle cx="50" cy="50" r="45" fill="none" stroke="url(#progressGradient)" stroke-width="8"
              stroke-dasharray="${circumference}"
              stroke-dashoffset="${circumference - (progress / 100) * circumference}"
              transform="rotate(-90 50 50)"/>
      <defs>
        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" style="stop-color:#1E4396"/>
          <stop offset="100%" style="stop-color:#5984E5"/>
        </linearGradient>
      </defs>
    `;
    circle.setAttribute('width', '100');
    circle.setAttribute('height', '100');
    circle.style.position = 'absolute';
    circle.style.top = '0';
    circle.style.left = '0';

    progressCircle.style.position = 'relative';
    progressCircle.appendChild(circle);
  }

  const progressBars = document.querySelectorAll('.progress-fill');
  progressBars.forEach((bar, index) => {
    const width = bar.style.width;
    bar.style.width = '0%';

    setTimeout(() => {
      bar.style.transition = 'width 1s ease-out';
      bar.style.width = width;
    }, 300 + (index * 200));
  });
}

function initDailyCompetitions() {
  const competitionItems = document.querySelectorAll('.competition-item');

  competitionItems.forEach((item, index) => {
    item.addEventListener('mouseenter', () => {
      item.style.transform = 'translateX(4px)';
      item.style.transition = 'transform 0.2s ease';
    });

    item.addEventListener('mouseleave', () => {
      item.style.transform = 'translateX(0)';
    });

    item.addEventListener('click', () => {
      const competitionText = item.querySelector('.competition-text').textContent;
      console.log(`Competition clicked: ${competitionText}`);

      item.style.transform = 'scale(0.98)';
      setTimeout(() => {
        item.style.transform = 'scale(1)';
      }, 150);
    });
  });
}

function initWelcomeBanner() {
  const continueBtn = document.querySelector('.continue-btn');

  if (continueBtn) {
    continueBtn.addEventListener('click', () => {
      const originalText = continueBtn.textContent;
      continueBtn.textContent = 'Loading...';
      continueBtn.disabled = true;

      setTimeout(() => {
        continueBtn.textContent = originalText;
        continueBtn.disabled = false;
        console.log('Continuing to lesson...');
      }, 1500);
    });
  }
}

function initCalendar() {
  const dayItems = document.querySelectorAll('.day-item');

  dayItems.forEach(day => {
    day.addEventListener('click', () => {
      dayItems.forEach(d => d.classList.remove('active'));
      day.classList.add('active');

      const dayLabel = day.querySelector('.day-label').textContent;
      console.log(`Selected day: ${dayLabel}`);
    });
  });
}

function initUserProfile() {
  const profileDropdown = document.querySelector('.profile-dropdown');

  if (profileDropdown) {
    profileDropdown.addEventListener('click', () => {
      console.log('Opening profile options...');

      profileDropdown.style.transform = 'rotate(180deg)';
      setTimeout(() => {
        profileDropdown.style.transform = 'rotate(0deg)';
      }, 200);
    });
  }
}

function initCourseCards() {
  const courseCards = document.querySelectorAll('.course-card');

  courseCards.forEach(card => {
    if (!card.classList.contains('locked')) {
      card.addEventListener('click', () => {
        const title = card.querySelector('.course-title').textContent;
        console.log(`Starting course: ${title}`);

        card.style.transform = 'scale(1.02)';
        setTimeout(() => {
          card.style.transform = 'scale(1)';
        }, 200);
      });

      card.addEventListener('mouseenter', () => {
        if (!card.classList.contains('current')) {
          card.style.transform = 'translateY(-2px)';
          card.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
        }
      });

      card.addEventListener('mouseleave', () => {
        if (!card.classList.contains('current')) {
          card.style.transform = 'translateY(0)';
          card.style.boxShadow = 'none';
        }
      });
    }
  });
}

function initNotificationBadge() {
  const notificationBadge = document.querySelector('.notification-badge');

  if (notificationBadge) {
    notificationBadge.addEventListener('click', () => {
      console.log('Opening notifications...');

      notificationBadge.style.transform = 'scale(1.1)';
      setTimeout(() => {
        notificationBadge.style.transform = 'scale(1)';
      }, 150);
    });
  }
}

function initStatsAnimation() {
  const statValues = document.querySelectorAll('.stat-value');

  statValues.forEach(stat => {
    const finalValue = stat.textContent;
    const numMatch = finalValue.match(/\d+/);

    if (numMatch) {
      const numValue = parseInt(numMatch[0]);
      let currentValue = 0;
      const increment = Math.ceil(numValue / 30);

      const counter = setInterval(() => {
        currentValue += increment;
        if (currentValue >= numValue) {
          currentValue = numValue;
          clearInterval(counter);
        }
        stat.textContent = finalValue.replace(/\d+/, currentValue);
      }, 50);
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  initNotificationBadge();
  initStatsAnimation();
});