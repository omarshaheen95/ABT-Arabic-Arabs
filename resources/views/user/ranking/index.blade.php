@extends('user.layout')
@push('style')
    <link rel="stylesheet" href="{{asset('user_assets/css/pages/ranking.css')}}?v=2" />
@endpush
@section('page-name', 'ranking')

@section('content')
    <section class="ranking-content">
        <div class="competition-banner">
            <div class="ranking-badges-grid">
                @foreach($achievementLevels as $index => $level)
                @php
                    $currentIndex = $achievementLevels->search(function($item) use ($currentAchievementLevel) {
                        return $currentAchievementLevel && $item->id == $currentAchievementLevel->id;
                    });
                    $isActive = $currentAchievementLevel && $currentAchievementLevel->id == $level->id;
                    $isCompleted = $currentIndex !== false && $index < $currentIndex;
                    $isLocked = $currentIndex !== false && $index > $currentIndex;
                @endphp
                <div class="ranking-badge {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isLocked ? 'locked' : '' }}" role="listitem">
                    <img
                        src="{{ $level->badge_icon ? asset($level->badge_icon) : asset('user_assets/images/illustrations/badge.svg') }}"
                        alt="{{ $level->name }}"
                    />
                    <div class="badge-tooltip">
                        <div class="tooltip-content">
                            <h4 class="tooltip-level-name">{{ $level->name }}</h4>
                            <p class="tooltip-points">{{ $level->required_points }} XP</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="banner-info">
                <h1 class="competition-title">{{ $currentAchievementLevel ? $currentAchievementLevel->name : t('No Level') }}</h1>
                <p class="competition-description">{{t('The top three advance to the next level')}}</p>
{{--                <span class="time-remaining">متبقي يومان</span>--}}
            </div>
        </div>
        <div class="ranking-container">
            <div class="participants-list">
                @forelse($usersInSameLevel as $index => $userLevel)
                    @php
                        $isCurrentUser = $userLevel->user_id == auth()->id();
                        $rank = $index + 1;
                        $medalIcons = [
                            1 => 'first.svg',
                            2 => 'second.svg',
                            3 => 'third.svg'
                        ];
                    @endphp

                    <article class="participant-card {{ $isCurrentUser ? 'participant-card--current-user' : '' }}">
                        <div class="participant-info">
                            @if($rank <= 3)
                            <div class="medal-container">
                                <img class="medal-icon" src="{{asset('user_assets/images/illustrations/' . $medalIcons[$rank])}}" alt="Rank {{ $rank }}" />
                            </div>
                            @endif

                            @if($isCurrentUser)
                                <div class="user-avatar {{in_array($rank,range(1,3))?'rank-'.$rank:'rank-other'}}" style="background-image: url('{{ $userLevel->user->image ? asset($userLevel->user->image) : asset('user_assets/images/illustrations/avatar.svg') }}')">
                                </div>
                                <h3 class="participant-name-current">{{ $userLevel->user->name ?? 'Unknown' }}</h3>
                            @else
                                <div class="user-avatar {{in_array($rank,range(1,3))?'rank-'.$rank:'rank-other'}}" style="background-image: url('{{ $userLevel->user->image ? asset($userLevel->user->image) : asset('user_assets/images/illustrations/avatar.svg') }}')"></div>
                                <h3 class="participant-name{{$rank==1?'-first':''}}">{{ $userLevel->user->name ?? 'Unknown' }}</h3>
                            @endif
                        </div>
                        <span class="{{ $isCurrentUser ? 'participant-score-current' : 'participant-score' }}">{{ $userLevel->points }} XP</span>
                    </article>
                @empty
                    <p>{{t('No participants in this level yet')}}.</p>
                @endforelse
            </div>

        </div>
    </section>
@endsection

@push('script')
    <script src="{{asset('user_assets/js/pages/ranking.js')}}"></script>
@endpush
