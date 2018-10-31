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
use Laravel\Socialite\Facades\Socialite;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $users;
    protected $request;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $users, Request $request)
    {
        $this->request = $request;
        $this->users = $users;
        $this->middleware('guest')->except('logout');
    }

    public function loginVk()
    {
        return Socialite::driver('vkontakte')->redirect();
    }
    public function handleCallback()
    {
        $vkUser = Socialite::driver('vkontakte')->user();
        $user = $this->users->getOrRegisterUserViaVk($vkUser);
        if ($user)
        {
            return $this->loginViaModel($user);
        }
        return 'Something went wrong.';
    }



    public function loginViaModel(User $user)
    {
        $this->request->session()->regenerate();
        $this->clearLoginAttempts($this->request);
        $this->guard()->login($user);
        return redirect('/');
    }


}