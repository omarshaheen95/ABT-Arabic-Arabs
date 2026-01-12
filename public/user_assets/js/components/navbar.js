class Navbar {
    constructor() {
        this.userStats = USER_STATUS;
        this.notificationsList = NOTIFICATION_LIST;
        this.userProfile = userProfileData;
        this.calendar = CALANDER;

    }

  render() {
    return `
      <nav class="navbar">
        <div class="navbar-start">
          <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Open menu">
            ☰
          </button>
          <a href="${BASE_URL}/home" class="navbar-logo-link">
            <img
              class="navbar-logo"
              src="${BASE_URL}/logo_circle.svg"
              alt="Non-Arabs LMS Logo"
            />
          </a>
        </div>
        <div class="navbar-end">
          <div class="streak-container">
            <button class="stat-item streak-stat" id="streak-stat-btn" aria-label="View streak calendar">
              <img
                class="stat-icon"
                src="${BASE_URL}/user_assets/images/illustrations/streak.svg"
                alt="Streak icon"
              />
              <span class="stat-value">${this.userStats.streak}</span>
            </button>
            <div class="streak-dropdown" id="streak-dropdown">
              ${this.renderCalendarMenu()}
            </div>
          </div>
          <div class="stat-item xp-stat">
            <img
              class="stat-icon"
              src="${BASE_URL}/user_assets/images/illustrations/diamond.svg"
              alt="XP icon"
            />
            <span class="stat-value xp-value">${this.userStats.xp} xp</span>
          </div>
          <div class="notification-container">
            <button class="notification-badge" id="notification-btn" aria-label="Open notifications">
              <img
                class="notification-icon"
                src="${BASE_URL}/user_assets/images/illustrations/notifications.svg"
                alt="Notifications icon"
              />
              <div class="badge-content">
                <span class="badge-number">${this.userStats.notifications}</span>
              </div>
            </button>
            <div class="notifications-dropdown" id="notifications-dropdown">
              <div class="notifications-header">
                <h3 class="notifications-title">Notifications</h3>
                <button class="mark-all-read" id="mark-all-read">Mark all as read</button>
              </div>
              <div class="notifications-list">
                ${this.renderNotifications()}
              </div>
            </div>
          </div>
          <div class="profile-container">
            <button class="profile-avatar-btn" id="profile-avatar-btn" aria-label="Open profile menu">
              <img
                class="profile-avatar-icon"
                src="${BASE_URL}/user_assets/images/illustrations/profile.svg"
                alt="User profile"
              />
            </button>
            <div class="profile-dropdown" id="profile-dropdown">
              ${this.renderProfileMenu()}
            </div>
          </div>
        </div>
      </nav>
    `;
  }

  renderNotifications() {
    if (this.notificationsList.length === 0) {
      return '<div class="no-notifications">لا يوجد اشعارات</div>';
    }

    return this.notificationsList.map(notification => `
      <div class="notification-item ${notification.read ? 'read' : 'unread'}" data-id="${notification.id}">
        <div class="notification-icon-wrapper ${notification.type}">
          ${this.getNotificationIcon(notification.icon_type)}
        </div>
        <div class="notification-content">
          <h4 class="notification-title">${notification.title}</h4>
          <p class="notification-message">${notification.message}</p>
          <span class="notification-time">${notification.time}</span>
        </div>
        ${!notification.read ? '<div class="unread-indicator"></div>' : ''}
      </div>
    `).join('');
  }

  getNotificationIcon(type) {
    const icons = {
      lesson: '📖',
      story: '📚',
      achievement: '🏆',
      message: '💬',
      reminder: '⏰'
    };
    return icons[type] || '🔔';
  }

  renderCalendarMenu() {
    return `
      <div class="calendar-menu-content">
        <div class="calendar-menu-header">
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

  renderProfileMenu() {
    return `
      <div class="profile-menu-content">
        <div class="profile-menu-card">
          <div class="profile-menu-header">
            <img
              class="profile-menu-avatar"
              src="${BASE_URL}/user_assets/images/illustrations/profile.svg"
              alt="User profile"
            />
            <div class="profile-menu-info">
              <h2 class="profile-menu-name">${this.userProfile.fullName}</h2>
              <p class="profile-menu-grade">
                <span class="grade-number">${this.userProfile.gradeArabic}</span>
                <span class="grade-label"> | سنة </span>
                <span class="grade-number">${this.userProfile.yearArabic}</span>
              </p>
            </div>
          </div>
          <div class="profile-menu-level">
            <img
              class="profile-menu-badge"
              src="${this.userProfile.levelIcon}"
              alt="Level badge"
            />
            <div class="profile-menu-level-details">
              <p class="profile-menu-level-title">
                <span class="level-name">${this.userProfile.levelName} </span>
              </p>
              <div class="profile-menu-progress-container">
                <div class="profile-menu-progress-bg">
                  <div
                    class="profile-menu-progress-bar"
                    style="width: ${Math.min((this.userProfile.currentXp / this.userProfile.maxXp) * 100, 100)}%"
                    role="progressbar"
                    aria-valuenow="${this.userProfile.currentXp}"
                    aria-valuemin="0"
                    aria-valuemax="${this.userProfile.maxXp}"
                    aria-label="Level progress: ${this.userProfile.currentXp} out of ${this.userProfile.maxXp} XP"
                  ></div>
                  <span class="profile-menu-progress-text">${this.userProfile.currentXp}/${this.userProfile.maxXp}xp</span>
                </div>
              </div>
            </div>
          </div>
          <button class="profile-menu-view-btn" id="view-profile-btn">
            عرض ملفي الشخصي
          </button>
        </div>
      </div>
    `;
  }

  attachEventListeners() {
    const notificationBtn = document.getElementById('notification-btn');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const markAllRead = document.getElementById('mark-all-read');
    const profileAvatarBtn = document.getElementById('profile-avatar-btn');
    const profileDropdown = document.getElementById('profile-dropdown');
    const viewProfileBtn = document.getElementById('view-profile-btn');
    const streakStatBtn = document.getElementById('streak-stat-btn');
    const streakDropdown = document.getElementById('streak-dropdown');

    if (notificationBtn && notificationsDropdown) {
      notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationsDropdown.classList.toggle('active');
        // Close other dropdowns if open
        if (profileDropdown) {
          profileDropdown.classList.remove('active');
        }
        if (streakDropdown) {
          streakDropdown.classList.remove('active');
        }
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', (e) => {
        if (!notificationBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
          notificationsDropdown.classList.remove('active');
        }
        if (profileAvatarBtn && profileDropdown &&
            !profileAvatarBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
          profileDropdown.classList.remove('active');
        }
        if (streakStatBtn && streakDropdown &&
            !streakStatBtn.contains(e.target) && !streakDropdown.contains(e.target)) {
          streakDropdown.classList.remove('active');
        }
      });

      // Mark all as read
      if (markAllRead) {
        markAllRead.addEventListener('click', () => {
          this.markAllAsRead();
        });
      }

      // Mark individual notification as read when clicked
      const notificationItems = document.querySelectorAll('.notification-item');
      notificationItems.forEach(item => {
        item.addEventListener('click', () => {
          const id = item.dataset.id;  // Keep as string (UUIDs are strings)
          this.markAsRead(id);
        });
      });
    }

    // Profile dropdown toggle
    if (profileAvatarBtn && profileDropdown) {
      profileAvatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
        // Close other dropdowns if open
        if (notificationsDropdown) {
          notificationsDropdown.classList.remove('active');
        }
        if (streakDropdown) {
          streakDropdown.classList.remove('active');
        }
      });
    }

    // Streak dropdown toggle
    if (streakStatBtn && streakDropdown) {
      streakStatBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        streakDropdown.classList.toggle('active');
        // Close other dropdowns if open
        if (notificationsDropdown) {
          notificationsDropdown.classList.remove('active');
        }
        if (profileDropdown) {
          profileDropdown.classList.remove('active');
        }
      });
    }

    // View profile button
    if (viewProfileBtn) {
      viewProfileBtn.addEventListener('click', () => {
        window.location.href = PROFILE_URL;
      });
    }
  }

  markAllAsRead() {
    // Make AJAX call to mark all notifications as read
    $.ajax({
      url: MARK_AS_READ_ALL_NOTIFICATION_URL,
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: (response) => {
        if (response.success) {
          // Update local state
          this.notificationsList.forEach(n => n.read = true);
          this.userStats.notifications = 0;
          this.refresh();
          this.attachEventListeners();
        }
      },
      error: (xhr, status, error) => {
        console.error('Error marking all notifications as read:', error);
      }
    });
  }

  markAsRead(id) {
    const notification = this.notificationsList.find(n => n.id === id);
    if (notification) {
      // If notification has a URL, navigate to read endpoint which will mark as read and redirect
      if (notification.url) {
        window.location.href = MARK_AS_READ_NOTIFICATION_URL.replace(':id',id);
        return;
      }


        window.location.href = MARK_AS_READ_NOTIFICATION_URL.replace(':id',id);

      // If no URL, make AJAX call and update UI after response
      // $.ajax({
      //   url: MARK_AS_READ_NOTIFICATION_URL.replace(':id',id),
      //   method: 'GET',
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   },
      //   success: (response) => {
      //     if (response.success) {
      //       // Update local state
      //       if (!notification.read) {
      //         notification.read = true;
      //         this.userStats.notifications = Math.max(0, this.userStats.notifications - 1);
      //       }
      //       if (response.redirect){
      //           window.location.href = response.redirect;
      //       }
      //       this.refresh();
      //       this.attachEventListeners();
      //     }
      //   },
      //   error: (xhr, status, error) => {
      //     console.error('Error marking notification as read:', error);
      //   }
      // });
    }
  }

  updateStats(stats) {
    this.userStats = { ...this.userStats, ...stats };
    this.refresh();
    this.attachEventListeners();
  }

  refresh() {
    const navbarElement = document.querySelector('.navbar');
    if (navbarElement) {
      navbarElement.outerHTML = this.render();
    }
  }
}

window.NavbarComponent = Navbar;
