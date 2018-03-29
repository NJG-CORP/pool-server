<?php
namespace App\Services;

use App\Models\User;
use App\Utils\R;
use App\Utils\Utils;
use Illuminate\Support\Facades\Password;

class UserService
{
    private function makeToken(){
        return str_random(32);
    }

    public function tryLogin($email, $password){
        $authAttempt = \Auth::attempt(['email' => $email, 'password' => $password]);
        if ( $authAttempt ){
            $user = \Auth::user();
            return $user;
        }
        return null;
    }

    public function checkExternalUserExists($email, $externalId, $source){
        $authAttempt = \Auth::attempt([
            'email' => $email, 'external_id' => $externalId, 'source' => $source
        ]);
        if ( $authAttempt ){
            $user = \Auth::user();
            return $user;
        }
        return null;
    }

    public function register($email, $name, $surname, $source, $external_id){
        $password = str_random(6);
        $createdUser = User::create([
            "email" => $email,
            "password" => bcrypt($password),
            "name" => $name,
            "surname" => $surname,
            "age" => null,
            "location_id" => null,
            "city_id" => null,
            "source" => $source,
            "external_id" => $external_id,
            "api_token" => $this->makeToken(),
            "status" => false
        ]);
        if ( $createdUser instanceof User ){
            Utils::sendMail(
                $password, $createdUser->email, R::USER_REGISTRATION_EMAIL_SUBJECT
            );
            return $createdUser;
        }
        return null;
    }

    public function resetPassword($email){
        /**
         * @var User $user
         */
        $user = User::where('email', $email)->first();
        if ( $user ){
            $broker = Password::broker();
            return $broker->sendResetLink(
                ['email' => $email]
            );
        }
        return null;
    }
}
