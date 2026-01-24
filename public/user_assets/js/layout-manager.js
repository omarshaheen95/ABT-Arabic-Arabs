class LayoutManager {
  constructor(config = {}) {
    this.config = {
      showRightSidebar: true,
      currentPage: 'dashboard',
      ...config
    };

    this.navbar = null;
    this.sideNav = null;
    this.rightSidebar = null;

    this.init();
  }

  init() {
    this.loadComponents();
    this.setupLayout();
    this.bindEvents();
  }

  loadComponents() {
    // Initialize components
    this.navbar = new NavbarComponent();
    this.sideNav = new SideNavigationComponent();

    // Initialize right sidebar with page-specific config
    const sidebarConfig = this.config.sidebarConfig || {};
    this.rightSidebar = new RightSidebarComponent(sidebarConfig);

    // Set current page in side nav
    this.sideNav.setActivePage(this.config.currentPage);
  }

  setupLayout() {
    // Create layout container if it doesn't exist
    let layoutContainer = document.querySelector('.dashboard-layout');
    if (!layoutContainer) {
      layoutContainer = document.createElement('div');
      layoutContainer.className = 'dashboard-layout';
      document.body.appendChild(layoutContainer);
    }

    // Apply sidebar visibility
    this.updateSidebarVisibility();

    // Render components
    this.renderLayout();
  }

  renderLayout() {
    const layoutContainer = document.querySelector('.dashboard-layout');
    if (!layoutContainer) return;

    // Clear existing content except main-content
    const existingMainContent = layoutContainer.querySelector('.main-content');
    const mainContentHTML = existingMainContent ? existingMainContent.outerHTML : '<main class="main-content"></main>';

    layoutContainer.innerHTML = `
      ${this.navbar.render()}
      ${this.sideNav.render()}
      ${mainContentHTML}
      ${this.config.showRightSidebar ? this.rightSidebar.render() : ''}
      <div class="drawer-backdrop" id="drawer-backdrop"></div>
    `;

    // Rebind events after rendering
    this.navbar.attachEventListeners();
    this.sideNav.bindEvents();
    this.bindDrawerEvents();

    // Initialize right sidebar events
    if (this.config.showRightSidebar && this.rightSidebar) {
      this.rightSidebar.init();
    }
  }

  updateSidebarVisibility() {
    const layoutContainer = document.querySelector('.dashboard-layout');
    if (!layoutContainer) return;

    if (this.config.showRightSidebar) {
      layoutContainer.classList.remove('no-sidebar');
      layoutContainer.classList.add('show-sidebar');
    } else {
      layoutContainer.classList.add('no-sidebar');
      layoutContainer.classList.remove('show-sidebar');
    }
  }

  showRightSidebar() {
    this.config.showRightSidebar = true;
    this.updateSidebarVisibility();

    // Add sidebar if it doesn't exist
    const existing = document.querySelector('.right-sidebar');
    if (!existing) {
      const layoutContainer = document.querySelector('.dashboard-layout');
      layoutContainer.insertAdjacentHTML('beforeend', this.rightSidebar.render());
      this.rightSidebar.init();
    } else {
      existing.style.display = 'flex';
    }
  }

  hideRightSidebar() {
    this.config.showRightSidebar = false;
    this.updateSidebarVisibility();

    const sidebar = document.querySelector('.right-sidebar');
    if (sidebar) {
      sidebar.style.display = 'none';
    }
  }

  setMainContent(content) {
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      mainContent.innerHTML = content;
    }
  }

  setCurrentPage(pageId) {
    this.config.currentPage = pageId;
    this.sideNav.setActivePage(pageId);
  }

  updateNavbarStats(stats) {
    this.navbar.updateStats(stats);
    this.navbar.attachEventListeners();
  }

  updateUserProgress(progressData) {
    this.sideNav.updateProgress(progressData);
  }

  updateUserProfile(profileData) {
    if (this.rightSidebar) {
      this.rightSidebar.updateUserProfile(profileData);
    }
  }

  updateCalendar(calendarData) {
    if (this.rightSidebar) {
      this.rightSidebar.updateCalendar(calendarData);
    }
  }

  updateChallenges(challengesData) {
    if (this.rightSidebar) {
      this.rightSidebar.updateChallenges(challengesData);
    }
  }

  bindEvents() {
    // Handle window resize for responsive behavior
    window.addEventListener('resize', () => {
      this.handleResize();
    });

    // Handle initial resize
    this.handleResize();
  }

  bindDrawerEvents() {
    // Mobile menu button
    const menuBtn = document.getElementById('mobile-menu-btn');
    if (menuBtn) {
      menuBtn.addEventListener('click', () => this.openDrawer());
    }

    // Drawer close button
    // const closeBtn = document.getElementById('drawer-close-btn');
    // if (closeBtn) {
    //   closeBtn.addEventListener('click', () => this.closeDrawer());
    // }

    // Backdrop click to close
    const backdrop = document.getElementById('drawer-backdrop');
    if (backdrop) {
      backdrop.addEventListener('click', () => this.closeDrawer());
    }

    // Close drawer when clicking nav items (but don't prevent default)
    const navItems = document.querySelectorAll('.side-nav .nav-item');
    navItems.forEach(item => {
      item.addEventListener('click', () => {
        // Only close drawer on mobile, don't prevent navigation
        if (window.innerWidth <= 1150) {
          // Close drawer immediately to allow navigation
          setTimeout(() => this.closeDrawer(), 100);
        }
      });
    });
  }

  openDrawer() {
    const sideNav = document.querySelector('.side-nav');
    const backdrop = document.getElementById('drawer-backdrop');

    if (sideNav) {
      sideNav.classList.add('active');
    }
    if (backdrop) {
      backdrop.classList.add('active');
    }

    // Prevent body scrolling when drawer is open
    document.body.style.overflow = 'hidden';
  }

  closeDrawer() {
    const sideNav = document.querySelector('.side-nav');
    const backdrop = document.getElementById('drawer-backdrop');

    if (sideNav) {
      sideNav.classList.remove('active');
    }
    if (backdrop) {
      backdrop.classList.remove('active');
    }

    // Restore body scrolling
    document.body.style.overflow = '';
  }

  handleResize() {
    const width = window.innerWidth;

    if (width <= 1024) {
      // Hide right sidebar on smaller screens
      // this.hideRightSidebar();
    } else if (this.config.showRightSidebar) {
      // Show right sidebar on larger screens if configured
      this.showRightSidebar();
    }
  }

  // Static method to initialize layout for any page
  static initPage(pageConfig = {}) {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        new LayoutManager(pageConfig);
      });
    } else {
      new LayoutManager(pageConfig);
    }
  }

  // Static method to configure which pages should show the right sidebar
  static getPageConfig(pageName) {
    const pageConfigs = {
      'dashboard': {
        showRightSidebar: true,
        currentPage: 'dashboard'
      },
      'library': {
        showRightSidebar: true,
        currentPage: 'library'
      },
      'library-grid': {
        showRightSidebar: true,
        currentPage: 'library-grid'
      },
      'lesson': {
        showRightSidebar: true,
        currentPage: 'lesson',
        sidebarConfig: {
          showUserProfile: false,
          showCalendar: true,
          showDailyChallenges: true
        }
      },
      'homework': {
        showRightSidebar: false,
        currentPage: 'homework'
      },
      'ranking': {
        showRightSidebar: true,
        currentPage: 'ranking'
      },
      'participant-info': {
        showRightSidebar: true,
        currentPage: 'participant-info',
        sidebarConfig: {
          showUserProfile: false,
          showCalendar: true,
          showDailyChallenges: true
        }
      },
      'certificates': {
        showRightSidebar: false,
        currentPage: 'certificates'
      },
      'profile': {
        showRightSidebar: true,
        currentPage: 'profile'
      },
      'settings': {
        showRightSidebar: false,
        currentPage: 'settings'
      },
      'course-road-map': {
        showRightSidebar: true,
        currentPage: 'course-road-map'
      },
      'quiz': {
        showRightSidebar: false,
        currentPage: 'quiz'
      },
      'training-quiz': {
        showRightSidebar: false,
        currentPage: 'training-quiz'
      },
      'quiz-result': {
        showRightSidebar: false,
        currentPage: 'quiz-result'
      },
      'watch': {
        showRightSidebar: false,
        currentPage: 'watch'
      }, 'story-read': {
            showRightSidebar: true,
            currentPage: 'library'
        }, 'story-watch': {
            showRightSidebar: false,
            currentPage: 'library'
        }, 'story-test': {
            showRightSidebar: false,
            currentPage: 'library'
        }, 'lesson-learn': {
            showRightSidebar: true,
            currentPage: 'dashboard'
        }, 'lesson-speaking-test': {
            showRightSidebar: true,
            currentPage: 'dashboard'
        }, 'lesson-writing-test': {
            showRightSidebar: true,
            currentPage: 'dashboard'
        }, 'lesson-training': {
            showRightSidebar: false,
            currentPage: 'dashboard'
        }, 'lesson-test': {
            showRightSidebar: false,
            currentPage: 'dashboard'
        }
    };

    return pageConfigs[pageName] || { showRightSidebar: false, currentPage: pageName };
  }
}

// Make LayoutManager globally available
window.LayoutManager = LayoutManager;

// Auto-initialize if data-page attribute is present
document.addEventListener('DOMContentLoaded', () => {
  const pageAttribute = document.body.dataset.page;
  if (pageAttribute) {
    const config = LayoutManager.getPageConfig(pageAttribute);
    new LayoutManager(config);
  }
});
