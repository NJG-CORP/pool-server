<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AdminPanel\EventService;
use App\Models\Club;
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
	  	
	  		$events_list = $this->eventClass->getEventsData();

	  		
	  		return view('admin_panel.events.list')->with('events_list');
	  		
	  
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
	    	
	    	
	    	(new EventService)->saveEvent($request);
	    
	    
	    }
    
}
