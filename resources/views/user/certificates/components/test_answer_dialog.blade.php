<!-- Test Answers Dialog -->
<div class="test-answers-dialog-overlay" id="testAnswersDialog" style="display: none;">
    <div class="test-answers-dialog">
        <header class="dialog-header">
            <div class="header-left">
                <h2 class="dialog-title">إجابات الاختبار</h2>
                <p class="dialog-subtitle" id="testLessonName"></p>
            </div>
            <div class="dialog-actions">
                <button class="close-dialog-btn" onclick="closeTestAnswersDialog()" aria-label="Close dialog">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Test Info Section -->
        <div class="test-info-section">
            <div class="test-info-item">
                <span class="test-info-label">الحالة:</span>
                <span class="test-status-badge" id="testStatusBadge">ناجح</span>
            </div>
            <div class="test-info-item">
                <span class="test-info-label">الدرجة:</span>
                <span class="test-info-value" id="testScore">0%</span>
            </div>
            <div class="test-info-item">
                <span class="test-info-label">المستوى:</span>
                <span class="test-info-value" id="testLevel">-</span>
            </div>
        </div>

        <div class="dialog-content">
            <div class="test-answers-table-container">
                <div class="certificates-table-wraper">
                    <table class="test-answers-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>السؤال</th>
                            <th>نوع السؤال</th>
                            <th>إجابتك</th>
                            <th>الإجابة الصحيحة</th>
                            <th>الحالة</th>
                        </tr>
                        </thead>
                        <tbody id="testAnswersTableBody">
                        <!-- Answers will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .dialog-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 4px 0 0 0;
        }

        .close-dialog-btn {
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            min-width: 40px;
            min-height: 40px;
        }

        .close-dialog-btn:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .close-dialog-btn svg {
            width: 24px;
            height: 24px;
        }

        /* Responsive close button */
        @media (max-width: 760px) {
            .close-dialog-btn {
                padding: 6px;
                min-width: 36px;
                min-height: 36px;
            }

            .close-dialog-btn svg {
                width: 20px;
                height: 20px;
            }
        }

        @media (max-width: 480px) {
            .close-dialog-btn {
                padding: 4px;
                min-width: 32px;
                min-height: 32px;
            }

            .close-dialog-btn svg {
                width: 18px;
                height: 18px;
            }
        }

        .test-info-section {
            display: flex;
            gap: 24px;
            padding: 16px 24px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            flex-wrap: wrap;
        }

        .test-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .test-info-label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
        }

        .test-info-value {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        .test-status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .test-status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .test-status-badge.failed {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endpush
