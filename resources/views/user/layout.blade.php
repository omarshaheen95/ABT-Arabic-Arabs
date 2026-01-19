<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="">
    <meta name="robots" content="index, follow">

    <meta name="geo.position" content="">
    <meta name="geo.placename" content="">
    <meta name="geo.region" content="">
    <meta name="google" content="notranslate">

    <meta property="og:type" content="" />
    <meta property="og:title" content="{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="{{!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))}}"/>
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />

    <meta name="twitter:title" content="{{ isset($title) ? $title. " | ":''  }}منصة لغتي الأولى"/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:image" content="{{!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))}}"/>
    <meta name="twitter:site" content=""/>
    <meta name="twitter:creator" content=""/>

    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="{{!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))}}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('user_assets/css/global.css')}}" />
    <link rel="stylesheet" href="{{asset('user_assets/css/components.css')}}" />
    <link rel="stylesheet" href="{{asset('user_assets/css/layout.css')}}" />
    <link rel="stylesheet" href="{{asset('user_assets/lib/toastr/toastr.min.css')}}" />
    @stack('style')
    <style>
        /* Page Preloader */
        .page-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: #F1FDF6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }

        .page-preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-logo {
            height: clamp(120px, 15vw, 200px);
            width: auto;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        /* Hide content until loaded */
        body.page-loading .dashboard-layout {
            opacity: 0;
            visibility: hidden;
        }

        body.page-loaded .dashboard-layout {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-in;
        }

    </style>
    <style>
        /* Welcome Banner */
        .welcome-banner {
            position: relative;
            background: linear-gradient(270deg, rgba(19, 137, 68, 1) 0%, rgba(52, 211, 108) 100%);
            border-radius: 12px;
            padding: clamp(12px, 1.2vw, 20px) clamp(16px, 1.5vw, 24px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: clamp(16px, 2vw, 24px);
            border-width: 1px 1px 3px 1px;
            border-style: solid;
            border-color: #00000030;
            margin-bottom: 12px;
            color: white;
            overflow: hidden;
            flex-shrink: 0;
            min-height: clamp(100px, 12vw, 140px);
            max-height: clamp(100px, 12vw, 140px);
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            -ms-border-radius: 12px;
            -o-border-radius: 12px;
        }

        .welcome-content {
            flex: 1;
            max-width: 65%;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-title {
            font-family: var(--font-family);
            font-size: clamp(14px, 1.1vw, 20px);
            font-weight: 700;
            margin-bottom: clamp(6px, 0.6vw, 10px);
            line-height: 1.3;
        }

        .user-name {
            color: #fbbf24;
        }

        .welcome-subtitle {
            font-family: var(--font-family);
            font-size: clamp(11px, 0.85vw, 15px);
            opacity: 0.95;
            line-height: 1.5;
            margin: 0;
        }

        .welcome-illustration {
            flex-shrink: 0;
            width: 25%;
            height: clamp(90px, 11vw, 130px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .welcome-illustration img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .welcome-banner {
                flex-direction: column;
                text-align: center;
                min-height: auto;
                max-height: none;
                padding: clamp(16px, 3vw, 24px);
            }

            .welcome-content {
                max-width: 100%;
            }

            .welcome-illustration {
                width: 50%;
                height: clamp(80px, 25vw, 120px);
            }
        }
    </style>
</head>
<body data-page="@yield('page-name','dashboard')" class="page-loading">

<!-- Page Preloader -->
<div class="page-preloader" id="pagePreloader">
    <img class="preloader-logo" src="{{asset('logo_circle.svg')}}" alt="Non-Arabs LMS Logo">
</div>

<div class="dashboard-layout">
    <main class="main-content">
        @if($user_data['package_name'] == 'Free package')
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h1 class="welcome-title">
                        مرحبًا بعودتك, <span class="user-name">{{str_before($user_data['name'],' ')}}!</span> 👋
                    </h1>
                    <p class="welcome-subtitle">
                        يبدو أنك قمت بإنشاء حساب مجاني وقمت باستخدامه.
                        يرجى استخدام الحساب المدفوع المخصص لك من المدرسة واستخدام البيانات التي تمت مشاركتها معك من خلال معلم اللغة العربية.
                    </p>

                </div>
                <div class="welcome-illustration">
                    <img
                        src="{{asset('user_assets/images/illustrations/man-ride.svg')}}"
                        alt="Learning illustration"
                    />
                </div>
            </div>
        @endif
        @yield('content')
    </main>
</div>
<script>
    var USER_ID = {{Auth::guard('web')->user()->id}};
    var USER_PROFILE={
        name: '{{Auth::guard('web')->user()->name}}',
        avatar: '{{Auth::guard('web')->user()->image??'user_assets/images/illustrations/profile.svg'}}',
        progress:{
            percentage: 55,
            todayTarget: 40
        }
    };
    var TRANS = {
        'Year':'{{t('Year')}}',
        'Grade':'{{t('Grade')}}',
    };
    var MENU_ITEMS = [
        { id: 'dashboard', icon: '📖', text: 'الدروس', href: '{{route('home')}}' },
        { id: 'library', icon: '📚', text: 'القصص', href: '{{route('stories.levels')}}' },
        { id: 'homework', icon: '📝', text: 'الواجبات', href: '{{route('lesson.assignments')}}' },
        { id: 'ranking', icon: '🏆', text: 'التصنيف', href: '{{route('ranking')}}' },
        { id: 'certificates', icon: '🎖️', text: 'الشهادات', href: '{{route('certificate.index',['type' => 'lessons'])}}' },
        { id: 'logout', icon: '🚪', text: 'تسجيل الخروج', href: '../pages/login.html', special: 'logout' }
    ];
    var CALANDER = {
        currentDate: '{{ \Carbon\Carbon::now()->locale("ar")->translatedFormat("d F Y") }}',
        streakCount: {{ $user_data['streak'] ?? 0 }},
        currentDay: '{{ \Carbon\Carbon::now()->locale("ar")->dayName }}',
        days: @json($dashboard_data['week_days'] ?? [])
    }
    var NOTIFICATION_LIST = @json($dashboard_data['notifications'] ?? []);
    var USER_STATUS = {
        streak: {{ $user_data['streak'] ?? 0 }},
        xp: {{ $user_data['currentXp'] ?? 0 }},
        levelIcon: '{{ $user_data['levelIcon']}}',
        notifications: {{ $dashboard_data['unread_notifications_count'] ?? 0 }},
        uncompletedLessons: {{ $user_data['uncompletedLessons'] ?? 0 }},
        uncompletedStories: {{ $user_data['uncompletedStories'] ?? 0 }}
    };
    var userProfileData = @json($user_data);
    var MARK_AS_READ_NOTIFICATION_URL = '{{route('notification.read',':id')}}'
    var MARK_AS_READ_ALL_NOTIFICATION_URL = '{{route('notification.read-all')}}'
    var LOGOUT_URL = '{{route('user.logout')}}'
    var PROFILE_URL = '{{route('user.profile')}}'
</script>
@stack('pre-script')
<script src="{{asset('user_assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('user_assets/js/main.js')}}"></script>
<script src="{{asset('user_assets/js/general.js')}}"></script>
<script src="{{asset('user_assets/js/components/navbar.js')}}"></script>
<script src="{{asset('user_assets/js/components/sidenav.js')}}"></script>
<script src="{{asset('user_assets/js/components/rightsidebar.js')}}"></script>
<script src="{{asset('user_assets/js/layout-manager.js')}}"></script>
<script src="{{asset('user_assets/js/pages/dashboard.js')}}"></script>
<script src="{{asset('user_assets/lib/toastr/toastr.min.js')}}"></script>

<script>
    <!--Toastr Init-->
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "2000",
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    @if(Session::has('message'))
    toastr.{{Session::get('m-type') ? Session::get('m-type'):'success'}}("{{Session::get('message')}}");
    @endif
</script>

@stack('script')

<script>
    // Show page content after all scripts and layout components are loaded
    window.addEventListener('load', function() {
        // Small delay to ensure all DOM manipulations are complete
        setTimeout(function() {
            // Hide preloader
            var preloader = document.getElementById('pagePreloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }

            // Show content
            document.body.classList.remove('page-loading');
            document.body.classList.add('page-loaded');
        }, 100);
    });

    // Fallback: Show content after a maximum wait time even if load event doesn't fire
    setTimeout(function() {
        if (document.body.classList.contains('page-loading')) {
            var preloader = document.getElementById('pagePreloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }

            document.body.classList.remove('page-loading');
            document.body.classList.add('page-loaded');
        }
    }, 3000);
</script>
</body>
</html>
