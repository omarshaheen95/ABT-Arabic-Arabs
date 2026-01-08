/**
 * Login form validation
 */

document.addEventListener('DOMContentLoaded', function() {
  const validator = window.formValidator;

  // Email validation
  function validateEmailField(email) {
    if (!email) {
      validator.showError('email', 'Email is required');
      return false;
    }

    if (!validator.validateEmail(email)) {
      validator.showError('email', 'Please enter a valid email address');
      return false;
    }

    validator.clearError('email');
    return true;
  }

  // Password validation
  function validatePasswordField(password) {
    if (!password) {
      validator.showError('password', 'Password is required');
      return false;
    }

    if (password.length < 6) {
      validator.showError('password', 'Password must be at least 6 characters');
      return false;
    }

    validator.clearError('password');
    return true;
  }

  // Add real-time validation
  validator.addRealTimeValidation('email', (value) => {
    validateEmailField(value);
  });

  validator.addRealTimeValidation('password', (value) => {
    validatePasswordField(value);
  });

  // Handle form submission
  validator.handleFormSubmission('.login-form', () => {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    const isEmailValid = validateEmailField(email);
    const isPasswordValid = validatePasswordField(password);

    return isEmailValid && isPasswordValid;
  });
});