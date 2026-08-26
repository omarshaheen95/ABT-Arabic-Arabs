<!-- Message Dialog -->
<div class="message-dialog-overlay" id="messageDialog" style="display: none;">
    <div class="message-dialog">
        <div class="message-content">
            <div class="message-icon" id="messageIcon">
                <!-- Success Icon -->
                <svg class="icon-success" viewBox="0 0 50 50" style="display: none;">
                    <circle class="success-circle" cx="25" cy="25" r="20" fill="none" stroke-width="3"></circle>
                    <path class="success-check" d="M15 25 L22 32 L35 18" fill="none" stroke-width="3"></path>
                </svg>
                <!-- Error Icon -->
                <svg class="icon-error" viewBox="0 0 50 50" style="display: none;">
                    <circle class="error-circle" cx="25" cy="25" r="20" fill="none" stroke-width="3"></circle>
                    <path class="error-cross" d="M18 18 L32 32 M32 18 L18 32" stroke-width="3"></path>
                </svg>
                <!-- Warning Icon -->
                <svg class="icon-warning" viewBox="0 0 50 50" style="display: none;">
                    <circle class="warning-circle" cx="25" cy="25" r="20" fill="none" stroke-width="3"></circle>
                    <path class="warning-mark" d="M25 15 L25 28 M25 33 L25 35" stroke-width="3" stroke-linecap="round"></path>
                </svg>
            </div>
            <h3 class="message-title" id="messageTitle">Message</h3>
            <p class="message-text" id="messageText"></p>
            <button class="message-btn" id="messageBtn" onclick="closeMessageDialog()">{{t('OK')}}</button>
        </div>
    </div>
</div>

@push('style')
    <style>
        .message-dialog-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease-in-out;
        }

        .message-dialog {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.3s ease-out;
        }

        .message-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .message-icon {
            width: 60px;
            height: 60px;
        }

        .message-icon svg {
            width: 100%;
            height: 100%;
        }

        /* Success Icon Styles */
        .icon-success .success-circle {
            stroke: #10B981;
            stroke-dasharray: 126;
            stroke-dashoffset: 126;
            animation: drawCircle 0.6s ease-out forwards;
        }

        .icon-success .success-check {
            stroke: #10B981;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 30;
            stroke-dashoffset: 30;
            animation: drawCheck 0.4s ease-out 0.3s forwards;
        }

        /* Error Icon Styles */
        .icon-error .error-circle {
            stroke: #EF4444;
            stroke-dasharray: 126;
            stroke-dashoffset: 126;
            animation: drawCircle 0.6s ease-out forwards;
        }

        .icon-error .error-cross {
            stroke: #EF4444;
            stroke-linecap: round;
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: drawCheck 0.4s ease-out 0.3s forwards;
        }

        /* Warning Icon Styles */
        .icon-warning .warning-circle {
            stroke: #F59E0B;
            stroke-dasharray: 126;
            stroke-dashoffset: 126;
            animation: drawCircle 0.6s ease-out forwards;
        }

        .icon-warning .warning-mark {
            stroke: #F59E0B;
            stroke-dasharray: 20;
            stroke-dashoffset: 20;
            animation: drawCheck 0.4s ease-out 0.3s forwards;
        }

        @keyframes drawCircle {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }

        .message-title {
            font-size: 24px;
            font-weight: 700;
            color: #1F2937;
            margin: 0;
        }

        .message-text {
            font-size: 16px;
            color: #6B7280;
            line-height: 1.6;
            margin: 0;
        }

        .message-btn {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .message-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .message-btn:active {
            transform: translateY(0);
        }

        /* Success variant */
        .message-dialog.success .message-btn {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .message-dialog.success .message-btn:hover {
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        /* Error variant */
        .message-dialog.error .message-btn {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .message-dialog.error .message-btn:hover {
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        /* Warning variant */
        .message-dialog.warning .message-btn {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .message-dialog.warning .message-btn:hover {
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        /**
         * Show message dialog
         * @param {string} message - The message to display
         * @param {string} type - Type of message: 'success', 'error', 'warning', 'info'
         * @param {string} title - Optional title (default based on type)
         */
        function showMessageDialog(message, type = 'info', title = null) {
            const dialog = document.getElementById('messageDialog');
            const messageText = document.getElementById('messageText');
            const messageTitle = document.getElementById('messageTitle');
            const messageDialogEl = dialog.querySelector('.message-dialog');
            const iconContainer = document.getElementById('messageIcon');

            // Set message text
            messageText.textContent = message;

            // Set title based on type if not provided
            if (!title) {
                switch(type) {
                    case 'success':
                        title = '{{t("Success")}}';
                        break;
                    case 'error':
                        title = '{{t("Error")}}';
                        break;
                    case 'warning':
                        title = '{{t("Warning")}}';
                        break;
                    default:
                        title = '{{t("Message")}}';
                }
            }
            messageTitle.textContent = title;

            // Reset all icons
            iconContainer.querySelectorAll('svg').forEach(svg => svg.style.display = 'none');

            // Remove all type classes
            messageDialogEl.classList.remove('success', 'error', 'warning', 'info');

            // Add type class and show appropriate icon
            messageDialogEl.classList.add(type);
            switch(type) {
                case 'success':
                    iconContainer.querySelector('.icon-success').style.display = 'block';
                    break;
                case 'error':
                    iconContainer.querySelector('.icon-error').style.display = 'block';
                    break;
                case 'warning':
                    iconContainer.querySelector('.icon-warning').style.display = 'block';
                    break;
            }

            // Show dialog
            dialog.style.display = 'flex';
        }

        /**
         * Close message dialog
         */
        function closeMessageDialog() {
            const dialog = document.getElementById('messageDialog');
            dialog.style.display = 'none';
        }

        // Close dialog on overlay click
        document.addEventListener('DOMContentLoaded', function() {
            const messageDialog = document.getElementById('messageDialog');
            if (messageDialog) {
                messageDialog.addEventListener('click', function(e) {
                    if (e.target === messageDialog) {
                        closeMessageDialog();
                    }
                });
            }
        });
    </script>
@endpush
