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
            return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }
        return $this->responder->successResponse([
            "token" => $auth->api_token,
            "user" => $auth
        ]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function social(){
        $req = $this->request->all();
        $this->validateRequestData([
            'external_id' => "required",
            'source' => 'required'
        ]);
        $auth = $this->users->checkExternalUserExists(
            $req['external_id'],
            $req['source']
        );
        if ( !$auth ){
            return $this->responder->successResponse([
                'token' => null,
            ]);
        }
        return $this->responder->successResponse([
            "token" => $auth->api_token,
            "user" => $auth
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function register(){
        $externalId = $this->request->get('external_id');
        $validationRules = [
            "name" => "required|min:2",
            "surname" => "required|min:2",
            "source" => "string|nullable",
            "external_id" => "string"
        ];
        if ( !$externalId ) {
            $validationRules["email"] = "required|email|unique:users";
        }

        $this->validateRequestData($validationRules);
        $res = $this->users->register(
            $this->request->get('email'),
            $this->request->get('name'),
            $this->request->get('surname'),
            $this->request->get('source', ''),
            $externalId
        );
        return $this->responder->successResponse([
            "token" => $res->api_token,
            "user" => $res
        ], 201);
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

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function vkInfo(){
        $this->validateRequestData([
            'access_token' => 'required|string'
        ]);
        $res = $this->users->vkAuth(
            $this->request->get('access_token')
        );
        return $this->responder->successResponse(['info' => $res]);
    }
}
