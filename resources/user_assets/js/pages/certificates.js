/**
 * Certificates Page - Interactive Features
 */

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', function() {
  loadViewPreference();
  initializeViewToggle();
  initializeTestAnswersDialog();
  setupFilterDropdown();
});

// =============================================================================
// VIEW TOGGLE
// =============================================================================

/**
 * Load view preference from localStorage
 */
function loadViewPreference() {
  const savedView = localStorage.getItem('non-arab-certificate-view-'+USER_ID??'0');

  if (savedView === 'grid') {
    // Switch to grid view immediately
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const viewToggleBtn = document.getElementById('viewToggleBtn');

    if (!viewToggleBtn || !gridView || !tableView) return;

    const listIcon = viewToggleBtn.querySelector('.list-icon');
    const gridIcon = viewToggleBtn.querySelector('.grid-icon');

    tableView.classList.remove('show');
    tableView.style.display = 'none';
    gridView.style.display = 'grid';
    gridView.classList.add('show');

    viewToggleBtn.setAttribute('data-view', 'grid');
    viewToggleBtn.setAttribute('aria-label', 'Switch to table view');
    if (listIcon) listIcon.style.display = 'none';
    if (gridIcon) gridIcon.style.display = 'block';
  }
  // If savedView is 'table' or null, keep default table view
}

/**
 * Save view preference to localStorage
 */
function saveViewPreference(view) {
  localStorage.setItem('non-arab-certificate-view-'+USER_ID??'0', view);
}

/**
 * Initialize view toggle functionality between table and grid views
 */

function initializeViewToggle() {
  const viewToggleBtn = document.getElementById('viewToggleBtn');
  const gridView = document.getElementById('gridView');
  const tableView = document.getElementById('tableView');

  if (!viewToggleBtn || !gridView || !tableView) {
    console.warn('View toggle elements not found');
    return;
  }

  const listIcon = viewToggleBtn.querySelector('.list-icon');
  const gridIcon = viewToggleBtn.querySelector('.grid-icon');
  const tableIcon = viewToggleBtn.querySelector('.table-icon');

  // Toggle between grid and table views
  viewToggleBtn.addEventListener('click', function() {
    const isTableView = tableView.style.display !== 'none';

    if (isTableView) {
      // Switch to grid view
      tableView.classList.remove('show');

      setTimeout(() => {
        tableView.style.display = 'none';
        gridView.style.display = 'grid';
        gridView.offsetHeight; // Trigger reflow
        gridView.classList.add('show');
      }, 400);

      viewToggleBtn.setAttribute('data-view', 'grid');
      viewToggleBtn.setAttribute('aria-label', 'Switch to table view');
      if (listIcon) listIcon.style.display = 'none';
      if (tableIcon) tableIcon.style.display = 'none';
      if (gridIcon) gridIcon.style.display = 'block';

      // Save preference
      saveViewPreference('grid');
    } else {
      // Switch to table view
      gridView.classList.remove('show');

      setTimeout(() => {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        tableView.offsetHeight; // Trigger reflow
        tableView.classList.add('show');
      }, 400);

      viewToggleBtn.setAttribute('data-view', 'table');
      viewToggleBtn.setAttribute('aria-label', 'Switch to grid view');
      if (listIcon) listIcon.style.display = 'block';
      if (tableIcon) tableIcon.style.display = 'block';
      if (gridIcon) gridIcon.style.display = 'none';

      // Save preference
      saveViewPreference('table');
    }
  });
}

// =============================================================================
// ACTION MENU
// =============================================================================

/**
 * Toggle action menu for table rows
 */
function toggleActionMenu(event, button) {
  event.stopPropagation();
  const menu = button.nextElementSibling;
  const isVisible = menu.style.display === 'flex';

  // Close all other menus
  document.querySelectorAll('.action-menu').forEach(m => {
    m.style.display = 'none';
    m.classList.remove('position-top');
  });

  // Toggle current menu
  if (!isVisible) {
    menu.style.display = 'flex';
    menu.style.flexDirection = 'column';
    menu.style.gap = '12px';

    // Check if menu would overflow at bottom
    setTimeout(() => {
      const menuRect = menu.getBoundingClientRect();
      const viewportHeight = window.innerHeight;
      const spaceBelow = viewportHeight - menuRect.bottom;

      // If not enough space below (less than 20px), position menu above
      if (spaceBelow < 20) {
        menu.classList.add('position-top');
      }
    }, 0);
  }
}

/**
 * Setup filter dropdown functionality
 */
function setupFilterDropdown() {
    const filterDropdown = document.getElementById('certificatesFilter');

    if (filterDropdown) {
        filterDropdown.addEventListener('change', function(event) {
            window.location.href = this.value;
        });
    }
}

