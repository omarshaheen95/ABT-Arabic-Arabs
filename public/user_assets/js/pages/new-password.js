/**
 * New password form validation
 */

document.addEventListener('DOMContentLoaded', function() {
  const validator = window.formValidator;

  // Password validation with strength indicators
  function validatePasswordField(password) {
    if (!password) {
      validator.showError('password', 'Password is required');
      return false;
    }

    const validation = validator.validatePassword(password);

    if (!validation.isValid) {
      let errorMessage = 'Password must contain:';
      if (!validation.minLength) errorMessage += ' at least 8 characters,';
      if (!validation.hasUpperCase) errorMessage += ' uppercase letter,';
      if (!validation.hasLowerCase) errorMessage += ' lowercase letter,';
      if (!validation.hasNumbers) errorMessage += ' number,';

      // Remove trailing comma and add period
      errorMessage = errorMessage.replace(/,$/, '');

      validator.showError('password', errorMessage);
      return false;
    }

    validator.clearError('password');
    return true;
  }

  // Password confirmation validation
  function validatePasswordConfirmation(password, confirmation) {
    if (!confirmation) {
      validator.showError('password-confirmation', 'Password confirmation is required');
      return false;
    }

    if (password !== confirmation) {
      validator.showError('password-confirmation', 'Passwords do not match');
      return false;
    }

    validator.clearError('password-confirmation');
    return true;
  }

  // Add real-time validation
  validator.addRealTimeValidation('password', (value) => {
    validatePasswordField(value);

    // Also revalidate confirmation if it has a value
    const confirmation = document.getElementById('password-confirmation').value;
    if (confirmation) {
      validatePasswordConfirmation(value, confirmation);
    }
  });

  validator.addRealTimeValidation('password-confirmation', (value) => {
    const password = document.getElementById('password').value;
    validatePasswordConfirmation(password, value);
  });

  // Handle form submission
  validator.handleFormSubmission('.password-form', () => {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password-confirmation').value;

    const isPasswordValid = validatePasswordField(password);
    const isConfirmationValid = validatePasswordConfirmation(password, confirmation);

    // If valid, show success message
    if (isPasswordValid && isConfirmationValid) {
      validator.showSuccessMessage('Password has been updated successfully!');
    }

    return isPasswordValid && isConfirmationValid;
  });
});