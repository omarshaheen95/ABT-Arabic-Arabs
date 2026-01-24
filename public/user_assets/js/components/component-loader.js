// Component Loader
// Loads shared static HTML components and injects them into pages

class ComponentLoader {
  constructor() {
    this.componentsLoaded = false;
    this.componentsHTML = null;
  }

  // async loadComponents() {
  //   if (this.componentsLoaded && this.componentsHTML) {
  //     return this.componentsHTML;
  //   }
  //
  //   try {
  //     const response = await fetch('../components/shared-components.html');
  //     if (!response.ok) {
  //       throw new Error(`Failed to load components: ${response.statusText}`);
  //     }
  //
  //     this.componentsHTML = await response.text();
  //     this.componentsLoaded = true;
  //     return this.componentsHTML;
  //   } catch (error) {
  //     console.error('Error loading shared components:', error);
  //     return null;
  //   }
  // }

  async injectComponent(templateId, targetSelector) {
    // Load components if not already loaded
    // if (!this.componentsHTML) {
    //   await this.loadComponents();
    // }

    // if (!this.componentsHTML) {
    //   console.error('Components HTML not loaded');
    //   return false;
    // }

    // Create a temporary container to parse the HTML
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = this.componentsHTML;

    // Find the template
    const template = tempContainer.querySelector(`#${templateId}`);
    if (!template) {
      console.error(`Template ${templateId} not found`);
      return false;
    }

    // Find the target element
    const targetElement = document.querySelector(targetSelector);
    if (!targetElement) {
      console.error(`Target element ${targetSelector} not found`);
      return false;
    }

    // Clone the template content
    const content = template.content.cloneNode(true);

    // Replace the entire target element with the template content
    // Get the first element from the template (the actual component)
    const firstElement = content.firstElementChild;
    if (firstElement) {
      // Replace the placeholder element with the new element
      targetElement.parentNode.replaceChild(content, targetElement);
    } else {
      // Fallback: just replace innerHTML if no element found
      targetElement.innerHTML = '';
      targetElement.appendChild(content);
    }

    return true;
  }

  async injectAllComponents() {
    // await this.loadComponents();

    // Inject navbar if element exists
    const navbarElement = document.querySelector('.navbar');
    if (navbarElement) {
      await this.injectComponent('navbar-template', '.navbar');
    }

    // Inject sidenav if element exists (check both class names)
    const sidenavElement = document.querySelector('.sidenav') || document.querySelector('.side-nav');
    if (sidenavElement) {
      const selector = sidenavElement.classList.contains('sidenav') ? '.sidenav' : '.side-nav';
      await this.injectComponent('sidenav-template', selector);
    }

    // Inject rightsidebar if element exists (check both class names)
    const rightsidebarElement = document.querySelector('.rightsidebar') || document.querySelector('.right-sidebar');
    if (rightsidebarElement) {
      const selector = rightsidebarElement.classList.contains('rightsidebar') ? '.rightsidebar' : '.right-sidebar';
      await this.injectComponent('rightsidebar-template', selector);
    }

    // Initialize interactive features after injection
    this.initializeInteractivity();
  }

  initializeInteractivity() {
    // Navbar dropdowns
    this.initNavbarDropdowns();

    // Side navigation
    this.initSideNavigation();

    // Right sidebar
    this.initRightSidebar();

    // Mobile menu
    this.initMobileMenu();
  }