// Close menus when clicking outside
document.addEventListener('click', function() {
  document.querySelectorAll('.action-menu').forEach(menu => {
    menu.style.display = 'none';
  });
});



/**
 * Preview certificate
 */
function previewCertificate(url) {
    window.open(url, '_blank');
}

// =============================================================================
// TEST ANSWERS DIALOG
// =============================================================================

/**
 * Initialize test answers dialog
 */
function initializeTestAnswersDialog() {
  const dialog = document.getElementById('testAnswersDialog');

  if (!dialog) {
    console.warn('Test answers dialog not found');
    return;
  }

  // Close dialog when clicking outside the dialog content
  dialog.addEventListener('click', function(e) {
    if (e.target === dialog) {
      closeTestAnswersDialog();
    }
  });

  // Close dialog on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && dialog.style.display !== 'none') {
      closeTestAnswersDialog();
    }
  });
}

/**
 * Open test answers dialog
 */
function openTestAnswersDialog(lessonName, testAnswers, status, testInfo) {
  const dialog = document.getElementById('testAnswersDialog');
  const tableBody = document.getElementById('testAnswersTableBody');
  const statusBadge = document.getElementById('testStatusBadge');
  const lessonNameElement = document.getElementById('testLessonName');
  const testScore = document.getElementById('testScore');
  const testLevel = document.getElementById('testLevel');
  const testGrade = document.getElementById('testGrade');
  const actionMenu = document.getElementById('actionMenu');

  if (!dialog || !tableBody) {
    console.warn('Dialog or table body not found');
    return;
  }

  if(actionMenu) {
    actionMenu.style.display = 'none'
  }

  // Update lesson name
  if (lessonNameElement) {
    lessonNameElement.textContent = lessonName;
  }

  // Update status badge
  statusBadge.textContent = status;
  statusBadge.className = 'test-status-badge ' + (status === 'Pass' || status === 'ناجح' ? 'success' : 'failed');

  // Update test info
  if (testInfo) {
    if (testScore) testScore.textContent = testInfo.score;
    if (testLevel) testLevel.textContent = testInfo.level;
    if (testGrade) testGrade.textContent = testInfo.grade;
  }

  // Populate table with test answers
  tableBody.innerHTML = '';
  testAnswers.forEach((answer, index) => {
    const row = document.createElement('tr');
    const isCorrect = answer.userAnswer === answer.correctAnswer;

    row.innerHTML = `
      <td>${index + 1}</td>
      <td>${answer.question}</td>
      <td>${answer.questionType}</td>
      <td>${answer.userAnswer}</td>
      <td>${answer.correctAnswer}</td>
      <td><span class="answer-status ${isCorrect ? 'correct' : 'incorrect'}">${isCorrect ? 'صحيحة' : 'خاطئة'}</span></td>
    `;

    tableBody.appendChild(row);
  });

  // Show dialog
  dialog.style.display = 'flex';
  document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

/**
 * Close test answers dialog
 */
function closeTestAnswersDialog() {
  const dialog = document.getElementById('testAnswersDialog');
  const tableBody = document.getElementById('testAnswersTableBody');

  if (!dialog) return;

  // Hide dialog
  dialog.style.display = 'none';
  document.body.style.overflow = ''; // Restore scrolling

  // Clear table content
  if (tableBody) {
    tableBody.innerHTML = '';
  }
}

/**
 * Check answers - Display test answers dialog
 */
function checkAnswers(url) {
  console.log('Fetching answers from:', url);

  // Show loading dialog
  showLoadingDialog();

  // Make AJAX request to get test answers using jQuery
  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    },
    success: function(data) {
      // Hide loading dialog
      hideLoadingDialog();

      if (data.success && data.data) {
        // Map the answers data
        const testAnswers = data.data.answers.map(function(item) {
          return {
            question: item.question,
            questionType: item.questionType,
            userAnswer: item.userAnswer,
            correctAnswer: item.correctAnswer
          };
        });

        // Get lesson name and status from test_info
        const lessonName = data.data.test_info.name;
        const status = data.data.test_info.status_name;
        const testInfo = {
          score: data.data.test_info.score,
          level: data.data.test_info.level,
          grade: data.data.test_info.grade
        };

        openTestAnswersDialog(lessonName, testAnswers, status, testInfo);
      } else {
        console.error('Error:', data.message || 'Unknown error');
        alert(data.message || 'Failed to load test answers. Please try again.');
      }
    },
    error: function(xhr, status, error) {
      // Hide loading dialog on error
      hideLoadingDialog();

      console.error('Error fetching answers:', error);

      // Try to get error message from response
      let errorMessage = 'An error occurred while loading test answers. Please try again.';
      if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
      }

      alert(errorMessage);
    }
  });
}
