<?php
/**
 * @var $event \App\Models\Events
 */
?>
<div class="news_block_div activities_block_div">
    <span class="date">{{date('d.m.Y', strtotime($event->created_at))}}</span>

    <p class="title"><a href="{{$event->getUrl()}}">{{$event->title}}</a></p>

    <div class="img">
        <a href="{{$event->getUrl()}}"><img src="{{$event->getMainImage()}}" alt=""></a>
    </div>

    <div class="text">
        <p>
            <b>{{$event->description}}</b>
        </p>

        <p>
            <b>Адрес</b>
            <br>
            {{$event->club->location->address}}
        </p>

        <p>
            <b>Время проведения:</b><br>{{$event->date}}
        </p>
    </div>
</div>