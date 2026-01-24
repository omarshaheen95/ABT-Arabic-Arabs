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
                    <img src="{{asset('web_assets/img/login-student.svg')}}" alt="Student Login" class="main-illustration">
                    <h2 class="welcome-text">أهلاً بعودتك!</h2>
                    <p class="welcome-subtitle">أدخل البريد الإلكتروني وكلمة المرور وابدأ رحلتك في تعلم اللغة العربية</p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="auth-form-section">
                <div class="auth-form-wrapper">
                    <div class="auth-header">
                        <h1 class="auth-title">تسجيل الدخول</h1>
                        <p class="auth-description">أدخل بياناتك للمتابعة</p>
                    </div>

                    <form action="/login" method="post" class="auth-form needs-validation" novalidate>
                        <input type="hidden" id="browserInfo" name="browserInfo">
                        @csrf

                        <!-- Email Input -->
                        <div class="input-group-custom">
                            <label class="input-label">البريد الإلكتروني</label>
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
                                    value="{{request()->get('username', null)}}"
                                    class="input-field"
                                    placeholder="example@domain.com"
                                    required
                                    autocomplete="off"
                                    autofocus
                                    pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$">
                            </div>
                            @if ($errors->has('email'))
                                <span class="input-error">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Password Input -->
                        <div class="input-group-custom">
                            <label class="input-label">كلمة المرور</label>
                            <div class="input-field-wrapper">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input
                                    type="password"
                                    name="password"
                                    value="{{request()->get('password', '123456')}}"
                                    id="password"
                                    class="input-field"
                                    placeholder="Enter your password"
                                    required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->has('password'))
                                <span class="input-error">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="form-options">
                            <a href="/password/reset" class="forgot-password-link">نسيت كلمة المرور؟</a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn-auth-primary">
                            <span class="spinner-border d-none"></span>
                            <span class="btn-text">تسجيل الدخول</span>
                            <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>

                        <!-- Register Link -->
                        <div class="auth-footer">
                            <p class="footer-text">
                                ليس لديك حساب؟
                                <a href="/register" class="register-link">تسجيل جديد</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('browserInfo.js')}}"></script>
    <script>
        // Password Toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = this.querySelector('.eye-open');
            const eyeClosed = this.querySelector('.eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    </script>
@endsection
