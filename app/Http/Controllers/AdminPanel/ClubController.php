<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest\ClubValidation;
use App\Services\ClubsService;
use App\Utils\R;
use Illuminate\Http\Request;

class ClubController extends Controller
{
   

 public function __construct(ClubsService $clubs)
    {
        //parent::__construct($request);
        $this->clubs = $clubs;
    }

    public function create()
    {
    	$days=$this->clubs->getWeekDay();
    	$kitchens=$this->clubs->getallKitchensData();
        return view('admin_panel.clubs.create',compact('days','kitchens'));
    }
    public function store(ClubValidation $request)
    {
    	//dd($request->all());
      $data=$this->clubs->storingClubData($request);
       return redirect()->route('get:club:data');
    }
    public function show()
    {
        $all_club=$this->clubs->getAllClubsData();
        
         return view('admin_panel.clubs.show')->with(compact('all_club'));
    }
    public function edit($id)
    {
        $data=$this->clubs->editClubData($id);
        $kitchens=$this->clubs->getallKitchensData();
        $days=$this->clubs->getWeekDay();
        //$worktime=$this->clubs->getWorktime($id);
        $gallery_images = $this->clubs->getGalleryImages($id);
          if(empty($data)){

                return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }
        return view('admin_panel.clubs.edit', compact('data', 'kitchens', 'days', 'worktime', 'gallery_images'));
    }
    public function update(ClubValidation $request)
    {
        $data=$this->clubs->updateDetails($request);
          if(empty($data)){

                return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }
        return redirect()->route('get:club:data');
    }
    public function delete(Request $request)
    {

       $data=$this->clubs->getDeleteDetails($request);
       

           return redirect()->route('get:club:data'); 
       
       
    }

}
