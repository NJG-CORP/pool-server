<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\User;
use App\Utils\R;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class UserService
{
    private function makeToken(){
        return str_random(32);
    }

    public function tryLogin($email, $password){
        $authAttempt = \Auth::attempt(['email' => $email, 'password' => $password]);
        if ( $authAttempt ){
            $user = User::where('email', $email)->first();
            return $user->api_token;
        }
        return false;
    }

    public function register($email, $password, $name, $surname){
        $createdUser = User::create([
            "email" => $email,
            "password" => bcrypt($password),
            "name" => $name,
            "surname" => $surname
        ]);
        \UserVerification::generate($createdUser);
        \UserVerification::send(
            $createdUser
        );
        return $createdUser;
    }
}
