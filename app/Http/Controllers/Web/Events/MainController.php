<?php


namespace App\Http\Controllers\Web\Events;


use App\Http\Controllers\Web\Controller;
use App\Services\EventsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{

    public function list()
    {
        return view('site.events.list', [ 'data' => (new EventsService())->getList()]);
    }

    public function view($url)
    {
        return view('site.events.item', ['event' => (new EventsService())->getEventByUrl($url)]);
    }

    public function viewId($id)
    {
        $event = (new EventsService())->getEvent($id);
        if (!$event) {
            throw new NotFoundHttpException;
        }

        return redirect(null, 301)->route('eventItem', ['url' => $event->url]);
    }

}