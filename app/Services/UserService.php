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
     * @param $externalId
     * @param $source
     * @return User|null
     * @throws ControllableException
     */
    public function checkExternalUserExists( $externalId, $source ){
        //TODO дыра в секурности
        $socialRegisteredUser = User::where([
            'external_id' => $externalId, 'source' => $source
        ])->first();
        if ( $socialRegisteredUser ) {
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
    public function register($email = null, $name, $surname, $source, $external_id){
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
            'game_time_from' => '00:00:00',
            'game_time_to' => '23:59:00',
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
            $v = \Taxonomy::getVocabularyByName('SkillLevel');
            $createdUser->addTerm(
                $v->terms()->where('name', 'Стандартный')->first()
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
    public function makeResetPasswordToken($email){
        /**
         * @var User $user
         */
        $user = User::where('email', $email)->where('source', null)->first();
        if ( $user ){
            $token = md5($email . microtime());
            Utils::sendMail(
                "
                    Что бы сбросить пароль пройдите по ссылке: https://poolbuddy.ru/api/auth/reset/$token
                ", $email, "Сброс пароля на poolbuddy.ru"
            );
            \DB::insert("INSERT INTO password_resets SET email = '$email', token = '$token', created_at=NOW()");
            return Password::RESET_LINK_SENT;
        }
        return null;
    }

    public function resetPassword($token){
        $pr = \DB::select("SELECT * FROM password_resets WHERE token = '$token' ORDER BY created_at DESC");
        if ( count($pr) ) {
            $password = str_random(6);
            $user = User::where('email', $pr[0]->email)->where('source', null)->firstOrFail();
            $user->password = bcrypt($password);
            $user->save();

            Utils::sendMail($password, $user->email, "Новый пароль на poolbuddy.ru");
            \DB::delete("DELETE FROM password_resets WHERE token = '$token'");

            return $user;
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
