<div class="answer-box justify-content-center mt-2">
    <div class="answers" data-question="{{$question->id}}">
        <div class="row justify-content-center">

            {{--    Start With Options Answers     --}}
            <div class="col-md-12 mb-3">
                <div data-question="{{$question->id}}" id=""
                     class="sortOptions sortConnected list-unstyled font-bold text-center d-flex justify-content-around">
                    @foreach($question->sortWords->shuffle() as $sort)
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
