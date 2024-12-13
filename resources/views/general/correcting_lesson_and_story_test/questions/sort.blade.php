<div id="{{$counter}}" class="exercise-box @if($loop->first) active @endif  question-item">
    <div class="exercise-box-header text-center">
        <span class="number"> {{$counter}} : </span>
        <span class="title"> اسحب الإجابات إلى الأماكن الصحيحة في الأسفل –  Drag and order the answers in the below box</span>
    </div>
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
                    <div class="answers">
                        <div class="answer-box justify-content-center mt-2">
                            <div class="answers" data-question="{{$question->id}}">
                                <div class="row justify-content-center">

                                    {{--    Start With Options Answers     --}}
                                    <div class="col-md-12 mb-3">
                                        <div data-question="{{$question->id}}" id=""
                                             class="sortOptions sortConnected list-unstyled font-bold text-center d-flex justify-content-around">
                                            @php
                                                if (isset($story)){
                                                    $words = $question->sort_words->whereNotIn('uid',$question->sort_results->pluck('story_sort_answer_uid'));
                                                }elseif(isset($lesson)){
                                                    $words = $question->sortWords->whereNotIn('uid',$question->sort_results->pluck('sort_answer_uid'));
                                                }
                                            @endphp
                                            @foreach($words as $sort)
                                                <div data-question="{{$question->id}}" class="ui-state-default add-answer me-1"
                                                     data-id="{{$sort->uid}}">
                                                    <text>{{$sort->content}} </text>
                                                    <span class="float-right"></span>
                                                    <input class="sort-answer-input" type="hidden" name="sorting[{{$question->id}}][{{$sort->uid}}]" id="" value="">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Start With Questions --}}
                                    <div class="col-md-12">
                                        <div class="row item-container">
                                            <div data-question="{{$question->id}}" class="list-unstyled m-0 font-bold active text-right m-0 p-0">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="item">
                                                            <ul data-question="{{$question->id}}" data-index="{{$loop->iteration}}" class="d-flex align-items-center gap-3 px-3 sortAnswers sortWords sortConnected list-unstyled m-0 font-bold active textOnly text-center m-0 p-0">
                                                                @foreach($question->sort_results as $answer)
                                                                    @php
                                                                        if (isset($story)){
                                                                            $uid = $answer->story_sort_answer_uid;
                                                                            $content = $question->sort_words->where('uid',$uid)->first()->content;
                                                                        }elseif(isset($lesson)){
                                                                            $uid = $answer->sort_answer_uid;
                                                                            $content = $question->sortWords->where('uid',$uid)->first()->content;
                                                                        }
                                                                    @endphp
                                                                    <div data-question="{{$question->id}}" class="ui-state-default add-answer me-1 ui-sortable-handle" data-id="{{$uid}}" style="">
                                                                        <text>{{$content}} </text>
                                                                        <span class="float-right"></span>
                                                                        <input type="hidden" name="sorting[{{$question->id}}][{{$uid}}]" id="" value="{{$loop->iteration}}">
                                                                    </div>
                                                                @endforeach
                                                            </ul>
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
        </div>
    </div>


</div>

