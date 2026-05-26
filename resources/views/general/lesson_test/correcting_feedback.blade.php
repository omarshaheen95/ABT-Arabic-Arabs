@extends(getGuard().'.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('style')
    <link href="{{asset('assets_v1/lib/recording/css/green-audio-player.min.css')}}?v={{time()}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets_v1/lib/recording/css/recorder.css')}}?v={{time()}}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route(getGuard().'.lessons_tests.correcting_feedback', $user_test->id) }}" method="post">
        @csrf
        @if(isset($user_record))
            @method('PATCH')
        @endif
        <div class="row gap-3">
            <div class="form-group row  align-items-center">
                @if($user_test->lesson->lesson_type == "writing")
                    @foreach($user_test->writingResults as $writingResult)
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{'Student Answers'}}</label>
                            <div class="col-lg-9 col-xl-6">
                                <label class="col-form-label">
                                   <span class="text-primary fw-bolder"> ❋ {{t('Q')}} :</span>   {{ $writingResult->question->content }}
                                    <div class="separator separator-dotted my-2"></div>

                                    @if($writingResult->question->getFirstMediaUrl('imageQuestion'))
                                        :
                                        <div class="row justify-content-center py-3">
                                            <div class="col-lg-6 col-md-8">
                                                @if(\Illuminate\Support\Str::contains($writingResult->question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                    <div class="recorder-player" id="voice_audio_2">
                                                        <div class="audio-player">
                                                            <audio >
                                                                <source
                                                                    src="{{asset($writingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                    type="audio/mpeg">
                                                            </audio>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="w-100 text-center">
                                                        <img src="{{asset($writingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                             width="300px">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </label>
                                <br>
                                @if(!empty($writingResult->result))
                                    <div class="mb-2">
                                        <span class="badge badge-light-info">
                                            <i class="ki-duotone ki-text fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                             {{t('Word Count')}}: {{ str_word_count($writingResult->result) }}
                                        </span>
                                    </div>
                                @endif
                                <textarea disabled class="form-control" style="min-height: 150px;">{{$writingResult->result}}</textarea>

                                @if(!empty($writingResult->attachment))
                                    <div class="mt-3">
                                        @php
                                            $extension = pathinfo($writingResult->attachment, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
                                        @endphp

                                            <a href="{{asset($writingResult->attachment)}}" target="_blank" class="btn btn-success btn-icon-text">
                                                @if(in_array(strtolower($extension), ['pdf']))
                                                    <i class="ki-duotone ki-file-down fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                                    <i class="ki-duotone ki-document fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @else
                                                    <i class="ki-duotone ki-file fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @endif
                                                 ({{strtoupper($extension)}}) - {{t('View Attached File')}}
                                            </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                @endif
                @if($user_test->lesson->lesson_type == "speaking")
                    @foreach($user_test->speakingResults as $speakingResult)
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">{{'Student Answers'}}</label>
                            <div class="col-lg-9 col-xl-6">
                                <label class="col-form-label">
                                    <span class="text-primary fw-bolder"> ❋ {{t('Q')}} :</span> {{ $speakingResult->question->content }}
                                    @if($speakingResult->question->getFirstMediaUrl('imageQuestion'))
                                        :
                                        <div class="row justify-content-center py-3">
                                            <div class="col-lg-6 col-md-8">
                                                @if(\Illuminate\Support\Str::contains($speakingResult->question->getFirstMediaUrl('imageQuestion'), '.mp3'))
                                                    <div class="recorder-player" id="voice_audio_2">
                                                        <div class="audio-player">
                                                            <audio >
                                                                <source
                                                                    src="{{asset($speakingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                                    type="audio/mpeg">
                                                            </audio>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="w-100 text-center">
                                                        <img src="{{asset($speakingResult->question->getFirstMediaUrl('imageQuestion'))}}"
                                                             width="300px">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </label>
                                <br>
                                <audio src="{{asset($speakingResult->attachment)}}" controls></audio>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
            <div class="separator separator-dashed my-2"></div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">تغذية راجعة</label>
                <div class="col-lg-9 col-xl-6">
                    <textarea class="form-control" name="teacher_message">{{$user_test->feedback_message}}</textarea>
                </div>
            </div>


            <div class="form-group row align-items-center mb-3">
                <label class="col-xl-3 col-lg-3 col-form-label">سجل تغذية راجعة</label>
                <div class="col-lg-9 col-xl-9">
                    <div class="recorder-box mt-2" id="recorder-1">
                        <div class="controls">
                            <!-- TicketId -->
                            <input type="hidden" id="recorder_url_1" data-id="1" class="recorder_url"
                                   name="speaking[1]">
                            <input type="hidden" class="questions" name="questions[]" value="1">
                            <!-- Start Voice -->
                            <div class="icon start-voice startRecording">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                     fill="currentColor" class="bi bi-mic" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
                                    <path fill-rule="evenodd"
                                          d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>
                                </svg>
                                <span class="ms-2">سجل إجابتك -  Record your answer</span>
                            </div>

                            <!-- Stop Voice -->
                            <div class="icon stop-voice stopRecording d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     fill="currentColor" class="bi bi-stop-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                                </svg>
                                <span class="ms-2">إيقاف -  Stop</span>
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
                                <span class="ms-2">حذف -  Delete</span>
                            </div>

                            <!-- Timer -->
                            <span class="timer d-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                     height="16" fill="currentColor"
                                     class="bi bi-hourglass" viewBox="0 0 16 16">
                                    <path
                                        d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5zm2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702c0 .7-.478 1.235-1.011 1.491A3.5 3.5 0 0 0 4.5 13v1h7v-1a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351v-.702c0-.7.478-1.235 1.011-1.491A3.5 3.5 0 0 0 11.5 3V2h-7z"/>
                                  </svg>
                                  <span id="timer">00:00</span>
                            </span>

                        </div>
                        <!-- Voice Audio-->
                        <div class="recorder-player d-none" id="voice_audio_1" data-id="1">
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
                    @if($user_test && !is_null($user_test->feedback_record) )
                        <audio id="old_record" src="{{asset($user_test->feedback_record)}}" controls></audio>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">{{t('The Final Mark From 100')}}</label>
                <div class="col-lg-9 col-xl-6 justify-content-center text-center">
                    <input class="form-control" name="mark" type="number" placeholder="{{t('Mark')}}"
                           value="{{ $user_test->total }}" min="0" max="100">
                </div>
            </div>

            <div class="separator mt-4"></div>
            <div class="d-flex justify-content-end">
                <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{asset('assets_v1/lib/recording/js/green-audio-player.min.js')}}"></script>
    <script src="{{asset('assets_v1/lib/recording/js/recorder.js')}}"></script>
    <script src="{{asset('assets_v1/lib/recording/js/recorder-app.js')}}?v=2"></script>
    <script>
        GreenAudioPlayer.init({
            selector: '.audio-player', // inits Green Audio Player on each audio container that has class "audio-player"
            stopOthersOnPlay: true,
            showTooltips: true,
        });

        $('#form_information').submit(function (e) {
            e.preventDefault();
            var URL = $(this).attr('action');
            var METHOD = $(this).attr('method');
            var fd = new FormData($(this)[0]);
            blob_recorder_files.forEach((element, index) => {
                fd.append('feedback_audio_message', element, 'record_' + index + '.wav'); // Ensure the correct extension
                return;
            });
            $.ajax({
                type: METHOD,
                url: URL,
                data: fd,
                processData: false,
                contentType: false,
            }).done(function (data) {
                if (data.status) {
                    toastr.success(data.message);
                    window.location.href = "{{route(getGuard().'.lessons_tests.index')}}";
                } else {
                    toastr.error(data.message);
                }
            }).fail(function (jqXHR, textStatus, error) {
                toastr.error(jqXHR.responseJSON.message);

            });
        })

    </script>

{{--    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>--}}

{{--    <script>--}}
{{--        //webkitURL is deprecated but nevertheless--}}
{{--        URL = window.URL || window.webkitURL;--}}

{{--        var bar = $('.bar');--}}
{{--        var percent = $('.percent');--}}
{{--        var status = $('#status');--}}

{{--        var filename = '';--}}
{{--        var testerAudio = '';--}}
{{--        var gumStream; 						//stream from getUserMedia()--}}
{{--        var rec; 							//Recorder.js object--}}
{{--        var input; 							//MediaStreamAudioSourceNode we'll be recording--}}

{{--        // shim for AudioContext when it's not avb.--}}
{{--        var AudioContext = window.AudioContext || window.webkitAudioContext;--}}
{{--        var audioContext //audio context to help us record--}}

{{--        var recordButton = document.getElementById("start-btn");--}}
{{--        var stopButton = document.getElementById("stop-btn");--}}
{{--        var deleteButton = document.getElementById("delete-btn");--}}
{{--        var recordingsList = document.getElementById("recordingslist");--}}

{{--        //add events to those 2 buttons--}}
{{--        recordButton.addEventListener("click", startRecording);--}}
{{--        stopButton.addEventListener("click", stopRecording);--}}
{{--        deleteButton.addEventListener("click", deleteRecording);--}}

{{--        function startRecording() {--}}
{{--            console.log("recordButton clicked");--}}

{{--            /*--}}
{{--            Simple constraints object, for more advanced audio features see--}}
{{--            https://addpipe.com/blog/audio-constraints-getusermedia/--}}
{{--            */--}}

{{--            var constraints = {audio: true, video: false}--}}

{{--            /*--}}
{{--            Disable the record button until we get a success or fail from getUserMedia()--}}
{{--            */--}}

{{--            recordButton.disabled = true;--}}
{{--            stopButton.disabled = false;--}}
{{--            deleteButton.disabled = true--}}

{{--            /*--}}
{{--            We're using the standard promise based getUserMedia()--}}
{{--            https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia--}}
{{--            */--}}

{{--            navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {--}}
{{--                console.log("getUserMedia() success, stream created, initializing Recorder.js ...");--}}

{{--                /*--}}
{{--                create an audio context after getUserMedia is called--}}
{{--                sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods--}}
{{--                the sampleRate defaults to the one set in your OS for your playback device--}}

{{--                */--}}
{{--                audioContext = new AudioContext();--}}

{{--//update the format--}}
{{--// document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"--}}

{{--                /*  assign to gumStream for later use  */--}}
{{--                gumStream = stream;--}}

{{--                /* use the stream */--}}
{{--                input = audioContext.createMediaStreamSource(stream);--}}

{{--                /*--}}
{{--                Create the Recorder object and configure to record mono sound (1 channel)--}}
{{--                Recording 2 channels  will double the file size--}}
{{--                */--}}
{{--                rec = new Recorder(input, {numChannels: 1})--}}

{{--//start the recording process--}}
{{--                rec.record()--}}

{{--                console.log("Recording started");--}}

{{--            }).catch(function (err) {--}}
{{--//enable the record button if getUserMedia() fails--}}
{{--                recordButton.disabled = false;--}}
{{--                stopButton.disabled = true;--}}
{{--                deleteButton.disabled = true--}}
{{--            });--}}
{{--        }--}}

{{--        function deleteRecording() {--}}
{{--            console.log("deleteButton clicked rec.recording=", rec.recording);--}}
{{--            event.stopPropagation();--}}
{{--            $('#recordingslist').empty();--}}
{{--            testerAudio = '';--}}
{{--// Disable Record button and enable stop button !--}}
{{--            recordButton.disabled = false;--}}
{{--            stopButton.disabled = true;--}}
{{--            deleteButton.disabled = true;--}}
{{--        }--}}

{{--        function stopRecording() {--}}
{{--            console.log("stopButton clicked");--}}

{{--//disable the stop button, enable the record too allow for new recordings--}}
{{--            stopButton.disabled = true;--}}
{{--            recordButton.disabled = true;--}}
{{--            deleteButton.disabled = false;--}}

{{--//reset button just in case the recording is stopped while paused--}}
{{--// deleteButton.innerHTML="Pause";--}}

{{--//tell the recorder to stop the recording--}}
{{--            rec.stop();--}}

{{--//stop microphone access--}}
{{--            gumStream.getAudioTracks()[0].stop();--}}

{{--//create the wav blob and pass it on to createDownloadLink--}}
{{--            rec.exportWAV(createDownloadLink);--}}
{{--        }--}}

{{--        function createDownloadLink(blob) {--}}
{{--            testerAudio = blob;--}}
{{--            var url = URL.createObjectURL(blob);--}}
{{--            var au = document.createElement('audio');--}}
{{--            var li = document.createElement('li');--}}

{{--            // $('#recordingslist').append(`<li><audio style="height: 33px;" src="${url}" controls></audio><li>`)--}}
{{--// var link = document.createElement('a');--}}

{{--//name of .wav file to use during upload and download (without extendion)--}}
{{--            filename = new Date().toISOString();--}}
{{--//--}}
{{--//add controls to the <audio> element--}}
{{--            au.controls = true;--}}
{{--            au.src = url;--}}

{{--// //save to disk link--}}

{{--//add the new audio element to li--}}
{{--            li.appendChild(au);--}}


{{--//add the li element to the ol--}}
{{--            recordingsList.appendChild(li);--}}
{{--        }--}}


{{--        $("#form_information").submit(function (e) {--}}
{{--            e.stopPropagation();--}}
{{--            e.preventDefault();--}}
{{--            $('#save').prop('disabled', true);--}}
{{--            $('#save').text('{{t('Please wait, saving ...')}}');--}}

{{--            document.getElementById("start-btn").disabled = true;--}}
{{--            document.getElementById("stop-btn").disabled = true;--}}
{{--            document.getElementById("delete-btn").disabled = true;--}}
{{--            if (testerAudio == '') {--}}
{{--                testerAudio = new Blob(['no file'], {type: "text/plain"});--}}
{{--            }--}}

{{--            uploadBlob(testerAudio);--}}

{{--            function uploadBlob(testerAudio) {--}}
{{--                var reader = new FileReader();--}}
{{--                // this function is triggered once a call to readAsDataURL returns--}}
{{--                reader.onload = function (event) {--}}
{{--                    var fd = new FormData($("#form_information")[0]);--}}
{{--                    fd.append('record1', testerAudio, filename);--}}
{{--                    console.log(fd);--}}
{{--                    $.ajax({--}}
{{--                        url: '{{ route(getGuard().'.lessons_tests.correcting_feedback', $user_test->id) }}',--}}
{{--                        data: fd,--}}
{{--                        processData: false,--}}
{{--                        contentType: false,--}}
{{--                        type: 'POST',--}}
{{--                        xhr: function () {--}}
{{--                            var xhr = $.ajaxSettings.xhr();--}}
{{--                            // xhr.onprogress = function e() {--}}
{{--                            //     // For downloads--}}
{{--                            //     if (e.lengthComputable) {--}}
{{--                            //         console.log(e.loaded / e.total);--}}
{{--                            //     }--}}
{{--                            // };--}}
{{--                            // xhr.upload.onprogress = function (e) {--}}
{{--                            //     $('.progress').show();--}}
{{--                            //     $('#message').show();--}}
{{--                            //     var percent_value = 0;--}}
{{--                            //     var position = event.loaded || event.position;--}}
{{--                            //     var total = event.total;--}}
{{--                            //     if (event.lengthComputable) {--}}
{{--                            //         percent_value = Math.ceil(position / total * 100);--}}
{{--                            //     }--}}
{{--                            //     //update progressbar--}}
{{--                            //     var percentVal = percent_value + '%';--}}
{{--                            //     bar.width(percentVal)--}}
{{--                            //     percent.html(percentVal);--}}
{{--                            // };--}}
{{--                            return xhr;--}}
{{--                        },--}}
{{--                        success: function (data) {--}}
{{--                            toastr.success("تم إعتماد التصحيح بنجاح");--}}
{{--                        },--}}
{{--                        error: function (data) {--}}
{{--                            $('#save').prop('disabled', false);--}}
{{--                            $('#save').text("{{ t('Save') }}");--}}
{{--                            document.getElementById("start-btn").disabled = false;--}}
{{--                            document.getElementById("stop-btn").disabled = true;--}}
{{--                            document.getElementById("delete-btn").disabled = false;--}}

{{--                        }--}}
{{--                    }).done(function (data) {--}}
{{--                        setTimeout(function () {--}}
{{--                            window.location.href = "{{route(getGuard().'.lessons_tests.index')}}";--}}
{{--                        }, 500);--}}
{{--                    });--}}
{{--                };--}}
{{--                // trigger the read from the reader...--}}
{{--                reader.readAsDataURL(testerAudio);--}}
{{--            }--}}
{{--        });--}}

{{--    </script>--}}

@endsection

