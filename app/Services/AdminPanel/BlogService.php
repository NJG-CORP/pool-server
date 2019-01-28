<?php
namespace App\Services\AdminPanel;

use App\Models\Blog;
use App\Services\ImageService;


class BlogService
{


	/* 
	 @role: get all events data with 10 in each page
	 
	 @comments: 
	 */ 
	 
	 public function getBlogsData() 
	 {

         return Blog::orderBy('id', 'desc')->paginate(10);
	 
	 }

	 /* 
	  @role: get specific event details
	  
	  @comments: 
	  */ 
	  
	  public function getOne($id) 
	  {

          return Blog::with('images')->findOrFail($id);
	  
	  
	  }
	 /* 
	  @role: save Blog 
	  
	  @comments: 
	  */ 
	  
	  public function saveBlog($request) 
	  { 
	  		
	  		if($request->blog_id>0) // if we have update request
                $blog = Blog::findOrFail($request->blog_id);
	  		else	// add new 
                $blog = new Blog;

	  		$blog->title = $request->title;
          $blog->name = $request->name;
	  		$blog->url = $request->url;
	  		$blog->paragraph = $request->paragraph;
	  		$blog->description = $request->description;
	  		
	  		
	  		$blog->description = $request->description;
	  		$blog->gallery_title = $request->gallery_title;
	  		$blog->save();


	  		
	  		if($blog)
	  		{
	  			 // check if event has any images
	  			 if($request->mainImg!='')
	  			 {	


	  			 	//echo $_FILES['mainImg']['tmp_name']; exit;
	  			 	$main_image = (new ImageService)->create($request->file('mainImg'),$blog,$request->file('mainImg')->getClientOriginalName());

	  			 	// now save main image id to event table
	  			 	$blog->mainImg = $main_image->id;
	  			 	$blog->save();

	  			 }	

	  			 if($request->images!='')
	  			 {	
	  			 	
	  			 	foreach ($request->images as $key => $image) {
	  			 		 
	  			 		 // save additional images	
	  			 		 (new ImageService)->create($image,$blog,$image->getClientOriginalName());


	  			 	}

	  			 }


	  			 

	  			 if($request->gallery_images!='')
	  			 {	


	  			 	$blog_image_array = [];
	  			 	
	  			 	foreach ($request->gallery_images as $key => $image) {
	  			 		 
	  			 		 // save additional images	
	  			 		 $blog_g_images = (new ImageService)->create($image,$blog,$image->getClientOriginalName());

	  			 		 array_push($blog_image_array,$blog_g_images->id);

	  			 	}

	  			 	

	  			 	if($blog->gallery_images!='')
	   				$im_arr  = unserialize($blog->gallery_images);

	   				else 
	   				$im_arr = []; 	

	   				

	  			 	$blog->gallery_images = serialize(array_merge($blog_image_array,$im_arr));
	  			 	$blog->save();

	  			 }
	  			 


	  	 		return true;
	  	 				 
	  		}	

	  		else 
	  		return redirect()->route('get:all:blogs');	

	  }





}