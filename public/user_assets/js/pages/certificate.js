/**
 * Certificate Dynamic Rendering
 * Handles dynamic population of certificate data
 */

// ===================================
// CONFIGURE CERTIFICATE DATA HERE
// ===================================
const certificateData = {
  name: 'Hisham',
  course: 'My Manners - أخلاقي',
  level: 'Level B+ - Third and Fourth Years of learning Arabic',
  percentage: 100,
  date: '03-11-2025'
};
// ===================================

class CertificateManager {
  constructor(data) {
    this.certificateData = {
      name: '',
      course: '',
      level: '',
      percentage: 0,
      date: ''
    };

    this.init(data);
  }

  /**
   * Initialize certificate rendering
   */
  init(data) {
    this.loadCertificateData(data);
    this.renderCertificate();
    this.updatePageTitle();
  }

  /**
   * Load and validate certificate data
   */
  loadCertificateData(data) {
    // Validate and sanitize data
    this.certificateData = {
      name: this.sanitizeText(data.name || 'Student Name'),
      course: this.sanitizeText(data.course || 'Course Name'),
      level: this.sanitizeText(data.level || 'Level B+ - Third and Fourth Years of learning Arabic'),
      percentage: this.validatePercentage(data.percentage || 100),
      date: this.formatDate(data.date || this.getCurrentDate())
    };
  }

  /**
   * Render certificate data to the DOM
   */
  renderCertificate() {
    // Get all elements with data-field attributes
    const fields = document.querySelectorAll('[data-field]');

    fields.forEach(field => {
      const fieldName = field.getAttribute('data-field');

      if (this.certificateData.hasOwnProperty(fieldName)) {
        let value = this.certificateData[fieldName];

        // Add percentage symbol for percentage field
        if (fieldName === 'percentage') {
          value = `${value}%`;
        }

        // Capitalize name
        if (fieldName === 'name') {
          value = this.capitalizeWords(value);
        }

        field.textContent = value;
      }
    });
  }

  /**
   * Update page title with student name
   */
  updatePageTitle() {
    document.title = `Certificate - ${this.certificateData.name} - ABT LMS`;
  }

  /**
   * Get current date in DD-MM-YYYY format
   */
  getCurrentDate() {
    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    return `${day}-${month}-${year}`;
  }

  /**
   * Format date string to DD-MM-YYYY
   */
  formatDate(dateString) {
    // If already in correct format, return as is
    if (/^\d{2}-\d{2}-\d{4}$/.test(dateString)) {
      return dateString;
    }

    // Try to parse various date formats
    const date = new Date(dateString);

    if (isNaN(date.getTime())) {
      return this.getCurrentDate();
    }

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
  }

  /**
   * Validate and normalize percentage value
   */
  validatePercentage(percentage) {
    const num = parseFloat(percentage);

    if (isNaN(num) || num < 0) {
      return 0;
    }

    if (num > 100) {
      return 100;
    }

    // Round to 2 decimal places
    return Math.round(num * 100) / 100;
  }

  /**
   * Sanitize text input to prevent XSS
   */
  sanitizeText(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  /**
   * Capitalize first letter of each word
   */
  capitalizeWords(text) {
    return text
      .toLowerCase()
      .split(' ')
      .map(word => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ');
  }

  /**
   * Get certificate data (useful for testing or external access)
   */
  getCertificateData() {
    return { ...this.certificateData };
  }

  /**
   * Update certificate data dynamically
   */
  updateCertificateData(newData) {
    this.loadCertificateData(newData);
    this.renderCertificate();
    this.updatePageTitle();
  }
}

// Initialize certificate manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.certificateManager = new CertificateManager(certificateData);

  // Log certificate data for debugging (remove in production)
  console.log('Certificate Data:', window.certificateManager.getCertificateData());
});

/**
 * Example Usage:
 *
 * 1. Edit the certificateData object at the top of this file:
 *    const certificateData = {
 *      name: 'John Doe',
 *      course: 'Arabic Grammar - النحو',
 *      level: 'Level A - First Year',
 *      percentage: 95,
 *      date: '15-03-2025'
 *    };
 *
 * 2. Or update dynamically from another script:
 *    window.certificateManager.updateCertificateData({
 *      name: 'Jane Smith',
 *      course: 'Advanced Arabic',
 *      percentage: 88
 *    });
 */
