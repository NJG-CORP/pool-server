<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\R;
use Carbon\Carbon;
use App\Http\Responder;
use App\Services\AdminPanel\RatingService;
use App\Http\Requests\AdminRequest\RatingValidation;
class RatingController extends Controller
{

	public function __construct(RatingService $rate)
	{
		$this->rate = $rate;
	}
    public function getAllRating()
    {
    	$all_rate=$this->rate->getAllRatingData();


    	return view('admin_panel.rating.show')->with(compact('all_rate'));
    }

    public function edit($id)
    {
    	$edit_data=$this->rate->getDataForEdit($id);

         if($edit_data) 
           {
            	return view('admin_panel.rating.edit',compact('edit_data'));
            }
        else{
            	return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
            }
    }


     public function update(RatingValidation $request)
       {
        $data=$this->rate->updateDetalis($request);

        if($data)
        {
           return redirect()->route('get:rating:data');
    
        }else{
       return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }

       }
   public function delete(Request $request)
    {
       $delete=$this->rate->deleteUserDetails($request);

      

           return redirect()->route('get:rating:data');
       
     }
}
