<!-- Loading Dialog -->
<div class="loading-dialog-overlay" id="loadingDialog" style="display: none;">
    <div class="loading-dialog">
        <div class="loading-content">
            <div class="loading-spinner">
                <svg class="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="4"></circle>
                </svg>
            </div>
            <p id="loading-text" class="loading-text">جاري المعالجة...</p>
            <p class="loading-subtext">الرجاء الإنتظار...</p>
        </div>
    </div>
</div>

@push('style')
    <style>
        .loading-dialog-overlay {
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
            z-index: 9999;
            animation: fadeIn 0.3s ease-in-out;
        }

        .loading-dialog {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.3s ease-out;
        }

        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
        }

        .spinner {
            animation: rotate 2s linear infinite;
            width: 100%;
            height: 100%;
        }

        .spinner .path {
            stroke: #138944;
            stroke-linecap: round;
            animation: dash 1.5s ease-in-out infinite;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes dash {
            0% {
                stroke-dasharray: 1, 150;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -35;
            }
            100% {
                stroke-dasharray: 90, 150;
                stroke-dashoffset: -124;
            }
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

        .loading-text {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .loading-subtext {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
            max-width: 280px;
        }
    </style>
@endpush

@push('script')
    <script>
        /**
         * Show loading dialog
         */
        function showLoadingDialog(text='جاري المعالجة...') {
            const loadingDialog = document.getElementById('loadingDialog');
            const loadingText = document.getElementById('loading-text');

            if (loadingDialog) {
                loadingDialog.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            if (loadingText) {
                loadingText.textContent = text;
            }
        }
        /**
         * Hide loading dialog
         */
        function hideLoadingDialog() {
            const loadingDialog = document.getElementById('loadingDialog');
            if (loadingDialog) {
                loadingDialog.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
    </script>
@endpush
