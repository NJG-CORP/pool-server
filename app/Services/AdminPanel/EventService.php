<?php

namespace App\Services\AdminPanel;

use App\Models\Events;
use App\Services\ImageService;


class EventService
{


    /*
     @role: get all events data with 10 in each page

     @comments:
     */

    public function getEventsData()
    {

        return Events::orderBy('id', 'desc')->paginate(10);

    }

    /*
     @role: get specific event details

     @comments:
     */

    public function getOne($id)
    {

        return Events::with('images')->findOrFail($id);


    }

    /*
     @role: save Event

     @comments:
     */

    public function saveEvent($request)
    {

        if ($request->event_id > 0) // if we have update request
            $event = Events::findOrFail($request->event_id);
        else    // add new
            $event = new Events;

        $event->title = $request->title;
        $event->name = $request->name;
        $event->url = $request->url;
        $event->paragraph = $request->paragraph;
        $event->description = $request->description;
        $event->club_id = $request->club_id;
        $event->date = $request->date . " " . $request->time;
        $event->description = $request->description;
        $event->save();


        if ($event) {
            // check if event has any images
            if ($request->mainImg != '') {


                //echo $_FILES['mainImg']['tmp_name']; exit;
                $main_image = (new ImageService)->create($request->file('mainImg'), $event, $request->file('mainImg')->getClientOriginalName());

                // now save main image id to event table
                $event->mainImg = $main_image->id;
                $event->save();

            }

            if ($request->images != '') {

                foreach ($request->images as $key => $image) {

                    // save additional images
                    (new ImageService)->create($image, $event, $image->getClientOriginalName());


                }

            }

            return true;

        } else
            return redirect()->route('get:all:events');

    }


}