<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 29.10.2018
 * Time: 2:18
 */

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Web\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{

    protected $userService;
    protected $redirectTo = '/';
    /**
     * RegisterController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('guest');
        $this->userService = $userService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }



    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = User::create([
            'name' =>  $request->post('name'),
            'password' => bcrypt($request->post('password')),
            'email' => $request->post('email'),
        ]);
        $user = $this->userService->register(
            $request->post('email'),
            $request->post('name'),
            $request->post('source', ''),
            null,
            null,
            $request->post('password')
        );

        if ($user) {
            $this->guard()->login($user);
            return response()->json(['success' => true, 'redirectTo' => '/'], 201);
        }
        return response()->json(['success' => false, 'redirectTo' => '/'], 403);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}