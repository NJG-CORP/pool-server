<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\User;
use App\Utils\R;
use App\Utils\Utils;
use Devfactory\Taxonomy\Models\Vocabulary;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Password;

class UserService
{
    /**
     * @return string
     */
    private function makeToken(){
        return str_random(32);
    }

    /**
     * @param $email
     * @param $password
     * @return User|null
     */
    public function tryLogin($email, $password){
        $authAttempt = \Auth::attempt(['email' => $email, 'password' => $password]);
        if ( $authAttempt ){
            $user = \Auth::user();
            return $user;
        }
        return null;
    }

    /**
     * @param $email
     * @param $externalId
     * @param $source
     * @return User|null
     * @throws ControllableException
     */
    public function checkExternalUserExists($email, $externalId, $source){
        $registeredUser = User::where(['email' => $email])->first();
        if ( $registeredUser ) {
            $socialRegisteredUser = User::where([
                'email' => $email, 'external_id' => $externalId, 'source' => $source
            ])->first();
            if ( $socialRegisteredUser ){
                return $socialRegisteredUser;
            } else {
                throw new ControllableException(R::USER_REGISTERED_WITH_NO_SOCIAL);
            }
        }
        return null;
    }

    /**
     * @param $email
     * @param $name
     * @param $surname
     * @param $source
     * @param $external_id
     * @return null
     */
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
            /**
             * @var Vocabulary $v
             */
            $v = \Taxonomy::getVocabularyByName('GamePaymentType');
            $createdUser->addTerm(
                $v->terms()->where('name', 'Поровну')->first()
            );
            Utils::sendMail(
                $password, $createdUser->email, R::USER_REGISTRATION_EMAIL_SUBJECT
            );
            return $createdUser;
        }
        return null;
    }

    /**
     * @param $email
     * @return null|string
     */
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

    /**
     * @param $accessToken
     * @return string
     */
    public function vkAuth($accessToken){
        $client = new Client();
        $res = $client->get(
            'https://api.vk.com/method/users.get',
            [
                'query' => [
                    'fields'=>'bdate,sex,city,photo_max_orig',
                    'access_token' => $accessToken,
                    'v' => '5.8'
                ]
            ]
        );
        return json_decode($res->getBody(), true);
    }
}
