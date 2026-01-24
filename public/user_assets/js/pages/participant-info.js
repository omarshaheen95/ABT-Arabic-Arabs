/**
 * Participant Info Page - Interactive Features
 */

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', function() {
  initEditProfileModal();
  initChangePasswordModal();
  animateLevelProgressBar();
});

// =============================================================================
// EDIT PROFILE MODAL
// =============================================================================

/**
 * Initialize Edit Profile Modal
 */
function initEditProfileModal() {
  const modal = document.getElementById('editProfileModal');
  const editBtn = document.getElementById('editProfileBtn');
  const closeBtn = document.getElementById('closeModalBtn');
  const form = document.getElementById('editProfileForm');
  const avatarEditBtn = document.getElementById('avatarEditBtn');
  const avatarInput = document.getElementById('avatarInput');
  const modalAvatarImg = document.getElementById('modalAvatarImg');

  if (!modal || !editBtn) return;

  // Handle avatar change button click
  if (avatarEditBtn && avatarInput) {
    avatarEditBtn.addEventListener('click', () => {
      avatarInput.click();
    });
  }

  // Handle avatar file selection
  if (avatarInput && modalAvatarImg) {
    avatarInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (event) => {
          const imageUrl = event.target.result;
          modalAvatarImg.src = imageUrl;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Open modal
  editBtn.addEventListener('click', () => {
    // Sync current profile data to modal
    const profileName = document.getElementById('participantName');
    const formName = document.getElementById('profileName');

    if (profileName && formName) {
      formName.value = profileName.textContent;
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  });

  // Close modal
  const closeModal = () => {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  // Close modal when clicking outside
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Handle form submission
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const name = document.getElementById('profileName').value;
      const email = document.getElementById('profileEmail').value;

      // Update the profile name in the header
      const profileNameElement = document.getElementById('participantName');
      if (profileNameElement) {
        profileNameElement.textContent = name;
      }

      // Update avatar if a new one was selected
      const newAvatarSrc = modalAvatarImg.src;
      const headerAvatar = document.querySelector('.profile-avatar .avatar-img');
      if (headerAvatar && newAvatarSrc && !newAvatarSrc.includes('user-avatar.png')) {
        headerAvatar.src = newAvatarSrc;
      }

      console.log('Profile updated:', { name, email });

      // Close modal after save
      closeModal();

      // TODO: In a real application, send this data to the backend
      // saveProfileToBackend({ name, email, avatar });
    });
  }

  // Handle escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });

  // Handle Change Password button click
  const changePasswordBtn = document.getElementById('changePasswordBtn');
  if (changePasswordBtn) {
    changePasswordBtn.addEventListener('click', (e) => {
      e.preventDefault();
      closeModal(); // Close edit profile modal
      // Open change password modal
      const changePasswordModal = document.getElementById('changePasswordModal');
      if (changePasswordModal) {
        changePasswordModal.classList.add('active');
        changePasswordModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      }
    });
  }
}

// =============================================================================
// CHANGE PASSWORD MODAL
// =============================================================================

/**
 * Initialize Change Password Modal
 */
function initChangePasswordModal() {
  const modal = document.getElementById('changePasswordModal');
  const closeBtn = document.getElementById('closeChangePasswordModalBtn');
  const form = document.getElementById('changePasswordForm');

  if (!modal) return;

  // Close modal
  const closeModal = () => {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (form) form.reset();
  };

  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  // Close modal when clicking outside
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Handle form submission
  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      // Validate passwords match
      if (newPassword !== confirmPassword) {
        showMessage('New passwords do not match!', 'error');
        return;
      }

      if (newPassword.length < 6) {
        showMessage('Password must be at least 6 characters long!', 'error');
        return;
      }

      // Show loading dialog
      showLoadingDialog('Updating password...');

      // Get CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Send AJAX request
      $.ajax({
        url: '/password/update',
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
          password: newPassword,
          password_confirmation: confirmPassword
        },
        success: function(response) {
          // Hide loading dialog
          hideLoadingDialog();

          // Close modal
          closeModal();

          // Show success message
          showMessage(response.message || 'Password changed successfully!', 'success');

          // Reset form
          if (form) form.reset();
        },
        error: function(xhr) {
          // Hide loading dialog
          hideLoadingDialog();

          let errorMessage = 'Failed to update password. Please try again.';

          if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            const errorMessages = Object.values(errors).flat();
            errorMessage = errorMessages.join('<br>');
          } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          }

          showMessage(errorMessage, 'error');
        }
      });
    });
  }

  // Handle escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });
}

// =============================================================================
// LEVEL PROGRESS BAR ANIMATION
// =============================================================================

/**
 * Animate the level progress bar
 */
function animateLevelProgressBar() {
  const levelProgressBar = document.getElementById('levelProgressBar');
  if (levelProgressBar) {
    const currentXp = parseInt(levelProgressBar.getAttribute('aria-valuenow')) || 0;
    const maxXp = parseInt(levelProgressBar.getAttribute('aria-valuemax')) || 1;
    const percentage = (currentXp / maxXp) * 100;

    // Start from 0 width
    levelProgressBar.style.width = '0%';

    // Animate to calculated percentage
    setTimeout(() => {
      levelProgressBar.style.transition = 'width 1.5s ease-out';
      levelProgressBar.style.width = `${percentage}%`;
    }, 300);
  }
}

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Show loading dialog
 */
function showLoadingDialog(message = 'Loading...') {
  // Remove existing loading dialog if any
  hideLoadingDialog();

  // Create loading overlay
  const loadingOverlay = document.createElement('div');
  loadingOverlay.id = 'loadingDialog';
  loadingOverlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  `;

  // Create loading content
  const loadingContent = document.createElement('div');
  loadingContent.style.cssText = `
    background: white;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    text-align: center;
    max-width: 300px;
  `;

  // Create spinner
  const spinner = document.createElement('div');
  spinner.style.cssText = `
    border: 4px solid #f3f3f3;
    border-top: 4px solid #1E4396;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
  `;

  // Add keyframes for spinner animation
  if (!document.getElementById('spinnerAnimation')) {
    const style = document.createElement('style');
    style.id = 'spinnerAnimation';
    style.innerHTML = `
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  }

  // Create message text
  const messageText = document.createElement('p');
  messageText.textContent = message;
  messageText.style.cssText = `
    margin: 0;
    color: #333;
    font-size: 16px;
  `;

  loadingContent.appendChild(spinner);
  loadingContent.appendChild(messageText);
  loadingOverlay.appendChild(loadingContent);
  document.body.appendChild(loadingOverlay);
}

/**
 * Hide loading dialog
 */
function hideLoadingDialog() {
  const loadingDialog = document.getElementById('loadingDialog');
  if (loadingDialog) {
    loadingDialog.remove();
  }
}

