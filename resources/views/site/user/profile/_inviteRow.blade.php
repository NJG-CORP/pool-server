<?php
/**
 * @var Invitation $invite
 */
?>use App\Models\Invitation;

<div class="news_block_div partners_block_div">

    <div class="img">
        <img src="{{$invite->inviter->getAvatarUrl()}}" alt="">

        @if($invite->isAccepted())
            <span class="mark check"></span>
        @else
            <span class="mark new"></span>
        @endif

    </div>

    <div class="text">
        <p class="name"><a
                    href="{{route('profile.card.other', ['id' => $invite->inviter->id])}}">{{$invite->inviter->getUserName()}}</a>
        </p>

        <p>
            {{$invite->inviter->getAddress()}}
        </p>

        {{--<p class="status">Статус: <span>Pro</span></p>--}}


        <p class="type">
            <span>Вид игры:</span>
            {{$invite->inviter->getGameTypes()}}
        </p>

        <div class="rating">
            <div class="stars stars_small">
                <p>Рейтинг игрока</p>
                <div>
                    <span class="star{{$invite->inviter->calculated_rating}}">{{$invite->inviter->calculated_rating}}</span>
                </div>
            </div>
        </div>
    </div>

    @if(!$invite->isAccepted())
        <div class="bottom_buttons">
            <a class="button reject_button" href="{{ url('/invite/accept', ['id' => $invite->id]) }}">Принять</a>
            <a class="button accept_button" href="{{ url('/invite/decline', ['id' => $invite->id]) }}">Отклонить</a>
        </div>
    @endif
</div>