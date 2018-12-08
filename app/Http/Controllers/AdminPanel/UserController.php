<?php

namespace App\Http\Controllers\AdminPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\R;
use Carbon\Carbon;
use App\Http\Responder;
use App\Services\AdminPanel\UserService;


use App\Http\Requests\AdminRequest\UserValidation;


class UserController extends Controller
{
	public function __construct(UserService $users){
       
  		$this->users = $users;
         
    }
  //find all existing user 
    public function getAllData()
    {
    	$all_users=$this->users->getAllUserData();
    	
    	 return view('admin_panel.user.users')->with(compact('all_users'));
    }
    //find detail about particuler user for edit purpose
    public function edit($id){

         $edit_data=$this->users->getDataForEdit($id);

         if($edit_data) 
           {
            	return view('admin_panel.user.edit',compact('edit_data'));
            }
        else{
            	return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
            }
    }

    
    public function update(UserValidation $request)
       {
        $data=$this->users->updateDetalis($request);

        if($data)
        {
           return redirect()->route('get:users:data');
    
        }else{
       return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }

       }
   public function delete(Request $request)
    {
       $delete=$this->users->deleteUserDetails($request);

       

           return redirect()->route('get:users:data');
       
     }
 
}
