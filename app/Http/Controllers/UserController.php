<?php

namespace App\Http\Controllers;

use App\Http\Responder;
use App\Services\UserService;
use App\Utils\R;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private  $users;
    /**
     * @var Responder
     */
    private $responder;

    public function __construct(Request $request, UserService $users, Responder $responder){
        $this->request = $request;
        $this->users = $users;
        $this->responder = $responder;
    }

    public function login(){
        $req = $this->request->all();
        $this->validateRequestData([
            "email" => "required|email|unique:users",
            'password' => "required|min:6",
        ]);
        $res = $this->users->tryLogin(
            $req['email'],
            $req['password']
        );
        return $this->responder->successResponse($res);
    }

    public function register(){
        $req = $this->request->all();
        $this->validateRequestData([
            "email" => "required|email|unique:users",
            "name" => "required|min:2",
            "surname" => "required|min:2"
        ]);
        $res = $this->users->register(
            $req['email'],
            $req['name'],
            $req['surname']
        );
        return $this->responder->successResponse($res);
    }

    public function resetPassword(){
        $this->validateRequestData([
            'email' => 'required|email'
        ]);
        $broker = Password::broker();
        $res = $broker->sendResetLink(
            $this->request->get('email')
        );
        if ( $res === Password::RESET_LINK_SENT ){
            return $this->responder->successResponse(true);
        } else {
            return $this->responder->errorResponse(R::USER_PASS_RESET_FAILURE);
        }
    }
}
