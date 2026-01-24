@extends('layouts.container_2')

@section('p_style')
    <link rel="stylesheet" href="{{ asset('web_assets/css/auth-modern.css') }}?v={{ time() }}">
@endsection

@section('content')
    <div class="auth-page">
        <div class="auth-container">
            <!-- Left Side - Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <div class="floating-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>
                    <img src="{{asset('web_assets/img/login-student.svg')}}" alt="Email Verification" class="main-illustration">
                    <h2 class="welcome-text">تحقق من بريدك الإلكتروني</h2>
                    <p class="welcome-subtitle">لقد أرسلنا رابط التحقق إلى عنوان بريدك الإلكتروني</p>
                </div>
            </div>

            <!-- Right Side - Verification Info -->
            <div class="auth-form-section">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1 class="auth-title">تحقق من عنوان بريدك الإلكتروني</h1>
                        <p class="auth-description">قبل المتابعة، يرجى التحقق من بريدك الإلكتروني للحصول على رابط التحقق</p>
                    </div>

                    @if (session('resent'))
                        <div class="alert-box alert-success">
                            تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني
                        </div>
                    @endif

                    <div class="verification-message">
                        <div class="message-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <p class="message-text">
                            إذا لم تستلم البريد الإلكتروني، انقر على الزر أدناه لطلب رابط تحقق آخر
                        </p>
                    </div>

                    <form class="auth-form" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn-auth-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                            <span class="btn-text">إعادة إرسال بريد التحقق</span>
                        </button>
                    </form>

                    <div class="auth-footer">
                        <p class="footer-text">
                            تم التحقق بالفعل؟
                            <a href="/login" class="register-link">تسجيل الدخول</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
