<?php


namespace App\Services;


use App\Models\Events;

class EventsService
{
    public function getList() {
        return Events::with(['images'])
            ->get();
    }

    public function getEvent($id) {
        return Events::with(['images'])
            ->first();
    }

}