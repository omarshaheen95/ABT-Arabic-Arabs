{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@extends('general.user.std_container')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://www.arabic-keyboard.org/keyboard/keyboard.css">


    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }

        .progress {
            position: relative;
            width: 100%;
            height: 30px;
            border: 1px solid #7F98B2;
            border-radius: 3px;
            border-radius: 36px !important;
            display: none;
        }

        .bar {
            background-color: #17C41A;
            width: 0%;
            height: 30px;
            border-radius: 3px;
        }

        .percent {
            position: absolute;
            display: inline-block;
            top: 4px;
            left: 48%;
            color: #000;
        }

        .text-success {
            color: #0F0 !important;
        }

        .box-style {
            color: #FF0000;
            font-size: 15px;
            font-weight: bold;
            background-color: #EFEFEF;
            padding: 10px;
            border-left: 5px solid #F00;;
            border-right: 5px solid #F00;
            border-top: 5px solid #000;
            border-bottom: 5px solid #000;
        }

        #recordingslist {
            list-style: none;
        }

        #keyboardInputLayout {
            direction: ltr !important;
        }

        #keyboardInputMaster tbody tr td div#keyboardInputLayout table tbody tr td {
            font: normal 30px 'Lucida Console', monospace;
        }

        .keyboardInputInitiator {
            width: 50px
        }
    </style>
