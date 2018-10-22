<?php

namespace App\Http\Controllers\Site\User;

use App\Http\Controllers\Site\AjaxController;
use App\Http\Controllers\Site\BaseController;
use App\Services\DeviceService;
use App\Services\UserService;
use App\Utils\R;
use Auth;
use Illuminate\Http\Request;

class AuthController extends AjaxController
{
    /**
     * @var UserService
     */
    private $users;

    /**
     * @var DeviceService
     */
    private $devices;

    /**
     * UserController constructor.
     * @param Request $request
     * @param UserService $users
     * @param DeviceService $devices
     */
    public function __construct(Request $request, UserService $users, DeviceService $devices)
    {
        parent::__construct($request);
        $this->users = $users;
        $this->devices = $devices;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function login()
    {
        if (Auth::user()) {
            return $this->responder->successResponse([]);
        }

        $req = $this->request->all();
        $this->validateRequestData([
            "email" => "required|email",
            'password' => "required|min:6",
        ]);
        $auth = $this->users->tryLogin(
            $req['email'],
            $req['password']
        );
        if (!$auth) {
            return $this->responder->errorResponse(R::USER_LOGIN_FAILURE, null, 401);
        }

        return $this->responder->successResponse([]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function register()
    {
        $externalId = $this->request->get('external_id');
        $validationRules = [
            "name" => "string",
            "surname" => "string",
            "source" => "string|nullable",
            "external_id" => "string"
        ];
        if (!$externalId) {
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
}