@extends('layouts.container_2')

@section('p_style')
    <link rel="stylesheet" href="{{ asset('web_assets/css/auth-modern.css') }}?v=2">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="auth-page">
        <div class="auth-container auth-register">
            <!-- Left Side - Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <div class="floating-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>
                    <img src="{{asset('web_assets/img/login-student.svg')}}" alt="Student Register" class="main-illustration">
                    <h2 class="welcome-text">انضم إلينا!</h2>
                    <p class="welcome-subtitle">املأ بياناتك الشخصية وابدأ بالحساب التجريبي</p>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="auth-form-section">
                <div class="auth-form-wrapper register-form-wrapper">
                    <div class="auth-header">
                        <h1 class="auth-title">تسجيل حساب جديد</h1>
                        <p class="auth-description">أنشئ حسابك لتبدأ التعلم</p>
                    </div>

                    @if (count($errors) > 0)
                        <div class="alert-box alert-warning">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/register" method="post" class="auth-form register-form needs-validation" novalidate>
                        @csrf

                        <div class="form-grid">
                            <!-- Name Field -->
                            <div class="input-group-custom">
                                <label class="input-label">الاسم</label>
                                <div class="input-field-wrapper">
                                    <span class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <input
                                        type="text"
                                        name="name"
                                        id="username"
                                        class="input-field"
                                        placeholder="أدخل اسمك"
                                        required
                                        autocomplete="off"
                                        autofocus>
                                </div>
                            </div>

                            <!-- Email Field -->
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
                                        class="input-field"
                                        placeholder="example@domain.com"
                                        required
                                        autocomplete="off"
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                </div>
                            </div>

                            <!-- Password Field -->
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
                                        id="password"
                                        class="input-field"
                                        placeholder="Enter your password"
                                        required>
                                    <button type="button" class="password-toggle" data-target="password">
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
                            </div>

                            <!-- Password Confirmation Field -->
                            <div class="input-group-custom">
                                <label class="input-label">تأكيد كلمة المرور</label>
                                <div class="input-field-wrapper">
                                    <span class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </span>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="input-field"
                                        placeholder="Confirm your password"
                                        required>
                                    <button type="button" class="password-toggle" data-target="password_confirmation">
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
                            </div>

                            <!-- Mobile Field -->
                            <div class="input-group-custom input-full-width-mobile">
                                <label class="input-label">الجوال</label>
                                <div class="input-field-wrapper" style="display: block !important;">
{{--                                    <span class="input-icon">--}}
{{--                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">--}}
{{--                                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>--}}
{{--                                            <line x1="12" y1="18" x2="12.01" y2="18"></line>--}}
{{--                                        </svg>--}}
{{--                                    </span>--}}
                                    <input
                                        type="tel"
                                        class="input-field phone-input"
                                        name="mobile"
                                        id="mobile-1"
                                        data-country="ae"
                                        onblur="getPhoneKey(this.id)"
                                        required>
                                    <input type="hidden" id="mobile-1-code" name="country_code">
                                    <input type="hidden" id="mobile-1-country" name="short_country">
                                </div>
                            </div>

                            <!-- School Field -->
                            <div class="input-group-custom input-full-width-mobile select2-wrapper">
                                <label class="input-label">اختر المدرسة</label>
                                <div class="select2-icon-wrapper">
                                    <span class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg>
                                    </span>
                                    <select name="school_id" id="universtiy" class="select2-custom searchSelect" required>
                                    </select>
                                </div>
                            </div>

                            <!-- Grade Field -->
                            <div class="input-group-custom">
                                <label class="input-label">اختر الصف</label>
                                <div class="input-field-wrapper">
                                    <span class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    </span>
                                    <select name="grade_id" id="class" class="input-field" required>
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Package Field -->
                            <div class="input-group-custom">
                                <label class="input-label">اختر الباقة</label>
                                <div class="input-field-wrapper">
                                    <span class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                    </span>
                                    <select name="package_id" id="package" class="input-field" required>
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}" {{request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="btn-auth-primary">
                            <span class="spinner-border d-none"></span>
                            <span class="btn-text">تسجيل</span>
                            <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>

                        <!-- Login Link -->
                        <div class="auth-footer">
                            <p class="footer-text">
                                لديك حساب بالفعل؟
                                <a href="/login" class="register-link">تسجيل الدخول</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! $validator->selector('#form_information') !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        // Password Toggle for multiple fields
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
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
        });



        // Select2 for School Search
        $('.searchSelect').select2({
            placeholder: '{{t('Select School')}}',
            dir: "{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}",
            language: {
                inputTooShort: function () {
                    return "{{t('Please enter a school name to search.')}}";
                },
                noResults: function() {
                    return "{{t('There are no matching results')}}";
                },
                searching: function() {
                    return "{{t('Searching ...')}}";
                }
            },
            ajax: {
                url: '{{route('schools')}}',
                dataType: 'json',
                delay: 150,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });


        //Phone-input
        $('.phone-input').each(function(){
            var input = document.querySelector("#"+ this.id);
            var country = $("#"+ this.id).data("country");
            var iti = window.intlTelInput(input, {
                initialCountry: 'ae',
                // geoIpLookup: function(callback) {
                //     $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
                //         var countryCode = (resp && resp.country) ? resp.country : "ae";
                //         callback(countryCode);
                //     });
                // },
                separateDialCode: true,
                utilsScript: "web_assets/intlTelInput/utils.js"
            });
        });

        function getPhoneKey(id){
            var input = document.querySelector('#'+ id);
            var iti = window.intlTelInputGlobals.getInstance(input);
            $('#'+ id + "-code").val(iti.getSelectedCountryData().dialCode);
            $('#'+ id + "-country").val(iti.getSelectedCountryData().iso2);
            $("#"+ id).attr("data-country",iti.getSelectedCountryData().iso2);
        }

    </script>
@endsection
