class RightSidebar {
  constructor(config = {}) {
    this.config = {
      showUserProfile: true,
      showCalendar: true,
      showDailyChallenges: true,
      ...config
    };

    this.userProfile = userProfileData;

    this.calendar = CALANDER;

    this.dailyChallenges = {
      completed: 1,
      total: 3,
      challenges: [
        {
          icon: 'diamond.svg',
          text: 'spend 7 min in learning',
          current: 6,
          target: 7,
          status: 'partial'
        },
        {
          icon: 'flag.svg',
          text: 'Get 80% score in a quiz',
          current: 1,
          target: 1,
          status: 'complete'
        },
        {
          icon: 'clock.svg',
          text: 'Get 12 xp',
          current: 9,
          target: 12,
          status: 'almost'
        }
      ]
    };
  }

  render() {
    return `
      <aside class="right-sidebar">
        ${this.config.showUserProfile ? this.renderUserProfile() : ''}
        ${this.config.showCalendar ? this.renderCalendar() : ''}
      </aside>
    `;
  }

  renderUserProfile() {
    return `
    <div class="user-profile-card-container">
        <div class="user-profile-card sidebar-card">
          <div class="profile-content">
            <div class="profile-header">
              <div class="profile-avatar-container">
                <img
                  class="profile-user-avatar"
                  src="${BASE_URL}/user_assets/images/illustrations/profile.svg"
                  aria-label="User profile picture"
                />
                <button
                  class="profile-avatar-overlay"
                  type="button"
                  aria-label="View profile"
                >
                  <img
                    class="profile-overlay-icon"
                    src="${BASE_URL}/user_assets/images/icons/eye.svg"
                    alt="View profile"
                  />
                </button>
              </div>
              <div class="user-info">
                <div class="user-details">
                  <h2 class="user-full-name">${this.userProfile.fullName}</h2>
                  <p class="user-grade">
                    <span class="grade-number">${this.userProfile.gradeArabic}</span>

                  </p>
                  <p class="user-grade">
                    <span class="grade-label">السنة:</span>
                    <span class="grade-number">${this.userProfile.yearArabic}</span>
                  </p>
                </div>
              </div>
              <div class="profile-actions">
                <button
                  class="dropdown-btn"
                  type="button"
                  aria-label="Profile options"
                >
                  <img
                    class="dropdown-arrow"
                    src="${BASE_URL}/user_assets/images/icons/arrow-forward.svg"
                    alt="Dropdown arrow"
                  />
                </button>
              </div>
            </div>
          </div>
          <div class="user-level-info">
            <img
              class="level-badge"
              src="${this.userProfile.levelIcon}"
              alt="Level badge"
            />
            <div class="level-details">
              <p class="level-title">
                <span class="level-name">${this.userProfile.levelName} </span>
              </p>
              <div class="level-progress-container">
                <div class="level-progress-bg">
                  <div
                    class="level-progress-bar"
                    style="width: ${Math.min((this.userProfile.currentXp / this.userProfile.maxXp) * 100, 100)}%"
                    role="progressbar"
                    aria-valuenow="${this.userProfile.currentXp}"
                    aria-valuemin="0"
                    aria-valuemax="${this.userProfile.maxXp}"
                    aria-label="Level progress: ${this.userProfile.currentXp} out of ${this.userProfile.maxXp} XP"
                  ></div>
                  <span class="level-progress-text">${this.userProfile.currentXp}/${this.userProfile.maxXp}xp</span>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
    `;
  }

  renderCalendar() {
    return `
      <div class="sidebar-card calendar">
        <div class="calendar-header">
          <span class="calendar-date">${this.calendar.currentDate}</span>
          <div class="streack-count-calendar glass-button-component">
            <img
              style="width: 24px; height: 24px"
              src="${BASE_URL}/user_assets/images/illustrations/streak.svg"
              alt="Streak count"
            />
            <span class="">${this.calendar.streakCount}</span>
          </div>
        </div>
        <div class="calendar-grid">
          ${this.calendar.days.map(day => {
            const isCurrent = day.name === this.calendar.currentDay;
            const iconSrc = day.active
              ? `${BASE_URL}/user_assets/images/illustrations/streak.svg`
              : `${BASE_URL}/user_assets/images/illustrations/inactive-day.svg`;

            return `
            <div class="day-activity" role="listitem">
              <img
                class="day-icon glass-button-component ${isCurrent ? 'day-icon--current' : ''} ${!day.active ? 'day-icon--inactive' : ''}"
                src="${iconSrc}"
                alt="${day.name} activity"
              />
              <span class="day-label ${isCurrent ? 'day-label--current' : ''} ${!day.active ? 'day-label--inactive' : ''}">${day.name}</span>
            </div>
          `;
          }).join('')}
        </div>
      </div>
    `;
  }

