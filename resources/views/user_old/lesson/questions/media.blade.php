{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    --}}
@if(!is_null($question->attachment))

    <div class="row justify-content-center py-3">
        <div class="col-lg-6 col-md-8">
            @if(\Illuminate\Support\Str::contains($question->attachment, '.mp3'))
                <div class="recorder-player"
                     id="voice_audio_2">
                    <div class="audio-player">
                        <audio crossorigin>
                            <source
                                src="{{asset($question->attachment)}}"
                                type="audio/mpeg">
                        </audio>
                    </div>
                </div>
            @else
                <div class="w-100 text-center">
                    <img
                        src="{{asset($question->attachment)}}"
                        width="300px">
                </div>
            @endif
        </div>
    </div>
@endif
