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
     * UserController constructor.
     * @param Request $request
     * @param UserService $users
     */
    public function __construct(Request $request, UserService $users){
        parent::__construct($request);
        $this->users = $users;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function login(){
        $req = $this->request->all();
        $this->validateRequestData([
            "email" => "required|email",
            'password' => "required|min:6",
        ]);
        $auth = $this->users->tryLogin(
            $req['email'],
            $req['password']
        );
        if ( !$auth ){
            return $this->responder->errorResponse(R::USER_LOGIN_FAILURE);
        }
        return $this->responder->successResponse([
            "token" => $auth
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
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
        return $this->responder->successResponse([
            "user" => $res
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function resetPassword(){
        $this->validateRequestData([
            'email' => 'required|email'
        ]);
        $res = $this->users->resetPassword(
            $this->request->get('email')
        );
        if ( $res === Password::RESET_LINK_SENT ){
            return $this->responder->successResponse([
                "reset" => true
            ]);
        } else {
            return $this->responder->errorResponse(R::USER_PASS_RESET_FAILURE);
        }
    }
}
