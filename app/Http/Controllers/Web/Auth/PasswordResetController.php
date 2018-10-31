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
use App\Services\DeviceService;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;


class PasswordResetController extends Controller
{
    protected $users;
    public function __construct(UserService $users)
    {
        $this->users = $users;
        $this->middleware('guest');
    }

    public function sendMessage(Request $request)
    {
        $this->validateEmail($request);
        $reset = $this->users->makeResetPasswordToken($request->post('email'));
        if ( $reset === Password::RESET_LINK_SENT ) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['success' => false], 404);
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    public function showResetForm($token)
    {
        return view('site.password.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {

        $user = $this->users->resetPassword($request->post('token'), $request->post('password'));
        if ($user instanceof User) {
            $request->session()->regenerate();
            $this->guard()->login($user);
            return redirect('/');
        } else {
            die('Такого токена не существует.');
        }
    }


    protected function guard()
    {
        return Auth::guard();
    }
}