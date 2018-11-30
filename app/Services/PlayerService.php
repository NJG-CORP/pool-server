<?php
namespace App\Services;

use App\Models\Location;
use App\Models\User;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use Devfactory\Taxonomy\Models\Term;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PlayerService
{
    /**
     * @param $offset
     * @param Collection $query
     * @param User $currentUser
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
//    public function search($offset, $query, User $currentUser){
//        $dbQuery = User::with([
//            'avatar', 'gameTime', 'city', 'location.city', 'receivedRatings'
//        ])
//            ->select(['users.*'])
//            ->groupBy(['users.id'])
////            ->join('rating', 'users.id', '=', 'rating.rateable_id')
////            ->addSelect(\DB::raw('AVG(`rating`.`score`) as calculated_rating'))
////            ->orderBy('calculated_rating', 'DESC')
//            ->where('users.id', '<>', $currentUser->id );
//
//        $gender = $query->get('gender');
//        if ( $gender !== null ){
//            $dbQuery->where('gender', $gender);
//        }
//
//        if ( $cityId = $query->get('city_id') ){
//            $dbQuery->where('city_id', $cityId);
//        }
//
//        if ( $days = $query->get('days') ){
//            $dbQuery->join('game_time', 'users.id', '=', 'game_time.user_id');
//            $dbQuery->whereIn('game_time.weekday_id', $days);
//        }
//
//        if ( $time = $query->get('time') ){
//            $dayStart = '00:00:00';
//            $dayEnd = '23:59:00';
//            $from = empty($time['from'])?
//                $dayStart:
//                date('H:i:s', strtotime($time['from']));
//            $to = empty($time['to'])?
//                $dayEnd:
//                date('H:i:s', strtotime($time['to']));
//            $dbQuery
//                ->where('game_time_from', '>=', $from)
//                ->where('game_time_to', '<=', $to);
//        }
//
//        if ( $rating = $query->get('rating') ){
//            $dbQuery
//                ->where('rating.rateable_type', User::class)
//                ->havingRaw(\DB::raw('`calculated_rating` >= ' . $rating));
//        }
//
//        if ( $gameTypes = $query->get('game_type') ){
//            $dbQuery->getAllByTermId($gameTypes);
//        }
//
//        if ( $gamePaymentTypes = $query->get('game_payment_type') ){
//            $v = \Taxonomy::getVocabularyByName('GamePaymentType');
//            /**
//             * @var Collection $terms
//             */
//            $terms = $v->terms;
//            $formattedTerms = [];
//            $queryTerms = [];
//            foreach ($terms as $term){
//                $formattedTerms[$term->name] = $term->id;
//            }
//            foreach ($gamePaymentTypes as $gamePaymentType){
//                if ( $gamePaymentType == $formattedTerms['За счет партнера'] )
//                    $gamePaymentType = $formattedTerms['Беру на себя'];
//                else if ( $gamePaymentType == $formattedTerms['Беру на себя'] )
//                    $gamePaymentType = $formattedTerms['За счет партнера'];
//                $queryTerms[] = $gamePaymentType;
//            }
//            $dbQuery->getAllByTermId($queryTerms);
//        }
//        $total = $dbQuery->get()->count();
//
//        return [
//            "total" => $total,
//            "players" => $dbQuery
//                ->offset($offset)
//                ->limit(10)
//                ->get()
//                ->map(function (User $user){
//                    $user->setAppends(['calculated_rating']);
//                    return $user;
//                })
//                ->sort(function($a, $b){
//                    return $b->calculated_rating - $a->calculated_rating;
//                })->values()
//        ];
//    }

    public function show($id){
        $user = User::with(
            'receivedRatings.rater.avatar', 'sentRatings',
            'receivedFavourites', 'gameType', 'gamePaymentType', 'skillLevel',
            'location', 'avatar', 'city', 'gameTime'
        )->find($id);
        if ( !empty($user->avatar->url) ){
            $path = public_path($user->avatar->url);
            if ( file_exists($path) ){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                if ( $type === 'jpg' ) $type = 'jpeg';
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $user->avatar->base64 = $base64;
            }
        }
        return $user;
    }

    /**
     * @param User $user
     * @param $fields
     * @param CityService $cityService
     * @param ImageService $imageService
     * @return User|bool
     */
//    public function save(User $user, $fields, CityService $cityService, ImageService $imageService){
//        $fields = collect($fields);
//        $city = $fields->pull('city');
//        $avatar = $fields->pull('avatar');
//        $gameType = $fields->pull('game_type');
//        $gamePaymentType = $fields->pull('game_payment_type');
//        $skillLevel = $fields->pull('skill_level');
//        $gameDays = $fields->pull('game_days');
//
//        foreach ($fields as $key=>$value){
//            $user->{$key} = $value;
//        }
//        if ( $city && $city['id'] ){
//            $userCity = $cityService->ensureCity($city['id'], $city['name']);
//            $user->city_id = $userCity->id;
//        }
//
//        if ( $avatar ){
//            \DB::delete("
//              DELETE FROM images WHERE imageable_type = 'App\\\\Models\\\\User'
//              AND imageable_id = " . $user->id);
//            $imagePath = "avatars/" . str_random(8) . '.jpg';
//            $imageService->create(
//                $avatar,
//                $user,
//                $imagePath
//            );
//        }
//
//        $user->removeAllTerms();
//        if ( $gameType ) $user->addTerm($gameType);
//        if ( $gamePaymentType ) $user->addTerm($gamePaymentType);
//        if ( $skillLevel ) $user->addTerm($skillLevel);
//
//        if ( $gameDays ){
//            \DB::delete('DELETE FROM game_time WHERE user_id=' . $user->id);
//            foreach ( $gameDays as $day ){
//                \DB::insert(
//                    "INSERT INTO game_time SET user_id=" . $user->id . ", weekday_id=$day"
//                );
//            }
//        }
//
//        if ( $user->save() ){
//            return $user;
//        }
//        return false;
//    }

    public function save($fields)
    {
        $user = new UserService();
        $user = $user->getUser();
        //обновляем(добавляем) дни игры пользователья
        $game_times = UserGameTime::where('user_id', $user->id)->first();
        if ($game_times) {
            in_array('monday', $fields['days']) ? $game_times->monday = 1 : $game_times->monday = 0;
            in_array('tuesday', $fields['days']) ? $game_times->tuesday = 1 : $game_times->tuesday = 0;
            in_array('wednesday', $fields['days']) ? $game_times->wednesday = 1 : $game_times->wednesday = 0;
            in_array('thursday', $fields['days']) ? $game_times->thursday = 1 : $game_times->thursday = 0;
            in_array('friday', $fields['days']) ? $game_times->friday = 1 : $game_times->friday = 0;
            in_array('saturday', $fields['days']) ? $game_times->saturday = 1 : $game_times->saturday = 0;
            in_array('sunday', $fields['days']) ? $game_times->sunday = 1 : $game_times->sunday = 0;
            $game_times->save();
        }else{
            $days = new UserGameTime();
            $days->user_id = $user;
            in_array('monday', $fields['days']) ? $days->monday = 1 : '';
            in_array('tuesday', $fields['days']) ? $days->tuesday = 1 : '';
            in_array('wednesday', $fields['days']) ? $days->wednesday = 1 : '';
            in_array('thursday', $fields['days']) ? $days->thursday = 1 : '';
            in_array('friday', $fields['days']) ? $days->friday = 1 : '';
            in_array('saturday', $fields['days']) ? $days->saturday = 1 : '';
            in_array('sunday', $fields['days']) ? $days->sunday = 1 : '';
            $days->save();
        }


        //обновляем(добавляем) типы игры пользователья
        $game_types = UserGameTypes::where('user_id', $user->id)->first();
        if ($game_types) {
            in_array('snooker', $fields['types']) ? $game_types->snooker = 1 : $game_types->snooker = 0;
            in_array('pool', $fields['types']) ? $game_types->pool = 1 : $game_types->pool = 0;
            in_array('russian', $fields['types']) ? $game_types->russian = 1 : $game_types->russian = 0;
            in_array('caromball', $fields['types']) ? $game_types->caromball = 1 : $game_types->caromball = 0;
            $game_types->save();
        }else{
            $types = new UserGameTypes();
            $types->user_id = $user;
            in_array('snooker', $fields['types']) ? $types->snooker = 1 : $types->snooker = 0;
            in_array('pool', $fields['types']) ? $types->pool = 1 : $types->pool = 0;
            in_array('russian', $fields['types']) ? $types->russian = 1 : $types->russian = 0;
            in_array('caromball', $fields['types']) ? $types->caromball = 1 : $types->caromball = 0;
            $types->save();
        }

        //обновляем(добавляем) метод оплаты пользователья
        $payments = UserPayment::where('user_id', $user->id)->first();
        if ($payments) {
            in_array('half', $fields['payment']) ? $payments->half = 1 : $payments->half = 0;
            in_array('me', $fields['payment']) ? $payments->me = 1 : $payments->me = 0;
            in_array('you', $fields['payment']) ? $payments->you = 1 : $payments->you = 0;
            in_array('unimportant', $fields['payment']) ? $payments->unimportant = 1 : $payments->unimportant = 0;
            $payments->save();
        }else{
            $pay = new UserPayment();
            $pay->user_id = $user;
            in_array('half', $fields['payment']) ? $pay->half = 1 : '';
            in_array('me', $fields['payment']) ? $pay->me = 1 : '';
            in_array('you', $fields['payment']) ? $pay->you = 1 : '';
            in_array('unimportant', $fields['payment']) ? $pay->unimportant = 1 : '';
            $pay->save();
        }


        //если пользователь хочет изменить пароль
        if ($fields['oldPassword']){
            //проверяем совпадение
            if (Hash::check($fields['oldPassword'], $user->password)){
                $new_pass = $fields['newPassword'];
                $new_pass = Hash::make($new_pass);
            }else{
                return redirect()->back()->with('error', 'Вы ввели неправильный пароль!');
            }
        }


        //сохраняем данные
        $user->email = $fields['email'];
        $user->age = $fields['age'];
        $user->gender = $fields['sex'];
        $user->phone = $fields['phone'];
        $user->street = $fields['location'];
        $user->game_time_from = $fields['time_from'];
        $user->game_time_to = $fields['time_to'];
        if (!empty($new_pass)){$user->password = $new_pass;}
        if($user->save()){
            return $user;
        }
        return false;
    }

    public function search($fields){
        $users_id = [];
        $searched_users = [];

        //начинаем поиск, Пол, добавляем ид найденных пользователей в массив
        $users = User::whereIn('gender', $fields['sex'])->get();
        foreach ($users as $user) {
            $users_id[] = $user->id;
        }
        //если поиск дал результат, идем дальше, Тип
        if (!empty($users_id)){
            $search_types = UserGameTypes::whereIn('user_id', $users_id)->get();
            foreach ($search_types as $search_type) {
                if (in_array('pool', $fields['types']) && $search_type->pool == 1) {
                    $user = $search_type->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('snooker', $fields['types']) && $search_type->snooker == 1) {
                    $user = $search_type->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('russian', $fields['types']) && $search_type->russian == 1) {
                    $user = $search_type->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('caromball', $fields['types']) && $search_type->caromball == 1) {
                    $user = $search_type->user_id;
                    $searched_users[] = $user;
                }
            }
        }

        //если поиск дал результат, идем дальше, Тип Оплаты
        if (!empty($searched_users)){
            $search_payments = UserPayment::whereIn('user_id', $searched_users)->get();
            $searched_users = [];
            foreach ($search_payments as $search_payment) {
                if (in_array('half', $fields['payment']) && $search_payment->half == 1) {
                    $user = $search_payment->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('me', $fields['payment']) && $search_payment->me == 1) {
                    $user = $search_payment->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('you', $fields['payment']) && $search_payment->you == 1) {
                    $user = $search_payment->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('unimportant', $fields['payment']) && $search_payment->unimportant == 1) {
                    $user = $search_payment->user_id;
                    $searched_users[] = $user;
                }
            }
        }

        //если поиск дал результат, идем дальше, Дни Недели
        if (!empty($searched_users)){
            $search_days = UserGameTime::whereIn('user_id', $searched_users)->get();
            $searched_users = [];
            foreach ($search_days as $search_day) {
                if (in_array('monday', $fields['days']) && $search_day->monday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('tuesday', $fields['days']) && $search_day->tuesday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('wednesday', $fields['days']) && $search_day->wednesday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('thursday', $fields['days']) && $search_day->thursday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('friday', $fields['days']) && $search_day->friday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('saturday', $fields['days']) && $search_day->saturday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
                elseif (in_array('sunday', $fields['days']) && $search_day->sunday == 1) {
                    $user = $search_day->user_id;
                    $searched_users[] = $user;
                }
            }
        }

        //если поиск дал результат, идем дальше, Рейтинг
        if (!empty($searched_users) && $fields['rating']){
            preg_match_all('!\d+!', $fields['rating'], $matches);
            $searched_ratings = User::whereIn('id', $searched_users)->get();
            $searched_users = [];
            foreach ($searched_ratings as $searched_rating) {
                $rating = round($searched_rating->getCalculatedRatingAttribute());
                $matches[0][0] == $rating ? $searched_users[] = $searched_rating->id : '';
            }
        }

        //если поиск дал результат, идем дальше, Времья
        if (!empty($searched_users) && $fields['time']) {
            $time = explode(';', $fields['time']);
            $searched_times = User::whereIn('id', $searched_users)->get();
            $searched_users = [];
            foreach ($searched_times as $searched_time) {
                $from = explode(':', $searched_time->game_time_from);
                $from = $from[0];
                $to = explode(':', $searched_time->game_time_to);
                $to = $to[0];
                !($time[1] <= $from || $time[0] >= $to) ? $searched_users[] = $searched_time->id : '';
            }
        }

            //если поиск дал результат, выведем список
        if (!empty($searched_users)){
            $results = User::whereIn('id', $searched_users)->get();
            return $results;
        }
        return false;
    }

    public function getUserGameTime($user_id){
        $game_time = UserGameTime::where('user_id', $user_id)->first();
        return $game_time;
    }

    public function getUserGameType($user_id){
        $game_type = UserGameTypes::where('user_id', $user_id)->first();
        return $game_type;
    }

    public function getUserGamePayment($user_id){
        $game_payment = UserPayment::where('user_id', $user_id)->first();
        return $game_payment;
    }

    /**
     * @param User $user
     * @param $cityId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function mapLocation(User $user, $cityId){
        $users = User::with(['location', 'avatar', 'receivedRatings'])
            ->where('id', '<>', $user->id)
            ->where('city_id', $cityId)
            ->whereHas('location')
            //->where('status', true)
            ->get();
        return $users->map(function (User $e){
            return $e
                ->setHidden(array_merge($e->getHidden(), ['received_ratings']))
                ->setAppends(['calculated_rating']);
        });
    }

    /**
     * @param User $user
     * @param $location
     * @return null
     */
    public function saveLocation(User $user, $location){
        if ( !$user->city ) return null;
        return Location::updateOrCreate([
            'id' => $user->id
        ], [
            'city_id' => $user->city->id,
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude']
        ]);
    }
}
