/**
 * Forgot password form validation
 */

document.addEventListener('DOMContentLoaded', function() {
  const validator = window.formValidator;

  // Email validation
  function validateEmailField(email) {
    if (!email) {
      validator.showError('email', 'Email address is required');
      return false;
    }

    if (!validator.validateEmail(email)) {
      validator.showError('email', 'Please enter a valid email address');
      return false;
    }

    validator.clearError('email');
    return true;
  }

  // Add real-time validation
  validator.addRealTimeValidation('email', (value) => {
    validateEmailField(value);
  });

  // Handle form submission
  validator.handleFormSubmission('.forgot-password-form', () => {
    const email = document.getElementById('email').value.trim();
    const isEmailValid = validateEmailField(email);

    // If valid, show special message for forgot password
    if (isEmailValid) {
      validator.showSuccessMessage('Password reset link has been sent to your email address!');
    }

    return isEmailValid;
  });
});