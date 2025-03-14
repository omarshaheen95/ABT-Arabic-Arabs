@extends('layouts.container_2')
@section('p_style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            padding-inline-start: 65px;
            height: 65px;
            border-radius: 20px;
            border-color: #D9E3FD;
            text-align: center;
            vertical-align: middle;
            padding-top: 18px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 20px;
            right: 1px;
            width: 20px;
        }
        .select2-container {
            box-sizing: border-box;
             display: inline;
            margin: 0;
            position: relative;
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <main class="wrapper" id="login-home">
        <!-- Start login-home -->
        <section class="login-home login-student pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb-5">
                            <h1 class="title"> تسجيل حساب جديد </h1>
                            <p class="info"> يرجى إدخال كافة البيانات بصورة صحيحة </p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @if (count($errors) > 0)
                        <div class="alert alert-warning">
                            <ul style="width: 100%;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-lg-6 col-md-9">
                        <div class="form form-login">
                            <form action="/register" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label"> الاسم </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/username.svg')}}" alt="">
                                                </div>
                                                <input type="text" name="name" id="username" class="form-control" placeholder="مثلاً: محمد خالد الاميري"
                                                       required autocomplete="off" autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label"> البريد الإلكتروني</label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/mail.svg')}}" alt="">
                                                </div>
                                                <input type="email" name="email" id="email" class="form-control" placeholder=" ex: example@domain.com"
                                                       required autocomplete="off" autofocus pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="forget-password-label">
                                                <label for="password" class="form-label"> كلمه المرور </label>
                                            </div>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                                </div>
                                                <input type="password" name="password" id="password" class="form-control" placeholder=" **********" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="forget-password-label">
                                                <label for="password_confirmation" class="form-label"> تأكيد كلمة المرور </label>
                                            </div>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/password.svg')}}" alt="">
                                                </div>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder=" **********" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="mobile" class="form-label"> الموبايل </label>
                                            <div class="form-control-icon phone">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/phone.svg')}}" alt="">
                                                </div>
                                                <input type="tel" class="form-control phone-input" name="mobile" id="mobile-1" data-country="sa" onblur="getPhoneKey(this.id)" required>
                                                <input type="hidden" class="form-control"  id="mobile-1-code" name="country_code">
                                                <input type="hidden" class="form-control"  id="mobile-1-country" name="short_country">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="universtiy" class="form-label"> اختر المدرسة </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/school.svg')}}" alt="">
                                                </div>
                                                <select name="school_id" id="universtiy" class="searchSelect form-control form-select " required>
{{--                                                    @foreach($schools as $school)--}}
{{--                                                        <option value="{{$school->id}}">{{$school->name}}</option>--}}
{{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="class" class="form-label"> اختر الصف </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/class.svg')}}" alt="">
                                                </div>
                                                <select name="grade_id" id="class" class="form-control form-select" required>
                                                    @foreach($grades as $grade)
                                                        <option value="{{$grade->id}}">{{$grade->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="package" class="form-label"> اختر باقة </label>
                                            <div class="form-control-icon">
                                                <div class="icon">
                                                    <img src="{{asset('web_assets/img/package.svg')}}" alt="">
                                                </div>
                                                <select name="package_id" id="package" class="form-control form-select" required>
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" {{request()->get('package_id', false) && request()->get('package_id', false) == $package->id ? 'selected':''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-theme btn-submit">
                                        <span class="spinner-border d-none"></span>
                                        <span class="text"> تسجيل </span>
                                    </button>
                                </div>
                                <div class="form-group text-center">
                                    <a href="/login" class="form-link"> العودة لتسجيل الدخول </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End login-home -->
    </main>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    {!! $validator->selector('#form_information') !!}
    <script type="text/javascript">
        $('.searchSelect').select2({
            placeholder: 'اختر مدرسة',
            dir: "rtl",
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                inputTooShort: function () {
                    return "يرجى ادخال اسم مدرسة للبحث";
                },
                noResults:function(){return"لا يوجد نتائج مطابقة"},
                searching:function(){return"جاري البحث ..."}
            },
            ajax: {

                url: '{{route('schools')}}',
                dataType: 'json',
                delay: 150,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
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
    </script>
@endsection
