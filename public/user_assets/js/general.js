/**
 * Show message notification
 */
function showMessage(message, type = 'success') {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.custom-message-notification');
    existingMessages.forEach(msg => msg.remove());

    // Create message container
    const messageContainer = document.createElement('div');
    messageContainer.className = 'custom-message-notification';
    messageContainer.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'success' ? '#4CAF50' : '#f44336'};
    color: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10000;
    min-width: 300px;
    max-width: 500px;
    animation: slideIn 0.3s ease-out;
  `;

    // Add keyframes for slide-in animation
    if (!document.getElementById('messageAnimation')) {
        const style = document.createElement('style');
        style.id = 'messageAnimation';
        style.innerHTML = `
      @keyframes slideIn {
        from {
          transform: translateX(400px);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
      @keyframes slideOut {
        from {
          transform: translateX(0);
          opacity: 1;
        }
        to {
          transform: translateX(400px);
          opacity: 0;
        }
      }
    `;
        document.head.appendChild(style);
    }

    // Create icon
    const icon = document.createElement('span');
    icon.innerHTML = type === 'success' ? '✓' : '✕';
    icon.style.cssText = `
    font-size: 20px;
    font-weight: bold;
    margin-right: 10px;
  `;

    // Create message text
    const messageText = document.createElement('span');
    messageText.innerHTML = message;
    messageText.style.cssText = `
    font-size: 14px;
    line-height: 1.5;
  `;

    messageContainer.appendChild(icon);
    messageContainer.appendChild(messageText);
    document.body.appendChild(messageContainer);

    // Auto remove after 5 seconds
    setTimeout(() => {
        messageContainer.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            messageContainer.remove();
        }, 300);
    }, 5000);
}
