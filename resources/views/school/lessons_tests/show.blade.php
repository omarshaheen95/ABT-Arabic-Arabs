@extends('manager.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}
    </li>
@endpush
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route('school.lessons_tests.correct', $user_test->id) }}" method="post">
        @csrf
        @if(isset($user_record))
            @method('PATCH')
        @endif
        <div class="row gap-3">
            <div class="form-group row  align-items-center">
                @if($user_test->lesson->lesson_type == "writing")
                    @foreach($user_test->writingResults as $writingResult)
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"></label>
                            <div class="col-lg-9 col-xl-6">
                                <label class="col-form-label">
                                    {{ $writingResult->question->content }}

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
                                <textarea disabled class="form-control">{{$writingResult->result}}</textarea>
                            </div>

                        </div>
                    @endforeach
                @endif
                @if($user_test->lesson->lesson_type == "speaking")
                    @foreach($user_test->speakingResults as $speakingResult)
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label"></label>
                            <div class="col-lg-9">
                                <label class="col-form-label">
                                    {{ $speakingResult->question->content }}
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
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">تغذية راجعة</label>
                <div class="col-lg-9 col-xl-6">
                    <textarea class="form-control" name="teacher_message">{{$user_test->feedback_message}}</textarea>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-xl-3 col-lg-3 col-form-label">سجل تغذية راجعة</label>
                <div class="col-lg-9 col-xl-6 d-flex justify-content-center align-items-center text-center">
                    <label class="text-danger" id="spiner" style="display:none;">تسجيل
                        <i
                            class="fa fa-circle-o-notch fa-spin"
                            style="font-size:24px"></i></label>
                    <div class="d-flex gap-3 justify-content-center align-items-center">
                        <button class="btn btn-success btn-sm btn-icon record-btn"
                                type="button"
                                id="start-btn">
                            <i class="fa fa-play p-0"></i>
                        </button>
                        <button type="button" data-question=""
                                class="btn btn-danger btn-sm btn-icon record-btn"
                                id="stop-btn"
                                disabled>
                            <i class="fa fa-stop p-0"></i>
                        </button>
                        <button type="button"
                                class="btn btn-warning btn-sm btn-icon record-btn"
                                id="delete-btn" disabled>
                            <i class="fa fa-trash p-0"></i>
                        </button>
                    </div>

                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-xl-3 col-lg-3 col-form-label">التسحيلات</label>
                <div class="col-lg-9 col-xl-6 d-flex justify-content-center align-items-center text-center">
                    <ul id="recordingslist"></ul>
                    @if($user_test && !is_null($user_test->feedback_record) )
                        <audio src="{{asset($user_test->feedback_record)}}" controls></audio>
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
    <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>

    <script>
        //webkitURL is deprecated but nevertheless
        URL = window.URL || window.webkitURL;

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        var filename = '';
        var testerAudio = '';
        var gumStream; 						//stream from getUserMedia()
        var rec; 							//Recorder.js object
        var input; 							//MediaStreamAudioSourceNode we'll be recording

        // shim for AudioContext when it's not avb.
        var AudioContext = window.AudioContext || window.webkitAudioContext;
        var audioContext //audio context to help us record

        var recordButton = document.getElementById("start-btn");
        var stopButton = document.getElementById("stop-btn");
        var deleteButton = document.getElementById("delete-btn");
        var recordingsList = document.getElementById("recordingslist");

        //add events to those 2 buttons
        recordButton.addEventListener("click", startRecording);
        stopButton.addEventListener("click", stopRecording);
        deleteButton.addEventListener("click", deleteRecording);

        function startRecording() {
            console.log("recordButton clicked");

            /*
            Simple constraints object, for more advanced audio features see
            https://addpipe.com/blog/audio-constraints-getusermedia/
            */

            var constraints = {audio: true, video: false}

            /*
            Disable the record button until we get a success or fail from getUserMedia()
            */

            recordButton.disabled = true;
            stopButton.disabled = false;
            deleteButton.disabled = true

            /*
            We're using the standard promise based getUserMedia()
            https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
            */

            navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
                console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

                /*
                create an audio context after getUserMedia is called
                sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
                the sampleRate defaults to the one set in your OS for your playback device

                */
                audioContext = new AudioContext();

//update the format
// document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

                /*  assign to gumStream for later use  */
                gumStream = stream;

                /* use the stream */
                input = audioContext.createMediaStreamSource(stream);

                /*
                Create the Recorder object and configure to record mono sound (1 channel)
                Recording 2 channels  will double the file size
                */
                rec = new Recorder(input, {numChannels: 1})

//start the recording process
                rec.record()

                console.log("Recording started");

            }).catch(function (err) {
//enable the record button if getUserMedia() fails
                recordButton.disabled = false;
                stopButton.disabled = true;
                deleteButton.disabled = true
            });
        }

        function deleteRecording() {
            console.log("deleteButton clicked rec.recording=", rec.recording);
            event.stopPropagation();
            $('#recordingslist').empty();
            testerAudio = '';
// Disable Record button and enable stop button !
            recordButton.disabled = false;
            stopButton.disabled = true;
            deleteButton.disabled = true;
        }

        function stopRecording() {
            console.log("stopButton clicked");

//disable the stop button, enable the record too allow for new recordings
            stopButton.disabled = true;
            recordButton.disabled = true;
            deleteButton.disabled = false;

//reset button just in case the recording is stopped while paused
// deleteButton.innerHTML="Pause";

//tell the recorder to stop the recording
            rec.stop();

//stop microphone access
            gumStream.getAudioTracks()[0].stop();

//create the wav blob and pass it on to createDownloadLink
            rec.exportWAV(createDownloadLink);
        }

        function createDownloadLink(blob) {
            testerAudio = blob;
            var url = URL.createObjectURL(blob);
            var au = document.createElement('audio');
            var li = document.createElement('li');

            // $('#recordingslist').append(`<li><audio style="height: 33px;" src="${url}" controls></audio><li>`)
// var link = document.createElement('a');

//name of .wav file to use during upload and download (without extendion)
            filename = new Date().toISOString();
//
//add controls to the <audio> element
            au.controls = true;
            au.src = url;

// //save to disk link

//add the new audio element to li
            li.appendChild(au);


//add the li element to the ol
            recordingsList.appendChild(li);
        }


        $("#form_information").submit(function (e) {
            e.stopPropagation();
            e.preventDefault();
            $('#save').prop('disabled', true);
            $('#save').text('{{t('Please wait, saving ...')}}');

            document.getElementById("start-btn").disabled = true;
            document.getElementById("stop-btn").disabled = true;
            document.getElementById("delete-btn").disabled = true;
            if (testerAudio == '') {
                testerAudio = new Blob(['no file'], {type: "text/plain"});
            }

            uploadBlob(testerAudio);

            function uploadBlob(testerAudio) {
                var reader = new FileReader();
                // this function is triggered once a call to readAsDataURL returns
                reader.onload = function (event) {
                    var fd = new FormData($("#form_information")[0]);
                    fd.append('record1', testerAudio, filename);
                    console.log(fd);
                    $.ajax({
                        url: '{{ route('school.lessons_tests.correct', $user_test->id) }}',
                        data: fd,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            // xhr.onprogress = function e() {
                            //     // For downloads
                            //     if (e.lengthComputable) {
                            //         console.log(e.loaded / e.total);
                            //     }
                            // };
                            // xhr.upload.onprogress = function (e) {
                            //     $('.progress').show();
                            //     $('#message').show();
                            //     var percent_value = 0;
                            //     var position = event.loaded || event.position;
                            //     var total = event.total;
                            //     if (event.lengthComputable) {
                            //         percent_value = Math.ceil(position / total * 100);
                            //     }
                            //     //update progressbar
                            //     var percentVal = percent_value + '%';
                            //     bar.width(percentVal)
                            //     percent.html(percentVal);
                            // };
                            return xhr;
                        },
                        success: function (data) {
                            toastr.success("تم إعتماد التصحيح بنجاح");
                        },
                        error: function (data) {
                            $('#save').prop('disabled', false);
                            $('#save').text("{{ t('Save') }}");
                            document.getElementById("start-btn").disabled = false;
                            document.getElementById("stop-btn").disabled = true;
                            document.getElementById("delete-btn").disabled = false;

                        }
                    }).done(function (data) {
                        setTimeout(function () {
                            window.location.href = "{{route('school.lessons_tests.index')}}";
                        }, 500);
                    });
                };
                // trigger the read from the reader...
                reader.readAsDataURL(testerAudio);
            }
        });

    </script>

@endsection

