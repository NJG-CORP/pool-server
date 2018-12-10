<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest\BlogRequest;
use App\Services\AdminPanel\BlogService;
use App\Models\Image;
use App\Models\Blogs;


class BlogController extends Controller
{
    /* 
	 @role: get dependancy to use in the class
	 
	 @comments: 
	 */ 
	 
	 public function __construct(BlogService $blogClass) 
	 { 
	 	
	 	$this->blogClass = $blogClass;

	 	
	 
	 }


	 /* 
	  @role: 
	  
	  @comments: 
	  */ 
	  
	  public function getBlogList()
	  { 
	  	
	  		$blogs_list_data = $this->blogClass->getBlogsData();

	  		
	  		
	  		return view('admin_panel.blogs.list')->with(compact('blogs_list_data'));
	  		
	  
	  }

	  /* 
	   @role: to display form to add new blog 
	   
	   @comments: returns view
	   */ 
	   
	   public function addBlogForm() 
	   { 	
	   		
	   		return view('admin_panel.blogs.add_new');
	   
	   }


	   /* 
	    @role: to save blog Data if it passes the validation
	    
	    @comments: 
	    */ 
	    
	    public function saveBlog(BlogRequest $request) 
	    { 
	    	
	    	

	    	$blog_process = (new BlogService)->saveBlog($request);

	    	if($blog_process)
	    	{
	    	\Session::flash('success','Blog Added Successfully');

	  		return redirect()->route('get:all:blogs');
	  
	  
	    	}	
	    
	    
	    }

	    /* 
	   @role: to display form to to edit a given blog
	   
	   @comments: returns view
	   */ 
	   
	   public function editBlogForm($id) 
	   { 	

	   		
	   		// get the selected blog for edit 
	   		$blog = $this->blogClass->getOne($id);

	   		

	   		if($blog->gallery_images!='')
	   		$im_arr  = unserialize($blog->gallery_images);

	   		else 
	   		$im_arr = []; 	

	   		$gallery_images = Image::whereIn('id',$im_arr)->get();

	   		

	   		// get additional images if any
	   		$additional_images = $blog->images->where('id','!=',$blog->mainImg)->whereNotIn('id',$im_arr);
	   		

	   		return view('admin_panel.blogs.edit')->with(compact('blog','additional_images','gallery_images'));
	   
	   }

	   /* 
	    @role: update specific blog
	    
	    @comments: 
	    */ 
	    
	    public function updateBlog(BlogRequest $request) 
	    { 
	    	

	    	$blog_process = (new BlogService)->saveBlog($request);

	    	if($blog_process)
	    	{
	    	\Session::flash('success','Blog Updated Successfully');

	  		return redirect()->route('get:all:blogs');
	    	}
	    
	    }


	    /* 
	     @role: remove image from blog
	     
	     @comments: 
	     */ 
	     
	     public function removeBlogImage(Request $request) 
	     { 
	     
	     	$image = Image::find($request->id);;		

	     	if(file_exists('/assets/images/'.$image->path))
	     	unlink('/assets/images/'.$image->path);
	     	
	     	$image->delete();	
	     	return $request->id;
	     }

	     /* 
	      @role: remove entire blog, also images associated with it
	      
	      @comments: 
	      */ 
	      
	      public function removeBlog(Request $request) 
	      { 
	      		

	      		$blog = Blogs::findorFail($request->id);

	      		$blog_id = $blog->id;
	      		
	      		$images = $blog->images;

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
	      		

	      		// remove blog from db
	      		$blog->delete();

	      		return $blog_id;



	      
	      
	      }
    
}
