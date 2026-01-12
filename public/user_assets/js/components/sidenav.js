class SideNavigation {
  constructor() {
    this.currentPage = 'dashboard';
    this.userProfile = USER_PROFILE
    this.userProgress = USER_PROFILE.progress;

    this.menuItems = MENU_ITEMS
  }

  render() {
    const totalUncompleted = (USER_STATUS.uncompletedLessons || 0) + (USER_STATUS.uncompletedStories || 0);

    return `
      <aside class="side-nav" style="font-family: var(--font-family)">
        <div class="sidenav-user-profile">
          <img src="${this.userProfile.avatar}" alt="User avatar" class="sidenav-user-avatar" />
          <div class="sidenav-user-info">
            <h3 class="sidenav-user-name">${this.userProfile.name}</h3>
          </div>
        </div>
        <nav class="nav-menu">
          ${this.menuItems.map(item => `
            <a href="${item.href}" class="nav-item ${item.id === this.currentPage ? 'active' : ''} ${item.special || ''}" data-page="${item.id}" style="${item.id === 'homework' && totalUncompleted > 0 ? 'position: relative;' : ''}">
              <span class="nav-icon">${item.icon}</span>
              <span class="nav-text">
                ${item.text}
              </span>
              ${item.id === 'homework' && totalUncompleted > 0 ? `
                <span class="assignment-badge" style="position: absolute; top: ${item.id === this.currentPage ? '14px' : '6px'}; right: 120px;">
                  <span class="badge-dot"></span>
                </span>
              ` : ''}
            </a>
          `).join('')}
        </nav>


      </aside>
      ${this.renderLogoutModal()}
    `;
  }
// <div class="user-progress">
// <div class="progress-circle">
// <div class="progress-ring">
// <div class="progress-value">${this.userProgress.percentage}%</div>
// </div>
// </div>
// <h3 class="progress-title">Today's Progress</h3>
// <p class="progress-subtitle">
// Finish Today's Tasks To Get <span class="highlight">${this.userProgress.todayTarget} ⚡</span>
// </p>
// </div>
  renderLogoutModal() {
    return `
      <div class="modal-overlay" id="logout-modal" aria-hidden="true">
        <div class="modal-container" role="dialog" aria-labelledby="modal-title" aria-modal="true">
          <div class="modal-header">
            <div class="modal-icon logout-icon">
              <span>🚪</span>
            </div>
          </div>
          <div class="modal-body">
            <h2 class="modal-title" id="modal-title">تأكيد تسجيل الخروج</h2>
            <p class="modal-message">هل أنت متأكد من رغبتك في تسجيل الخروج؟ تم حفظ تقدمك</p>
          </div>
          <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" id="logout-cancel">إلغاء</button>
            <button class="modal-btn modal-btn-confirm" id="logout-confirm">تسجيل الخروج</button>
          </div>
        </div>
      </div>
    `;
  }

  setActivePage(pageId) {
    this.currentPage = pageId;
    this.refresh();
  }

  updateProgress(progressData) {
    this.userProgress = { ...this.userProgress, ...progressData };
    this.refresh();
  }

  refresh() {
    const sideNavElement = document.querySelector('.side-nav');
    if (sideNavElement) {
      sideNavElement.outerHTML = this.render();
      this.bindEvents();
    }
  }

  bindEvents() {
    // Only bind event for logout, let other nav items work as normal links
    const logoutItem = document.querySelector('.nav-item[data-page="logout"]');
    if (logoutItem) {
      logoutItem.addEventListener('click', (e) => {
        e.preventDefault();
        this.showLogoutModal();
      });
    }

    // Bind modal events
    this.bindModalEvents();
  }

  bindModalEvents() {
    const modal = document.getElementById('logout-modal');
    const cancelBtn = document.getElementById('logout-cancel');
    const confirmBtn = document.getElementById('logout-confirm');

    if (cancelBtn) {
      cancelBtn.addEventListener('click', () => {
        this.hideLogoutModal();
      });
    }

    if (confirmBtn) {
      confirmBtn.addEventListener('click', () => {
        this.handleLogout();
      });
    }

    // Close modal when clicking outside
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          this.hideLogoutModal();
        }
      });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.hideLogoutModal();
      }
    });
  }

  showLogoutModal() {
    const modal = document.getElementById('logout-modal');
    if (modal) {
      modal.classList.add('active');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }
  }

  hideLogoutModal() {
    const modal = document.getElementById('logout-modal');
    if (modal) {
      modal.classList.remove('active');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }
  }

  handleLogout() {
    window.location.href = LOGOUT_URL;
  }
}

window.SideNavigationComponent = SideNavigation;
