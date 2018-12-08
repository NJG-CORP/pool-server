<?php
namespace App\Services\AdminPanel;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Builder;
class RatingService
{
    /**
     * @return all exixting user details
     */
    
    public function getAllRatingData(){
       $data=Rating::with(['rater','rateable'])->orderBy('id','DESC')->paginate(10);
        return $data;
       
    }
     /**
    *@role passing existing id to get respected details
     * @return all exixting user details
     */

    public function getDataForEdit($id)
    
    {
        $data=Rating::where('id',$id)->first();
         return $data; 
    }
/**
    *@role all data for update as $request
     * @return all exixting user details
     */
    public function updateDetalis(Request $request)
    {     

        $data=Rating::find($request['item']);
       
        if(empty($data)){
            return null;
        }

       $data->comment=$request['comment'];



        $data->save();

         return $data;
      
    }
    /**
    *@role id for delete
     * @return all exixting user details
     */
    public function deleteUserDetails(Request $request)
    {

        
        $data=Rating::find($request['id']);
    
        $data->delete();
        
       
        
    }
    
}
