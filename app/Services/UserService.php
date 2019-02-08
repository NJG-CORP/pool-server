<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\GameTime;
use App\Models\TermRelation;
use App\Models\User;
use App\Models\Weekday;
use App\Utils\R;
use App\Utils\Utils;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserService
{
    /**
     * @return string
     */
    private function makeToken()
    {
        return str_random(32);
    }

    /**
     * @param $email
     * @param $password
     * @return User|null
     */
    public function tryLogin($email, $password)
    {
        $authAttempt = \Auth::attempt(['email' => $email, 'password' => $password]);
        if ($authAttempt) {
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
    public function checkExternalUserExists($externalId, $source)
    {
        //TODO дыра в секурности
        $socialRegisteredUser = User::where([
            'external_id' => $externalId, 'source' => $source
        ])->first();
        if ($socialRegisteredUser) {
            if ($socialRegisteredUser) {
                return $socialRegisteredUser;
            } else {
                throw new ControllableException(R::USER_REGISTERED_WITH_NO_SOCIAL);
            }
        }
        return null;
    }

    /**
     * @param null $email
     * @param $name
     * @param $surname
     * @param $source
     * @param $external_id
     * @param $password
     * @return null
     */
    public function register($email = null, $name, $surname, $source, $external_id, $password)
    {
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
        if ($createdUser instanceof User) {
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
    public function makeResetPasswordToken($email)
    {
        /**
         * @var User $user
         */
        $user = User::where('email', $email)->first();
        if ($user) {
            $token = md5($email . microtime());
            Utils::sendMail(
                "
                    Что бы сбросить пароль пройдите по ссылке: https://poolbuddy.ru/password/reset/$token
                ", $email, "Сброс пароля на poolbuddy.ru"
            );
            \DB::insert("INSERT INTO password_resets SET email = '$email', token = '$token', created_at=NOW()");
            return Password::RESET_LINK_SENT;
        }
        return null;
    }

    public function resetPassword($token, $password)
    {
        $pr = \DB::select("SELECT * FROM password_resets WHERE token = '$token' ORDER BY created_at DESC");
        if (count($pr)) {
            $user = User::where('email', $pr[0]->email)->firstOrFail();
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
    public function vkAuth($accessToken)
    {
        $client = new Client();
        $res = $client->get(
            'https://api.vk.com/method/users.get',
            [
                'query' => [
                    'fields' => 'bdate,sex,city,photo_max_orig',
                    'access_token' => $accessToken,
                    'v' => '5.8'
                ]
            ]
        );
        return json_decode($res->getBody(), true);
    }


    public function getOrRegisterUserViaVk(\Laravel\Socialite\Contracts\User $vkUser)
    {
        $email = isset($vkUser->accessTokenResponseBody['email']) ? $vkUser->accessTokenResponseBody['email'] : null;

        $user = $this->getUserViaVkId($vkUser->user['id']);
        $user = $user ?? $this->register(
                $email,
                $vkUser->user['first_name'],
                $vkUser->user['last_name'],
                'vk',
                $vkUser->user['id'],
                null);

        if ($user) {
            return $user;
        }
        return null;
    }

    public function getOrRegisterUserViaFb(\Laravel\Socialite\Contracts\User $fbUser)
    {


        $user = $this->getUserViaFbId($fbUser->user['id']);
        $user = $user ?? $this->register(
                $fbUser->getEmail(),
                $fbUser->getName(),
                '',
                'fb',
                $fbUser->user['id'],
                null);

        if ($user) {
            return $user;
        }
        return null;
    }

    protected function getUserViaVkId($externalId)
    {
        return User::where(['external_id' => $externalId, 'source' => 'vk'])->first() ?? null;
    }

    protected function getUserViaFbId($externalId)
    {
        return User::where(['external_id' => $externalId, 'source' => 'fb'])->first() ?? null;
    }

    public function getUserName(User $user)
    {
        return $user->surname . ' ' . $user->name;
    }

    public function getUser()
    {
        $user = Auth::user();
        if ($user) {
            return $user;
        }
        return false;
    }

    public function getAvatarPath($user)
    {
        if ($user) {
            if ($user->avatar()->first()) {
                return $user->avatar()->first()->path;
            }
            return '/default-person.jpg';
        }
        return false;
    }

    public function setGameTime($fields, $user)
    {
        $game_times = GameTime::where('user_id', $user->id)->get();
        $weekdays = Weekday::all();
        $i = 1;
        if (!empty($game_times)) {
            $this->deleteUserGameTimes($game_times);
        }
        foreach ($weekdays as $weekday) {
            if (in_array($weekday->key, $fields)) {
                $game_time[$i] = new GameTime();
                $game_time[$i]->user_id = $user->id;
                $game_time[$i]->weekday_id = $weekday->id;
                $i++;
            }
        }
        return $game_time;
    }

    public function setGameType($fields, $user)
    {
        $type_id = $this->getVocabularyId('GameType');
        $terms = $this->getTerms($type_id);
        $game_types = $this->getUserTermRelation($user->id, $type_id);
        $i = 1;
        if (!empty($game_types)) {
            $this->deleteUserGameTypes($game_types);
        }
        foreach ($terms as $term) {
            if (in_array($term->key, $fields)) {
                $user_game_type[$i] = new TermRelation();
                $user_game_type[$i]->relationable_id = $user->id;
                $user_game_type[$i]->relationable_type = 'App\Models\User';
                $user_game_type[$i]->term_id = $term->id;
                $user_game_type[$i]->vocabulary_id = $type_id;
                $i++;
            }
        }
        return $user_game_type;
    }

    public function setGamePayment($fields, $user)
    {
        $type_id = $this->getVocabularyId('GamePaymentType');
        $terms = $this->getTerms($type_id);
        $payment_types = $this->getUserTermRelation($user->id, $type_id);
        $i = 1;
        if (!empty($payment_types)) {
            $this->deleteUserPaymentTypes($payment_types);
        }
        foreach ($terms as $term) {
            if (in_array($term->key, $fields)) {
                $user_payment_type[$i] = new TermRelation();
                $user_payment_type[$i]->relationable_id = $user->id;
                $user_payment_type[$i]->relationable_type = 'App\Models\User';
                $user_payment_type[$i]->term_id = $term->id;
                $user_payment_type[$i]->vocabulary_id = $type_id;
                $i++;
            }
        }
        return $user_payment_type;
    }

    public function setChangedPassword($old, $new, $user)
    {
        //проверяем совпадение
        if ($this->checkHashedPassword($old, $user->password)) {
            $new_pass = $this->hashPassword($new);
            return $new_pass;
        } else {
            return false;
        }
    }

    public function setUserData($fields, $user)
    {
        //если пользователь хочет изменить пароль
        if ($fields['oldPassword']) {
            $changed_pass = $this->setChangedPassword($fields['oldPassword'], $fields['newPassword'], $user);
            if (!$changed_pass) {
                return redirect()->back()->with('falsePass', 'Вы ввели неправильный пароль!');
            }

        }

        if ($fields['location']) {
            $city = (new CityService())->getCity($fields['location']);
            if ($city) {
                $city_id = $city->id;
            } else {
                $city_id = (new CityService())->saveCity($fields['location']);
            }
        }

        //передаем данные
        $user->email = $fields['email'];
        $user->age = $fields['age'];
        $user->gender = $fields['sex'];
        $user->phone = $fields['phone'];
        $user->game_time_from = $fields['time_from'];
        $user->game_time_to = $fields['time_to'];
        isset($city_id) ? $user->city_id = $city_id : '';
        !empty($changed_pass) ? $user->password = $changed_pass : '';

        return $user;
    }

    public function checkHashedPassword($curr, $filled)
    {
        if (Hash::check($curr, $filled)) {
            return true;
        }
        return false;
    }

    public function hashPassword($password)
    {
        $password = Hash::make($password);
        return $password;
    }

    public function getVocabularyId($name)
    {
        $vocabulary = Vocabulary::where('name', $name)->first()->id;
        return $vocabulary;
    }

    public function getUserTermRelation($user_id, $vocabulary_id)
    {
        $term_relation = TermRelation::where('relationable_id', $user_id)->where('vocabulary_id', $vocabulary_id)->get();
        return $term_relation;
    }


    public function getTerms($vocabulary_id)
    {
        $terms = Term::where('vocabulary_id', $vocabulary_id)->get();
        return $terms;
    }

    public function getSearchedTermsId($arr, $voc_id)
    {
        $terms = Term::where('vocabulary_id', $voc_id)->whereIn('key', $arr)->get();
        $data = [];
        if ($terms) {
            foreach ($terms as $term) {
                $data[] = $term->id;
            }
        }
        return $data;
    }

    public function getSearchedTermRelations($term_id, $voc_id, $users_id)
    {
        $term_relation = TermRelation::whereIn('term_id', $term_id)->whereIn('relationable_id', $users_id)->where('vocabulary_id', $voc_id)->get();
        return $term_relation;
    }

    public function getSearchedWeekdayId($arr)
    {
        $weekdays = Weekday::whereIn('key', $arr)->get();
        $data = [];
        foreach ($weekdays as $weekday) {
            $data[] = $weekday->id;
        }
        return $data;
    }

    public function getSearchedGameTime($weekday_id, $users_id)
    {
        $data = GameTime::whereIn('weekday_id', $weekday_id)->whereIn('user_id', $users_id)->get();
        return $data;
    }

    public function deleteUserGameTypes($types)
    {
        if ($types) {
            foreach ($types as $type) {
                $type->delete();
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserPaymentTypes($payments)
    {
        if ($payments) {
            foreach ($payments as $payment) {
                $payment->delete();
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserGameTimes($times)
    {
        if ($times) {
            foreach ($times as $time) {
                $time->delete();
            }
            return true;
        } else {
            return false;
        }
    }
}