  renderDailyChallenges() {
    return `
      <div class="sidebar-card daily-competitions">
        <div class="daily-competitions-header">
          <h3 class="card-title">Daily competitions</h3>
          <span class="competition-count">${this.dailyChallenges.completed}/${this.dailyChallenges.total}</span>
        </div>

        <div class="challenge-items-container">
        ${this.dailyChallenges.challenges.map(challenge => {
          // Calculate progress percentage
          const progressPercent = Math.min((challenge.current / challenge.target) * 100, 100);

          // Calculate responsive width using clamp formula
          // clamp(14px, 13.5vw, 260px) where the middle value scales with progress
          const minWidth = 14;
          const maxWidth = 260;
          const preferredVw = (progressPercent / 100) * 13.5; // Scale 13.5vw by progress percentage

          const progressWidthStyle = `clamp(${minWidth}px, ${preferredVw}vw, ${(progressPercent / 100) * maxWidth}px)`;

          // Determine state based on progress percentage
          const isComplete = progressPercent >= 100;
          const statusClass = isComplete ? 'complete' : 'in-progress';

          return `
          <div class="challenge-item">
            <img
              class="challenge-icon ${isComplete ? 'challenge-icon--quiz' : ''}"
              src="${BASE_URL}/user_assets/images/illustrations/${challenge.icon}"
              alt="${challenge.text} challenge icon"
            />
            <div class="challenge-details">
              <p class="challenge-text">${challenge.text}</p>
              <div class="challenge-progress-container">
                <div class="challenge-progress-bg"></div>
                <div
                  class="challenge-progress challenge-progress--${statusClass}"
                  style="width: ${progressWidthStyle};"
                  role="progressbar"
                  aria-valuenow="${challenge.current}"
                  aria-valuemin="0"
                  aria-valuemax="${challenge.target}"
                  aria-label="Progress: ${challenge.current} out of ${challenge.target}"
                ></div>
                <span class="challenge-progress-text challenge-progress-text--${statusClass}">${challenge.current}/${challenge.target}</span>
              </div>
            </div>
            <img
              class="challenge-status"
              src="${BASE_URL}/user_assets/images/illustrations/reward.svg"
              alt="Challenge status"
            />
          </div>
        `;
        }).join('')}
         </div>
      </div>
    `;
  }

  updateUserProfile(profileData) {
    this.userProfile = { ...this.userProfile, ...profileData };
    this.refresh();
  }

  updateCalendar(calendarData) {
    this.calendar = { ...this.calendar, ...calendarData };
    this.refresh();
  }

  updateChallenges(challengesData) {
    this.dailyChallenges = { ...this.dailyChallenges, ...challengesData };
    this.refresh();
  }

  show() {
    const sidebar = document.querySelector('.right-sidebar');
    if (sidebar) {
      sidebar.style.display = 'block';
      document.body.classList.add('has-right-sidebar');
    }
  }

  hide() {
    const sidebar = document.querySelector('.right-sidebar');
    if (sidebar) {
      sidebar.style.display = 'none';
      document.body.classList.remove('has-right-sidebar');
    }
  }

  refresh() {
    const sidebarElement = document.querySelector('.right-sidebar');
    if (sidebarElement) {
      sidebarElement.outerHTML = this.render();
      this.init();
    }
  }

  init() {
    const dropdownBtn = document.querySelector('.dropdown-btn');
    if (dropdownBtn) {
      dropdownBtn.addEventListener('click', () => {
        window.location.href = PROFILE_URL;
      });
    }

    const profileAvatarOverlay = document.querySelector('.profile-avatar-overlay');
    if (profileAvatarOverlay) {
      profileAvatarOverlay.addEventListener('click', () => {
        window.location.href = PROFILE_URL;
      });
    }
  }
}

window.RightSidebarComponent = RightSidebar;
