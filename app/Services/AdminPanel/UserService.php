<?php
namespace App\Services\AdminPanel;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
class UserService
{
    /**
     * @return all exixting user details
     */
    
    public function getAllUserData(){
       $data=User::all();
        return $data;
       
    }

    /**
    *@role passing existing id to get respected details
     * @return all exixting user details
     */

    public function getDataForEdit($id)
    
    {
        $data=User::where('id',$id)->first();
         return $data; 
    }
    /**
    *@role all data for update as $request
     * @return all exixting user details
     */
    public function updateDetalis(Request $request)
    {     

        $data=User::find($request['item']);
       
        if(empty($data)){
            return null;
        }

       $data->name=$request['name'];

       $data->surname=$request['surname'];

       $data->age=$request['age'];

       $data->email=$request['mail'];

       $data->phone=$request['mob'];

        $data->save();

         return $data;
      
    }
    /**
    *@role id for delete
     * @return all exixting user details
     */
    public function deleteUserDetails(Request $request)
    {

        
        $data=User::find($request['id']);

        $data->delete();
        
         return $data;
        
    }
    
}
