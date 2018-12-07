<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AdminPanel\EventService;
use App\Models\Club;
use App\Models\Image;
use App\Models\Events;
use App\Http\Requests\AdminRequest\EventRequest;

class EventController extends Controller
{


	/* 
	 @role: get dependancy to use in the class
	 
	 @comments: 
	 */ 
	 
	 public function __construct(EventService $eventClass) 
	 { 
	 	
	 	$this->eventClass = $eventClass;

	 	
	 
	 }


	 /* 
	  @role: 
	  
	  @comments: 
	  */ 
	  
	  public function getEventList() 
	  { 
	  	
	  		$events_list_data = $this->eventClass->getEventsData();

	  		
	  		
	  		return view('admin_panel.events.list')->with(compact('events_list_data'));
	  		
	  
	  }

	  /* 
	   @role: to display form to add new event 
	   
	   @comments: returns view
	   */ 
	   
	   public function addEventForm() 
	   { 	
	   		// get all the clubs for drop down menu
	   		$clubs = Club::all();

	   		return view('admin_panel.events.add_new')->with(compact('clubs'));
	   
	   }


	   /* 
	    @role: to save event Data if it passes the validation
	    
	    @comments: 
	    */ 
	    
	    public function saveEvent(EventRequest $request) 
	    { 
	    	
	    	
	    	$event_process = (new EventService)->saveEvent($request);

	    	if($event_process)
	    	{
	    	\Session::flash('success','Event Added Successfully');

	  		return redirect()->route('get:all:events');
	  
	  
	    	}	
	    
	    
	    }

	    /* 
	   @role: to display form to to edit a given event
	   
	   @comments: returns view
	   */ 
	   
	   public function editEventForm($id) 
	   { 	

	   		
	   		// get the selected event for edit 
	   		$event = $this->eventClass->getOne($id);

	   		// get additional images if any
	   		$additional_images = $event->images->where('id','!=',$event->mainImg);


	   		// get all the clubs for drop down menu
	   		$clubs = Club::all();



	   		return view('admin_panel.events.edit')->with(compact('clubs','event','additional_images'));
	   
	   }

	   /* 
	    @role: update specific event
	    
	    @comments: 
	    */ 
	    
	    public function updateEvent(EventRequest $request) 
	    { 
	    	

	    	$event_process = (new EventService)->saveEvent($request);

	    	if($event_process)
	    	{
	    	\Session::flash('success','Event Updated Successfully');

	  		return redirect()->route('get:all:events');
	    	}
	    
	    }


	    /* 
	     @role: remove image from event
	     
	     @comments: 
	     */ 
	     
	     public function removeEventImage(Request $request) 
	     { 
	     
	     	$image = Image::find($request->id);;		

	     	if(file_exists('/assets/images/'.$image->path))
	     	unlink('/assets/images/'.$image->path);
	     	
	     	$image->delete();	
	     	return $request->id;
	     }

	     /* 
	      @role: remove entire event, also images associated with it
	      
	      @comments: 
	      */ 
	      
	      public function removeEvent(Request $request) 
	      { 
	      		

	      		$event = Events::findorFail($request->id);

	      		$event_id = $event->id;
	      		
	      		$images = $event->images;

	      		// delete all images 
	      		foreach($images as $image)
	      		{
	      			 $image = Image::find($request->id);;		

			     	if($image && file_exists('/assets/images/'.$image->path))
			     	{
			     		unlink('/assets/images/'.$image->path);
			    	
				    	// remove image from DB
				    	$image->delete(); 	
			        }		

	      		}	
	      		

	      		// remove event from db
	      		$event->delete();

	      		return $event_id;



	      
	      
	      }
    
}
