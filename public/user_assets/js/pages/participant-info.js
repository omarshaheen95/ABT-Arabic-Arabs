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
      showLoadingDialog('تعديل كلمة المرور ...');

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
          showMessage(response.message || 'تم تعديل كلمة المرور بنجاح!', 'success');

          // Reset form
          if (form) form.reset();
        },
        error: function(xhr) {
          // Hide loading dialog
          hideLoadingDialog();

          let errorMessage = 'فشل تعديل كلمة المرور , حاول مرة اخرى.';

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
