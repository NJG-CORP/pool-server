<?php
/**
 * $review Review
 */
?>

@if($review->rater)
    <div class="partners_div" style="">
        <div class="img">
            <img src="{{$review->rater->getAvatarUrl()}}" alt="">
            <p class="name">{{$review->rater->name}}</p>
            <p class="date">{{date('d.m.Y', strtotime($review->created_at))}}</p>
        </div>
        <div class="text">
            <p>
                {{$review->comment}}
            </p>
        </div>
    </div>
@endif