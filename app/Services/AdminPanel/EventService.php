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
	 
	 		return Events::paginate(10);
	 
	 }

	 /* 
	  @role: save Event 
	  
	  @comments: 
	  */ 
	  
	  public function saveEvent($request) 
	  { 
	  		
	  		$event = new Events;

	  		$event->title = $request->title;
	  		$event->url = $request->url;
	  		$event->paragraph = $request->paragraph;
	  		$event->description = $request->description;
	  		$event->club_id = $request->club_id;
	  		$event->date = $request->date." ".$request->time;
	  		$event->description = $request->description;
	  		$event->save();

	  		if($event)
	  		{
	  			 // check if event has any images
	  			 if($request->mainImg!='')
	  			 {	

	  			 	(new ImageService)->create($request->file('mainImg'),$event,'abc.jpg');

	  			 }	

	  			 if($request->images!='')
	  			 {

	  			 }
	  		}	
	  
	  
	  }



}