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
            ->where(['id' => $id])
            ->first();
    }

    public function getMoreEvents($id) {
        return Events::with(['images'])
            ->limit(2)
            ->whereNotIn('id', [$id])
            ->get() ?? [];
    }

    public function getEventByUrl(string $url)
    {
        return Events::with(['images'])
            ->where(['url' => $url])
            ->first();

    }

}