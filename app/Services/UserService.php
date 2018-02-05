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
            $user = \Auth::user();
            return $user->api_token;
        }
        return null;
    }

    public function register($email, $name, $surname){
        $password = str_random(6);
        $createdUser = User::create([
            "email" => $email,
            "password" => bcrypt($password),
            "name" => $name,
            "surname" => $surname,
            "age" => null,
            "location_id" => null,
            "city_id" => null,
            "api_token" => str_random(32),
            "status" => false
        ]);
        if ( $createdUser instanceof User ){
            \Mail::raw(
                $password,
                function (Message $message) use ( $createdUser, $email){
                    $message->from("pooltest@mail.ru");
                    $message->to($email);
                }
            );
            return $createdUser;
        }
        return null;
    }
}
