/**
 * Homework Page - Interactive Features
 */

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', function() {
  initializeHomework();
});

function initializeHomework() {
  loadViewPreference();
  setupViewToggle();
  setupFilterDropdown();
  startTimerUpdates();
}

// =============================================================================
// VIEW TOGGLE
// =============================================================================

/**
 * Load view preference from localStorage
 */
function loadViewPreference() {
  const savedView = localStorage.getItem('non-arab-homework-view-'+USER_ID??'0');

  if (savedView === 'table') {
    // Switch to table view immediately
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleBtn = document.getElementById('viewToggleBtn');
    const gridIcon = toggleBtn.querySelector('.grid-icon');
    const tableIcon = toggleBtn.querySelector('.table-icon');

    gridView.classList.remove('show');
    gridView.style.display = 'none';
    tableView.style.display = 'block';
    tableView.classList.add('show');

    gridIcon.style.display = 'none';
    tableIcon.style.display = 'block';
    toggleBtn.setAttribute('aria-label', 'Switch to grid view');
  }
  // If savedView is 'grid' or null, keep default grid view
}

/**
 * Save view preference to localStorage
 */
function saveViewPreference(view) {
  localStorage.setItem('non-arab-homework-view-'+USER_ID??'0', view);
}

/**
 * Setup view toggle functionality (Grid ↔ Table)
 */
function setupViewToggle() {
  const toggleBtn = document.getElementById('viewToggleBtn');
  const gridView = document.getElementById('gridView');
  const tableView = document.getElementById('tableView');
  const gridIcon = toggleBtn.querySelector('.grid-icon');
  const tableIcon = toggleBtn.querySelector('.table-icon');

  toggleBtn.addEventListener('click', () => {
    const isGridView = gridView.style.display !== 'none';

    if (isGridView) {
      // Switch to table view
      gridView.classList.remove('show');

      setTimeout(() => {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        tableView.offsetHeight; // Trigger reflow
        tableView.classList.add('show');
      }, 400);

      gridIcon.style.display = 'none';
      tableIcon.style.display = 'block';
      toggleBtn.setAttribute('aria-label', 'Switch to grid view');

      // Save preference
      saveViewPreference('table');
    } else {
      // Switch to grid view
      tableView.classList.remove('show');

      setTimeout(() => {
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        gridView.offsetHeight; // Trigger reflow
        gridView.classList.add('show');
      }, 400);

      gridIcon.style.display = 'block';
      tableIcon.style.display = 'none';
      toggleBtn.setAttribute('aria-label', 'Switch to table view');

      // Save preference
      saveViewPreference('grid');
    }
  });
}

// =============================================================================
// FILTER DROPDOWN
// =============================================================================

/**
 * Setup filter dropdown functionality
 */
function setupFilterDropdown() {
  const filterDropdown = document.getElementById('homeworkFilter');

  if (filterDropdown) {
    filterDropdown.addEventListener('change', function(event) {
      window.location.href = this.value;
    });
  }
}

// =============================================================================
// TIMER FUNCTIONALITY
// =============================================================================

/**
 * Start timer updates
 */
function startTimerUpdates() {
  setInterval(() => {
    updateAllTimers();
  }, 1000);
}

/**
 * Update all visible timers
 */
function updateAllTimers() {
  const timers = document.querySelectorAll('[data-deadline]');

  timers.forEach(timer => {
    const deadline = timer.getAttribute('data-deadline');
    if (!deadline) return;

    const timerText = timer.querySelector('.timer-text');
    if (timerText) {
      const timeRemaining = calculateTimeRemaining(deadline);
      timerText.textContent = timeRemaining.display;

      // Change color when time is running out (less than 1 day)
      if (timeRemaining.totalHours < 24 && timeRemaining.totalHours > 0) {
        timerText.style.background = 'linear-gradient(0deg, #FF6B6B 0%, #FF4757 100%)';
        timerText.style.webkitBackgroundClip = 'text';
        timerText.style.webkitTextFillColor = 'transparent';
        timerText.style.backgroundClip = 'text';
      } else if (timeRemaining.totalHours <= 0) {
        timerText.textContent = 'انتهى الوقت';
        timerText.style.background = 'linear-gradient(0deg, #95A5A6 0%, #7F8C8D 100%)';
        timerText.style.webkitBackgroundClip = 'text';
        timerText.style.webkitTextFillColor = 'transparent';
        timerText.style.backgroundClip = 'text';
      }
    }
  });
}

/**
 * Calculate time remaining until deadline
 */
function calculateTimeRemaining(deadline) {
  const now = new Date();
  const deadlineDate = new Date(deadline);
  const diff = deadlineDate - now;

  if (diff <= 0) {
    return {
      display: 'انتهى الوقت',
      totalHours: 0
    };
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((diff % (1000 * 60)) / 1000);

  const totalHours = days * 24 + hours;

  // Format display based on time remaining
  let display;
  if (days > 0) {
    display = `${days}d ${hours}h ${minutes}m`;
  } else if (hours > 0) {
    display = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
  } else {
    display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
  }

  return {
    display,
    totalHours
  };
}

// =============================================================================
// ACTION MENU
// =============================================================================

/**
 * Toggle action menu in table view
 */
function toggleActionMenu(event, assignmentId) {
  event.stopPropagation();

  // Close all other menus
  document.querySelectorAll('.action-menu').forEach(menu => {
    if (menu.id !== `actionMenu-${assignmentId}`) {
      menu.classList.remove('show');
    }
  });

  // Toggle current menu
  const menu = document.getElementById(`actionMenu-${assignmentId}`);
  menu.classList.toggle('show');
}

// Close menu when clicking outside
document.addEventListener('click', (event) => {
  if (!event.target.closest('.action-menu-wrapper')) {
    document.querySelectorAll('.action-menu').forEach(menu => {
      menu.classList.remove('show');
    });
  }
});

// =============================================================================
// ACTION HANDLERS
// =============================================================================

/**
 * Navigate to test page for assignment
 */
function goToTest(url) {
  // Close the menu
  document.querySelectorAll('.action-menu').forEach(menu => {
    menu.classList.remove('show');
  });
  window.location.href = url;
}

/**
 * Navigate to tasks page for assignment
 */
function goToTasks(url) {
  // Close the menu
  document.querySelectorAll('.action-menu').forEach(menu => {
    menu.classList.remove('show');
  });
  // TODO: Add navigation logic here
  window.location.href = url;
}

// Make functions globally available for onclick handlers
window.goToTest = goToTest;
window.goToTasks = goToTasks;
window.toggleActionMenu = toggleActionMenu;
