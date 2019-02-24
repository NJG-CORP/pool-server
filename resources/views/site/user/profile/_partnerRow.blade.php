<div class="news_block_div partners_block_div">

    <div class="img">
        <img src="{{$partner->getAvatarUrl()}}" alt="">

        <a class="button" href="#">Выход на чат</a>
    </div>

    <div class="text">
        <p class="name"><a
                    href="{{route('profile.card.other', ['id' => $partner->id])}}">{{$partner->getUserName()}}</a>
        </p>

        <p>
            {{$partner->getAddress()}}
        </p>

        {{--<p class="status">Статус: <span>Pro</span></p>--}}


        <p class="type">
            <span>Вид игры:</span>
            {{$partner->getGameTypes()}}
        </p>

        <div class="rating">
            <div class="stars stars_small">
                <p>Рейтинг игрока</p>
                <div>
                    <span class="star{{$partner->calculated_rating}}">{{$partner->calculated_rating}}</span>
                </div>
            </div>
        </div>
    </div>
</div>