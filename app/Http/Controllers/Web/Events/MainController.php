<?php


namespace App\Http\Controllers\Web\Events;


use App\Http\Controllers\Web\Controller;
use App\Models\Events;
use App\Services\EventsService;

class MainController extends Controller
{

    public function list()
    {
        return view('site.events.list', [ 'data' => (new EventsService())->getList()]);
    }

    public function view($id)
    {
        return view('site.events.item', ['event' => (new EventsService())->getEvent($id)]);
    }

}