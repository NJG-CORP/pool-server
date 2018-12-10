<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest\NewsRequest;
use App\Services\AdminPanel\NewsService;
use App\Models\Image;
use App\Models\News;

class NewsController extends Controller
{
    /* 
	 @role: get dependancy to use in the class
	 
	 @comments: 
	 */ 
	 
	 public function __construct(NewsService $newsClass) 
	 { 
	 	
	 	$this->newsClass = $newsClass;

	 	
	 
	 }


	 /* 
	  @role: 
	  
	  @comments: 
	  */ 
	  
	  public function getNewsList()
	  { 
	  	
	  		$news_list_data = $this->newsClass->getNewsData();

	  		
	  		
	  		return view('admin_panel.news.list')->with(compact('news_list_data'));
	  		
	  
	  }

	  /* 
	   @role: to display form to add new news 
	   
	   @comments: returns view
	   */ 
	   
	   public function addNewsForm() 
	   { 	
	   		
	   		return view('admin_panel.news.add_new');
	   
	   }


	   /* 
	    @role: to save news Data if it passes the validation
	    
	    @comments: 
	    */ 
	    
	    public function saveNews(NewsRequest $request) 
	    { 
	    	
	    	

	    	$news_process = (new NewsService)->saveNews($request);

	    	if($news_process)
	    	{
	    	\Session::flash('success','News Added Successfully');

	  		return redirect()->route('get:all:news');
	  
	  
	    	}	
	    
	    
	    }

	    /* 
	   @role: to display form to to edit a given news
	   
	   @comments: returns view
	   */ 
	   
	   public function editNewsForm($id) 
	   { 	

	   		
	   		// get the selected news for edit 
	   		$news = $this->newsClass->getOne($id);

	   		

	   		if($news->gallery_images!='')
	   		$im_arr  = unserialize($news->gallery_images);

	   		else 
	   		$im_arr = []; 	

	   		$gallery_images = Image::whereIn('id',$im_arr)->get();

	   		

	   		// get additional images if any
	   		$additional_images = $news->images->where('id','!=',$news->mainImg)->whereNotIn('id',$im_arr);
	   		

	   		return view('admin_panel.news.edit')->with(compact('news','additional_images','gallery_images'));
	   
	   }

	   /* 
	    @role: update specific news
	    
	    @comments: 
	    */ 
	    
	    public function updateNews(NewsRequest $request) 
	    { 
	    	

	    	$news_process = (new NewsService)->saveNews($request);

	    	if($news_process)
	    	{
	    	\Session::flash('success','News Updated Successfully');

	  		return redirect()->route('get:all:news');
	    	}
	    
	    }


	    /* 
	     @role: remove image from news
	     
	     @comments: 
	     */ 
	     
	     public function removeNewsImage(Request $request) 
	     { 
	     
	     	$image = Image::find($request->id);;		

	     	if(file_exists('/assets/images/'.$image->path))
	     	unlink('/assets/images/'.$image->path);
	     	
	     	$image->delete();	
	     	return $request->id;
	     }

	     /* 
	      @role: remove entire news, also images associated with it
	      
	      @comments: 
	      */ 
	      
	      public function removeNews(Request $request) 
	      { 
	      		

	      		$news = News::findorFail($request->id);

	      		$news_id = $news->id;
	      		
	      		$images = $news->images;

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
	      		

	      		// remove news from db
	      		$news->delete();

	      		return $news_id;



	      
	      
	      }
    
}
