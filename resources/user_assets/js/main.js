/**
 * Form validation utilities
 */
var BASE_URL = window.location.origin;

class FormValidator {
  constructor() {
    this.errors = {};
  }

  // Email validation
  validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Password strength validation
  validatePassword(password) {
    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    return {
      isValid: password.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers,
      minLength: password.length >= minLength,
      hasUpperCase,
      hasLowerCase,
      hasNumbers,
      hasSpecialChar
    };
  }

  // Show error message
  showError(fieldId, message) {
    const errorElement = document.getElementById(`${fieldId}-error`);
    const inputElement = document.getElementById(fieldId);

    if (errorElement) {
      errorElement.textContent = message;
      errorElement.style.display = 'block';
    }

    if (inputElement) {
      inputElement.classList.add('error');
      inputElement.setAttribute('aria-invalid', 'true');
    }

    this.errors[fieldId] = message;
  }

  // Clear error message
  clearError(fieldId) {
    const errorElement = document.getElementById(`${fieldId}-error`);
    const inputElement = document.getElementById(fieldId);

    if (errorElement) {
      errorElement.textContent = '';
      errorElement.style.display = 'none';
    }

    if (inputElement) {
      inputElement.classList.remove('error');
      inputElement.setAttribute('aria-invalid', 'false');
    }

    delete this.errors[fieldId];
  }

  // Check if form has errors
  hasErrors() {
    return Object.keys(this.errors).length > 0;
  }

  // Add real-time validation to input
  addRealTimeValidation(fieldId, validator) {
    const input = document.getElementById(fieldId);
    if (!input) return;

    // Validate on blur (when user leaves field)
    input.addEventListener('blur', () => {
      const value = input.value.trim();
      if (value) {
        validator(value);
      }
    });

    // Clear error on input (when user starts typing)
    input.addEventListener('input', () => {
      if (this.errors[fieldId]) {
        this.clearError(fieldId);
      }
    });
  }

  // Generic form submission handler
  handleFormSubmission(formSelector, validator) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();

      // Clear all previous errors
      Object.keys(this.errors).forEach(fieldId => this.clearError(fieldId));

      // Run validation
      const isValid = validator();

      if (isValid && !this.hasErrors()) {
        // Form is valid, you can submit here
        console.log('Form is valid, ready to submit');
        this.showSuccessMessage('Form submitted successfully!');
        // form.submit(); // Uncomment to actually submit
      } else {
        // Focus on first error field
        const firstErrorField = Object.keys(this.errors)[0];
        if (firstErrorField) {
          document.getElementById(firstErrorField)?.focus();
        }
      }
    });
  }

  // Show success message
  showSuccessMessage(message) {
    // Create or update success message element
    let successElement = document.querySelector('.success-message');
    if (!successElement) {
      successElement = document.createElement('div');
      successElement.className = 'success-message';
      successElement.setAttribute('role', 'alert');
      successElement.setAttribute('aria-live', 'polite');

      // Insert after form title
      const formTitle = document.querySelector('.form-title');
      if (formTitle) {
        formTitle.parentNode.insertBefore(successElement, formTitle.nextSibling);
      }
    }

    successElement.textContent = message;
    successElement.style.display = 'block';

    // Auto-hide after 5 seconds
    setTimeout(() => {
      successElement.style.display = 'none';
    }, 5000);
  }
}

// Global validator instance
window.formValidator = new FormValidator();

$(document).ready(function () {
    'use strict';
    let host = window.location.hostname

    if (host!=='127.0.0.1' && host!=='nonArab.test'){
        // تعطيل الزر الأيمن
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('keydown', function (e) {
            // تعطيل مفتاح F12
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
            }
            // تعطيل النسخ باستخدام CTRL+C
            if (e.ctrlKey && e.key === 'c') {
                e.preventDefault();
            }
            // تعطيل CTRL+U
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
            }
        });

        // تعطيل النسخ عن طريق التحديد
        document.addEventListener('copy', function (e) {
            e.preventDefault();
        });

        // تعطيل تحديد النص
        document.addEventListener('selectstart', function (e) {
            e.preventDefault();
        });
    }

});
