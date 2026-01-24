<!-- Matching Question -->
<div class="question-container" id="question{{$question->id}}" data-question-id="{{$question->id}}" data-question-type="matching" style="display: {{$loop->iteration==1?'block':'none'}};">
    <div class="question-header">
        <div class="question-text">
            <h2 class="question-title">اسحب الإجابات الى الأماكن الصحيحية في الاسفل</h2>
        </div>
    </div>

    <div class="question-divider"></div>

    <div class="question-content">
        <div class="question-content-text">
            <p class="question-content-title">{!! $question->content !!}</p>
        </div>

        @if($question->attachment)
            <div class="question-audio-player">
                @if(str_contains($question->attachment, '.mp3') || str_contains($question->attachment, '.wav'))
                    <button class="audio-play-button" data-audio-url="{{asset($question->attachment)}}" data-question-id="{{$question->id}}">
                        <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.0538 2.95684C19.7631 2.76539 19.4227 2.66612 19.0752 2.67321C15.9552 2.67321 10.4101 9.72157 9.52378 10.6079C8.87142 11.2461 8.29706 11.9623 7.80778 12.7352C6.43924 12.5792 4.12051 12.7848 2.97178 13.7776C2.14434 14.6398 1.66021 15.7745 1.61033 16.9685C1.41178 19.1383 1.61033 23.4638 2.77324 24.655C4.12051 26.0165 6.7016 25.7825 6.92851 25.7683C7.15542 25.7541 7.48869 25.7328 7.83615 25.7257C8.08433 26.2008 9.30397 27.853 9.70106 28.3636C10.9703 30.0016 15.2674 35.0858 17.2103 36.0785C17.5436 36.2628 17.9194 36.355 18.3023 36.3621C21.0961 36.3621 21.4223 31.4694 21.5712 24.5628V23.3361C22.0038 7.58012 21.2734 3.80066 20.0538 2.95684Z" fill="white"/>
                            <path d="M27.4064 14.1317C26.7611 13.6354 25.896 13.5219 25.1444 13.8339C24.7854 13.9803 24.4713 14.2186 24.2336 14.5247C23.9959 14.8309 23.8429 15.1943 23.79 15.5783V15.7839C23.7687 16.5285 24.1091 17.2375 24.7047 17.6843C25.2436 18.1239 25.5485 18.7975 25.5131 19.4995C25.4918 20.0243 25.2862 20.5277 24.9387 20.9177C24.6045 21.2794 24.393 21.7374 24.3345 22.2263C24.2759 22.7153 24.3732 23.2103 24.6125 23.6406C24.7983 23.9917 25.0758 24.2859 25.4154 24.4919C25.755 24.6979 26.1441 24.8081 26.5413 24.8106C26.676 24.8106 26.8178 24.7965 26.9525 24.7681C27.1015 24.7397 27.2433 24.6972 27.3851 24.6475C27.5127 24.5908 27.6333 24.5199 27.7396 24.4348C30.5051 21.9885 30.7675 17.7552 28.3211 14.9826C28.0375 14.6706 27.7326 14.387 27.4064 14.1317Z" fill="white"/>
                            <path d="M31.1289 8.28969C30.5888 8.02119 29.9644 7.97711 29.3919 8.16706C28.8194 8.357 28.3452 8.76557 28.0727 9.30369C27.814 9.82086 27.7617 10.4171 27.9263 10.9714C28.0909 11.5257 28.4601 11.9968 28.9591 12.289C32.0082 14.1397 32.9371 17.7348 32.7953 20.4719C32.6109 23.0033 31.3346 25.3291 29.3066 26.8537C28.7251 27.3004 28.3989 28.0095 28.4415 28.7399C28.4627 29.0448 28.5478 29.3426 28.6826 29.612C28.9733 30.1935 29.5051 30.619 30.1362 30.7679C30.3205 30.8175 30.512 30.8388 30.7035 30.8388C31.1927 30.8388 31.6678 30.6899 32.0649 30.3991C35.192 28.0662 37.1491 24.4853 37.4186 20.5924C37.8298 14.9835 35.5395 10.5091 31.1289 8.28969Z" fill="white"/>
                        </svg>
                    </button>
{{--                    <div class="audio-player training-audio-player" data-question-id="{{$question->id}}">--}}
{{--                        <audio preload="metadata">--}}
{{--                            <source src="{{asset($question->attachment)}}" type="audio/mpeg">--}}
{{--                            Your browser does not support the audio element.--}}
{{--                        </audio>--}}
{{--                    </div>--}}
                @else
                    <div class="question-image">
                        <img src="{{asset($question->attachment)}}" alt="Question Image" style="max-width: 100%; border-radius: 8px;">
                    </div>
                @endif
            </div>
        @endif

        <div class="drag-drop-container">
            <!-- Draggable Items (content from matches) -->
            <div class="drag-items-container">
                @foreach($question->matches->shuffle() as $match)
                    <div class="drag-item" draggable="false" data-item-id="{{$match->uid}}">
                        {!! $match->content !!}
                    </div>
                @endforeach
            </div>

            <!-- Drop Zones (results from matches) -->
            <div class="drop-zones-container">
                @foreach($question->matches as $match)
                    <div class="drop-zone-container">
                        <div class="drop-zone-label-container @if($match->image) has-image @endif">
                            @if($match->image)
                                <img src="{{asset($match->image)}}" alt="{{$match->result}}" class="drop-zone-label-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            @endif
                            <div class="drop-zone-label" @if($match->image) style="display: none;" @endif>
                                {!! $match->result !!}:
                            </div>
                        </div>
                        <div class="drop-zone" data-zone-id="{{$match->id}}">
                            <div class="drop-zone-items"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Answer Feedback for this question -->
        <div class="answer-feedback" id="answerFeedback_{{$question->id}}" style="display: none;"></div>
    </div>
</div>
