@extends('layouts.container_2')

@section('p_style')
    <link rel="stylesheet" href="{{ asset('web_assets/css/auth-modern.css') }}?v=2">
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
                    <img src="{{asset('web_assets/img/forget-password.svg')}}" alt="Forgot Password" class="main-illustration">
                    <h2 class="welcome-text">هل نسيت كلمة السر؟</h2>
                    <p class="welcome-subtitle">لا داعي للقلق! أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين.</p>
                </div>
            </div>

            <!-- Right Side - Reset Password Form -->
            <div class="auth-form-section">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1 class="auth-title">إعادة تعيين كلمة المرور</h1>
                        <p class="auth-description">أدخل بريدك الإلكتروني لتلقي رابط إعادة التعيين</p>
                    </div>

                    @if (session('status'))
                        <div class="alert-box alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="/password/email" method="post" class="auth-form needs-validation" novalidate>
                        @csrf

                        <!-- Email Field -->
                        <div class="input-group-custom">
                            <label class="input-label">البريد الالكتروني</label>
                            <div class="input-field-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="input-field"
                                    placeholder="example@domain.com"
                                    required
                                    autocomplete="off"
                                    autofocus
                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            </div>
                            @if ($errors->has('email'))
                                <span class="input-error">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-auth-primary">
                            <span class="spinner-border d-none"></span>
                            <span class="btn-text">إرسال رابط إعادة تعيين كلمة المرور</span>
                            <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>

                        <!-- Back to Login Link -->
                        <div class="auth-footer">
                            <p class="footer-text">
                                هل تتذكر كلمة مرورك؟
                                <a href="/login" class="register-link">تسجيل الدخول</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