  initNavbarDropdowns() {
    const notificationBtn = document.getElementById('notification-btn');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const markAllRead = document.getElementById('mark-all-read');
    const profileAvatarBtn = document.getElementById('profile-avatar-btn');
    const profileDropdown = document.getElementById('profile-dropdown');
    const viewProfileBtn = document.getElementById('view-profile-btn');
    const streakStatBtn = document.getElementById('streak-stat-btn');
    const streakDropdown = document.getElementById('streak-dropdown');

    // Notification dropdown toggle
    if (notificationBtn && notificationsDropdown) {
      notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationsDropdown.classList.toggle('active');
        if (profileDropdown) profileDropdown.classList.remove('active');
        if (streakDropdown) streakDropdown.classList.remove('active');
      });
    }

    // Profile dropdown toggle
    if (profileAvatarBtn && profileDropdown) {
      profileAvatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
        if (notificationsDropdown) notificationsDropdown.classList.remove('active');
        if (streakDropdown) streakDropdown.classList.remove('active');
      });
    }

    // Streak dropdown toggle
    if (streakStatBtn && streakDropdown) {
      streakStatBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        streakDropdown.classList.toggle('active');
        if (notificationsDropdown) notificationsDropdown.classList.remove('active');
        if (profileDropdown) profileDropdown.classList.remove('active');
      });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (notificationBtn && notificationsDropdown &&
          !notificationBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
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

    // Mark all notifications as read
    if (markAllRead) {
      markAllRead.addEventListener('click', () => {
        const notifications = document.querySelectorAll('.notification-item.unread');
        notifications.forEach(notification => {
          notification.classList.remove('unread');
          notification.classList.add('read');
          const indicator = notification.querySelector('.unread-indicator');
          if (indicator) indicator.remove();
        });

        const badgeNumber = document.querySelector('.badge-number');
        if (badgeNumber) badgeNumber.textContent = '0';
      });
    }

    // Mark individual notification as read
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
      item.addEventListener('click', () => {
        if (item.classList.contains('unread')) {
          item.classList.remove('unread');
          item.classList.add('read');
          const indicator = item.querySelector('.unread-indicator');
          if (indicator) indicator.remove();

          // Update badge count
          const badgeNumber = document.querySelector('.badge-number');
          if (badgeNumber) {
            const currentCount = parseInt(badgeNumber.textContent) || 0;
            badgeNumber.textContent = Math.max(0, currentCount - 1);
          }
        }
      });
    });

    // View profile button
    if (viewProfileBtn) {
      viewProfileBtn.addEventListener('click', () => {
        window.location.href = PROFILE_URL;
      });
    }
  }

  initSideNavigation() {
    // Set active page based on current URL
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {
      const href = item.getAttribute('href');
      if (currentPath.includes(href)) {
        item.classList.add('active');
      }
    });

    // Logout modal functionality
    const logoutItem = document.querySelector('.nav-item[data-page="logout"]');
    const logoutModal = document.getElementById('logout-modal');
    const logoutCancel = document.getElementById('logout-cancel');
    const logoutConfirm = document.getElementById('logout-confirm');

    if (logoutItem && logoutModal) {
      logoutItem.addEventListener('click', (e) => {
        e.preventDefault();
        logoutModal.classList.add('active');
        logoutModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      });
    }

    if (logoutCancel) {
      logoutCancel.addEventListener('click', () => {
        logoutModal.classList.remove('active');
        logoutModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      });
    }

    if (logoutConfirm) {
      logoutConfirm.addEventListener('click', () => {
        window.location.href = '../pages/login.html';
      });
    }

    // Close modal when clicking outside
    if (logoutModal) {
      logoutModal.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
          logoutModal.classList.remove('active');
          logoutModal.setAttribute('aria-hidden', 'true');
          document.body.style.overflow = '';
        }
      });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && logoutModal && logoutModal.classList.contains('active')) {
        logoutModal.classList.remove('active');
        logoutModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });
  }

  initRightSidebar() {
    // Profile dropdown button
    const dropdownBtn = document.querySelector('.dropdown-btn');
    if (dropdownBtn) {
      dropdownBtn.addEventListener('click', () => {
        window.location.href = PROFILE_URL;
      });
    }

    // Set progress bar width dynamically
    const levelProgressBar = document.querySelector('.level-progress-bar');
    if (levelProgressBar) {
      const currentXp = parseInt(levelProgressBar.getAttribute('aria-valuenow')) || 0;
      const maxXp = parseInt(levelProgressBar.getAttribute('aria-valuemax')) || 1;
      const percentage = (currentXp / maxXp) * 100;
      levelProgressBar.style.width = `${percentage}%`;
    }
  }

  initMobileMenu() {
    // Mobile menu button
    const menuBtn = document.getElementById('mobile-menu-btn');
    const sideNav = document.querySelector('.side-nav') || document.querySelector('.sidenav');

    if (menuBtn && sideNav) {
      menuBtn.addEventListener('click', () => {
        this.toggleMobileMenu();
      });
    }

    // Create backdrop if it doesn't exist
    if (!document.getElementById('drawer-backdrop')) {
      const backdrop = document.createElement('div');
      backdrop.id = 'drawer-backdrop';
      backdrop.className = 'drawer-backdrop';
      document.body.appendChild(backdrop);

      // Backdrop click to close
      backdrop.addEventListener('click', () => {
        this.closeMobileMenu();
      });
    }

    // Close drawer when clicking nav items on mobile
    const navItems = sideNav.querySelectorAll('.nav-item');
    navItems.forEach(item => {
      item.addEventListener('click', () => {
        if (window.innerWidth <= 1150) {
          setTimeout(() => this.closeMobileMenu(), 100);
        }
      });
    });

    // Handle window resize
    window.addEventListener('resize', () => {
      if (window.innerWidth > 1150) {
        this.closeMobileMenu();
      }
    });
  }

  toggleMobileMenu() {
    const sideNav = document.querySelector('.side-nav') || document.querySelector('.sidenav');
    const backdrop = document.getElementById('drawer-backdrop');

    if (sideNav && sideNav.classList.contains('active')) {
      this.closeMobileMenu();
    } else {
      this.openMobileMenu();
    }
  }

  openMobileMenu() {
    const sideNav = document.querySelector('.side-nav') || document.querySelector('.sidenav');
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

  closeMobileMenu() {
    const sideNav = document.querySelector('.side-nav') || document.querySelector('.sidenav');
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
}

// Initialize component loader when DOM is ready
window.componentLoader = new ComponentLoader();

// Auto-load components when DOM is ready
document.addEventListener('DOMContentLoaded', async () => {
  await window.componentLoader.injectAllComponents();
});
