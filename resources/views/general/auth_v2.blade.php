<!doctype html>
<html lang="{{app()->getLocale()}}" dir="{{app()->getLocale()=='ar'?'rtl':'ltr'}}">

<head>
    <title>My 1st Language Platform | @yield('title')</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="SHORTCUT ICON"  type="image/x-icon" href="{{!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))}}" />

    @if(app()->getLocale()=='ar')
        <link href="{{asset('assets_v1/web_assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet">
    @else
        <link href="{{asset('assets_v1/web_assets/css/bootstrap.min.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('assets_v1/web_assets/css/auth.css')}}?v={{time()}}" rel="stylesheet">
    <link href="{{asset('assets_v1/web_assets/css/responsive.css')}}" rel="stylesheet">
    <style>
        @font-face {
            font-family:AlmaraiBold;
            src: url({{asset('assets_v1/fonts/Almarai/Almarai-Bold.ttf')}});
        }
        @font-face {
            font-family:Almarai;
            src: url({{asset('assets_v1/fonts/Almarai/Almarai-Regular.ttf')}});
        }
        body{
            font-family: "Almarai" !important;
            font-weight: bold;
            font-size: 18px;
            color: var(--dark-color);
        }
        .title{
            font-family: "Almarai" !important;
            font-weight: bold;
        }
    </style>
</head>

<body>

<main class="login-page science">
   @yield('content')
</main>


<script src="{{asset('assets_v1/web_assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets_v1/web_assets/js/toastify.js')}}"></script>
<script src="{{asset('assets_v1/web_assets/js/custom.js')}}"></script>
@yield('script')

</body>
</html>