@endsection
@section('content')
    <section class="login-home user-home lessons-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="direction: ltr">
                    <div class="section-title">
                        <h1 class="title"> {{$story->name}} </h1>
                        <nav class="breadcrumb">
{{--                            <a class="breadcrumb-item" href="{{route('stories.list', $story->grade)}}"> المستويات </a>--}}
                            <span class="breadcrumb-item active" aria-current="page">القصص </span>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row" dir="rtl">
                <div class="col-lg-3">
                    <div class="exercise-card">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="#exercise-1" class="nav-link active">
                                    <div class="exercise-title"> التمرين الاول</div>
                                    <div class="exercise-title"> 1st Exercise</div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#exercise-2" class="nav-link">
                                    <div class="exercise-title"> سجل بصوتك</div>
                                    <div class="exercise-title">Record your voice</div>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">

                    <div class="exercise-box" id="exercise-1">
                        <div class="exercise-box-header">
                                    <span class="icon">
                                        <svg id="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                             viewBox="0 0 33 33">
                                            <g id="message-question">
                                                <path id="Vector" d="M0,0H33V33H0Z" fill="none" opacity="0"/>
                                                <path id="Vector-2" data-name="Vector"
                                                      d="M20.625,22h-5.5L9.006,26.07a1.371,1.371,0,0,1-2.131-1.141V22A6.5,6.5,0,0,1,0,15.125V6.875A6.5,6.5,0,0,1,6.875,0h13.75A6.5,6.5,0,0,1,27.5,6.875v8.25A6.5,6.5,0,0,1,20.625,22Z"
                                                      transform="translate(2.75 3.341)" fill="#223f99" opacity="0.4"/>
                                                <g id="Group" transform="translate(13.186 8.401)">
                                                    <path id="Vector-3" data-name="Vector"
                                                          d="M3.314,8.25A1.039,1.039,0,0,1,2.282,7.219V6.93A3.2,3.2,0,0,1,3.891,4.249c.509-.344.674-.577.674-.935a1.251,1.251,0,1,0-2.5,0A1.039,1.039,0,0,1,1.031,4.345,1.039,1.039,0,0,1,0,3.314a3.314,3.314,0,1,1,6.627,0,3.154,3.154,0,0,1-1.581,2.64c-.536.358-.7.591-.7.976v.289A1.03,1.03,0,0,1,3.314,8.25Z"
                                                          fill="#223f99"/>
                                                </g>
                                                <g id="Group-2" data-name="Group" transform="translate(15.469 18.012)">
                                                    <path id="Vector-4" data-name="Vector"
                                                          d="M1.031,2.063A1.031,1.031,0,1,1,2.063,1.031,1.03,1.03,0,0,1,1.031,2.063Z"
                                                          fill="#223f99"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                            <span class="title"> أقرأ بصوتٍ عالٍ </span>
                        </div>
                        <div class="exercise-box-body">
                            <div class="exercise-question">
                                {!! $story->content !!}
                            </div>
                        </div>
                    </div>

                    <div class="exercise-box" id="exercise-2">
                        <div class="exercise-box-header">
                                    <span class="icon">
                                        <svg id="icon" xmlns="http://www.w3.org/2000/svg" width="33" height="33"
                                             viewBox="0 0 33 33">
                                            <g id="message-question">
                                                <path id="Vector" d="M0,0H33V33H0Z" fill="none" opacity="0"/>
                                                <path id="Vector-2" data-name="Vector"
                                                      d="M20.625,22h-5.5L9.006,26.07a1.371,1.371,0,0,1-2.131-1.141V22A6.5,6.5,0,0,1,0,15.125V6.875A6.5,6.5,0,0,1,6.875,0h13.75A6.5,6.5,0,0,1,27.5,6.875v8.25A6.5,6.5,0,0,1,20.625,22Z"
                                                      transform="translate(2.75 3.341)" fill="#223f99" opacity="0.4"/>
                                                <g id="Group" transform="translate(13.186 8.401)">
                                                    <path id="Vector-3" data-name="Vector"
                                                          d="M3.314,8.25A1.039,1.039,0,0,1,2.282,7.219V6.93A3.2,3.2,0,0,1,3.891,4.249c.509-.344.674-.577.674-.935a1.251,1.251,0,1,0-2.5,0A1.039,1.039,0,0,1,1.031,4.345,1.039,1.039,0,0,1,0,3.314a3.314,3.314,0,1,1,6.627,0,3.154,3.154,0,0,1-1.581,2.64c-.536.358-.7.591-.7.976v.289A1.03,1.03,0,0,1,3.314,8.25Z"
                                                          fill="#223f99"/>
                                                </g>
                                                <g id="Group-2" data-name="Group" transform="translate(15.469 18.012)">
                                                    <path id="Vector-4" data-name="Vector"
                                                          d="M1.031,2.063A1.031,1.031,0,1,1,2.063,1.031,1.03,1.03,0,0,1,1.031,2.063Z"
                                                          fill="#223f99"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                            <span class="title"> أقرأ بصوتٍ عالٍ </span>
                        </div>
                        <div class="exercise-box-body">
                            <div class="exercise-question-answer text-center">
                                <div class="text-center">
                                    <div class="recorder-box" id="recorder-1">
                                        <div class="controls">
                                            <!-- TicketId -->
                                            <input type="hidden" id="recorder_url_1" class="recorder_url"
                                                   name="TicketId" value="500">
                                            <!-- Start Voice -->
                                            <div class="icon start-voice startRecording">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                     fill="currentColor" class="bi bi-mic" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                          d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
                                                    <path fill-rule="evenodd"
                                                          d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>
                                                </svg>
                                                <span class="ms-2">
                                                                سجل إجابتك -  Record your answer
                                                            </span>
                                            </div>

                                            <!-- Stop Voice -->
                                            <div class="icon stop-voice stopRecording d-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-stop-fill"
                                                     viewBox="0 0 16 16">
                                                    <path
                                                            d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                                                </svg>
                                                <span class="ms-2">
                                                                إيقاف -  Stop
                                                            </span>
                                            </div>

                                            <!-- Remove Voice -->
                                            <div class="icon remove-voice deleteRecording d-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                     fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd"
                                                          d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                                <span class="ms-2">
                                                                حذف -  Delete
                                                            </span>
                                            </div>

                                            <!-- Timer -->
                                            <span class="timer d-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor" class="bi bi-hourglass"
                                                                 viewBox="0 0 16 16">
                                                                <path
                                                                        d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z"/>
                                                            </svg>
                                                            <span id="timer">00:00</span>
                                                        </span>
                                            <div class="icon btn  btn-soft-success saveRecording d-none"
                                                 style="background-color: rgb(46, 204, 112) !important; color: #FFF !important;">
                                                    <span class="icon-spinner d-none ">
                                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                                        <span> جاري الحفظ </span>
                                                    </span>
                                                <span class=" text ms-2"> حفظ -  Save</span>
                                            </div>

                                        </div>
                                        <!-- Voice Audio-->
                                        <div class="recorder-player d-none" id="voice_audio_1">
                                            <!-- crossorigin -->
                                            <div class="audio-player">
                                                <audio crossorigin>
                                                    <source
                                                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/355309/Swing_Jazz_Drum.mp3"
                                                            type="audio/mpeg">
                                                </audio>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="exercise-question">

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

