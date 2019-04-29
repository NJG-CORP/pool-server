<?php


namespace App\Http\Controllers\Web\Events;


use App\Http\Controllers\Web\Controller;
use App\Services\EventsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{

    public function list()
    {
        return view('site.events.index', ['data' => (new EventsService())->getList()]);
    }

    public function view($url)
    {
        $event = (new EventsService())->getEventByUrl($url);
        $more_events = (new EventsService())->getMoreEvents($event->id);
        return view('site.events.event', compact('event', 'more_events'));
    }

    public function viewId($id)
    {
        $event = (new EventsService())->getEvent($id);
        if (!$event) {
            throw new NotFoundHttpException;
        }

        return redirect()->route('eventItem', ['url' => $event->url]);
    }

}