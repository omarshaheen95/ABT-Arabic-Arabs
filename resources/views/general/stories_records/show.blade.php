@extends(getGuard().'.layout.container')
@section('title',$title)
@push('breadcrumb')
    <li class="breadcrumb-item b text-muted">
        {{$title}}|{{$user_record->story->name}}
    </li>
@endpush
@section('actions')
    <a target="_blank" href="{{route('manager.story.review',[$user_record->story_id,'training'])}}" class="btn btn-primary btn-sm">{{t('Preview Story')}}</a>
@endsection
@section('style')
    <link href="{{asset('assets_v1/lib/recording/css/recorder.css')}}?v={{time()}}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <form enctype="multipart/form-data" id="form_information"
          action="{{ route(getGuard().'.stories_records.update', $user_record->id) }}" method="post">
        @csrf
        @if(isset($user_record))
            @method('PATCH')
        @endif
        <div class="row gap-3">
            <div class="form-group row  align-items-center">
                <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Record answer') }}</label>
                <div class="col-lg-9 d-flex align-items-center">
                    <audio src="{{asset($user_record->record)}}" controls></audio>

                    <div class="ms-auto d-flex flex-column" style="width: 130px">
                        <div>{{t('Mark')}}:</div>
                        <input class="form-control" name="mark" type="number" placeholder="{{t('Mark')}}"
                               value="{{ $user_record->mark }}" min="0" max="10">
                    </div>

                </div>

            </div>
            <div class="col-12 form-group row mb-2">
                <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Feedback') }}</label>
                <div class="col-lg-9 col-xl-6">
                            <textarea class="form-control"
                                      name="feedback_message">{{$user_record->feedback_message}}</textarea>
                </div>
            </div>
            <div class="col-12 form-group row mb-2">
                <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Record your Feedback') }}</label>
                <div class="col-lg-9 col-xl-9">
                    <div class="recorder-box mt-2" id="recorder-1">
                        <div class="controls">
                            <input type="hidden" id="recorder_url_1" data-id="1" class="recorder_url">
                            <!-- Start Voice -->
                            <div class="icon start-voice startRecording">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                     fill="currentColor" class="bi bi-mic" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
                                    <path fill-rule="evenodd"
                                          d="M10 8V3a2 2 0 1 0-4 0v5a2 2 0 1 0 4 0zM8 0a3 3 0 0 0-3 3v5a3 3 0 0 0 6 0V3a3 3 0 0 0-3-3z"/>
                                </svg>
                                <span class="ms-2">سجل ملاحظاتك -  Record your feedback</span>
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
                    @if($user_record && !is_null($user_record->feedback_audio_message) )
                        <audio src="{{asset($user_record->feedback_audio_message)}}" controls></audio>
                    @endif
                </div>
            </div>
            <div class="col-12 row align-items-center">
                <label class="col-3">{{ t('Status') }}</label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_status" value="pending" @if($user_record->status == 'pending') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Waiting list'))}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_status" value="corrected"  @if($user_record->status == 'corrected') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Marking Completed'))}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="s_status" value="returned" @if($user_record->status == 'returned') checked @endif>
                            <label class="form-check-label" for="section">{{t(ucfirst('Send back'))}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row align-items-center">
                <label class="col-3">{{ t('Show as model') }}</label>
                <div class="col-6">
                    <div class="d-flex gap-2 p-4 border-secondary ms-1" style="border: 1px solid;border-radius: 5px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="0"  @if(!$user_record->approved) checked @endif>
                            <label class="form-check-label" for="section">{{t('Do not Show as model')}}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approved" value="1" @if($user_record->approved) checked @endif>
                            <label class="form-check-label" for="section">{{t('Show as model')}}</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator mt-4"></div>
            @can('marking user records')
                <div class="d-flex justify-content-end">
                    <button type="submit" id="save" class="btn btn-primary">{{ t('Save') }}</button>&nbsp;
                </div>

            @endcan
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}?v={{time()}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\General\UpdateUserRecordRequest::class, '#form_information'); !!}
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
                    window.location.href = "{{route(getGuard().'.stories_records.index')}}";
                } else {
                    toastr.error(data.message);
                }
            }).fail(function (jqXHR, textStatus, error) {
                toastr.error(jqXHR.responseJSON.message);

            });
        })

    </script>

@endsection
