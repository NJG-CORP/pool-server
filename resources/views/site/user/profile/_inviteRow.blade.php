<?php
/**
 * @var \App\Models\Invitation $invite
 */
?>

<div class="players_table_row">
    <div class="img" style="cursor: pointer"
         onclick="window.open('{{ url('user', [$invite->inviter->id]) }}')">
        {{--<img src="{{$invite->inviter->avatar->getUrlAttribute() }}" alt="">--}}
    </div>

    <div class="specs">

        <div class="text" style="cursor: pointer"
             onclick="window.open('{{ url('user', [$invite->inviter->id]) }}')">
            <p class="name"> {{ $invite->inviter->getUserName() }}, {{$invite->inviter->age ? ($invite->inviter->age . 'лет') : ''}} </p>

            <p> {{ $invite->inviter->location ? $invite->inviter->location->address : ''}} </p>
        </div>
        <div class="stars">
            <span class="star{{$invite->inviter->calculated_rating ? $invite->inviter->calculated_rating : '0' }}"></span>
        </div>
        <div class="descr">
            <p>Вид игры: @foreach($invite->inviter->gameType as $gameType) {{$gameType->term->name}} @endforeach</p>
        </div>
        @if($invite->inviter->isPro())<span class="status_span">Pro</span>@endif

        @if(!$invite->accepted)
        <a class="button invite_button" href="{{ url('/invite/accept', ['id' => $invite->id]) }}">Принять</a>
        <a class="button invite_button" href="{{ url('/invite/decline', ['id' => $invite->id]) }}">Отклонить</a>
        @else
        <a class="button invite_button" href="{{ url('/chat/user', ['id' => $invite->inviter->id]) }}>">Пообщаться</a>
        @endif
    </div>
</div>