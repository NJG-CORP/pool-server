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
use App\Utils\Utils;
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
        $this->validate($request, ['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();
        $password = str_random(10);
        $user->password = bcrypt($password);
        $user->save();
        Utils::sendMail(
        "Ваш новый пароль уже готов: {$password}.", $user->email, "Сброс пароля на poolbuddy.ru"
    );
        return redirect(route('home'))->withSuccess('password.sent');
    }

    protected function validateEmail(Request $request)
    {
        return $this->validate($request, ['email' => 'required|email']);
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