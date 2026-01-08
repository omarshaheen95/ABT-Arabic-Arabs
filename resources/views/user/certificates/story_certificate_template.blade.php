{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@php
    $signature_2 = 'Executive Director';
    $signature_3 = 'Mr. Mohamed Gamal';
    $date = 'Date:';
@endphp
<html lang="en" dir="ltr">
<head>
    <link rel="stylesheet" href="{{asset('certification/css/bootstrap-5.0.2/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('certification/css/style.css')}}?v={{time()}}">
    <title>Certificate Awarded to {{$student_test->user->name}}</title>
    <link rel="shortcut icon" href="{{!settingCache('logo_min')? asset('logo_min.svg?v=1'):asset(settingCache('logo_min'))}}" />
</head>
<body class="no-page-break">
<div class="page no-page-break position-relative p-0 d-flex justify-content-center">
    <div class="position-absolute">
        <img src="{{asset('certification/img/border.svg')}}" style="width: 296mm; min-height: 209mm;">
    </div>
    <div class="subpage no-page-break">
        <div class="row mb-5">
            <img src="{{asset('certification/img/header_en.svg')}}">
        </div>
        <div class="d-flex flex-column align-items-center gap-1" style="padding-top: 40px">
            <div class="d-flex justify-content-center mb-2 gap-1">
                <h5 class="p-text"> This Certificate Awarded to </h5>
                <h5 class="p-text"> : </h5>
                <h5 class="p-s-text">{{$student_test->user->name}}</h5>
            </div>

            <div class="d-flex justify-content-center gap-1">
                <h6 class="s-text">In appreciation of passing the assessment of story</h6>
                <h5 class="s-text"> : </h5>
                <h5 class="s-s-text">{{$student_test->story->name}}</h5>
            </div>
            <div class="d-flex justify-content-center gap-1">
                <h6 class="s-text">in the level {{$student_test->story->grade}}</h6>
            </div>
            <div class="d-flex justify-content-center gap-1">
                <h6 class="s-text">with the percentage of {{$student_test->total_per}}</h6>
            </div>
            <div class="d-flex justify-content-center">
                <h6 class="s-text">During the journey of learning Arabic from the Non-Arabs platform.</h6>
            </div>
            <div class="signature-section mt-5">
                <div class="signature-block">
                    <span class="signature-title">{{$signature_2}}</span>
                    <span class="signature-name">{{$signature_3}}</span>
                    <img src="{{asset('certification/img/seo_signature.png')}}" style="height: 50px;" alt="Signature">
                </div>
                <div class="signature-block">
                    <img src="{{asset('certification/img/signature_v2.png')}}" style="height: 90px;" alt="Signature">
                </div>
                <div class="signature-block">
                    <span class="signature-title">{{$date}}</span>
                    <span class="signature-name">{{date('d-m-Y')}}</span>
                </div>
            </div>
            <img class="logos" src="{{asset('certification/img/logos_group_v2.svg')}}?v=1" alt="Logos">
        </div>

    </div>
</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>
</body>
</html>
