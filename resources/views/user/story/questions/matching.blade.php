<div class="exercise-box-body">
    <div class="exercise-question">
        <div class="exercise-question-data border-0">
            <div class="info">
                {{$question->content}}
            </div>
            <div class="exercise-question-answer text-center my-4">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="answer-box justify-content-center w-100 mt-2">
                                <div class="answers" data-question="{{$question->id}}">
                                    <div class="row justify-content-center">

                                        {{-- Start With Options Answers --}}
                                        <div class="col-md-12 mb-3">
                                            <div data-question="{{$question->id}}" id=""
                                                 class="matchOptions matchConnected list-unstyled font-bold text-center d-flex justify-content-around">
                                                @foreach($question->matches->shuffle() as $match)
                                                    <div data-question="{{$question->id}}" class="ui-state-default add-answer"
                                                         data-id="{{$match->uid}}">
                                                        <text>{{$match->result}}</text>
                                                        <span class="float-right"></span>
                                                        <input class="matching-answer-input" type="hidden" name="matching[{{$question->id}}][{{$match->uid}}]" id="" value="">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Start With Questions --}}
                                        <div class="col-md-12">
                                            <div class="row item-container">
                                                <div data-question="{{$question->id}}" class="list-unstyled m-0 font-bold active text-right m-0 p-0">
                                                    @foreach($question->matches as $match)
                                                        <div class="row">
                                                            <div class="col-md-8 mb-2">
                                                                <div class="ui-state-default mb-2 question-option item">
                                                                    @if(!is_null($match->image))
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-md-12 text-center">
                                                                                <img src="{{asset($match->image)}}" class="match-img" />
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <span class="ml-3"></span>
                                                                        <text class="fs-5 fw-bold">{{$match->content}}</text>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-2 matching-answers-box">
                                                                <div class="item match-item" style="border: 2px dashed #5d87e8;border-radius: 10px">
                                                                    <ul data-question="{{$question->id}}" data-index="{{$match->id}}"
                                                                        class="matchAnswers m-0 matchWords matchConnected list-unstyled d-flex align-items-center justify-content-center">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
