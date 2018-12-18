<?php

namespace App\Services;

use App\Models\City;
use App\Models\GameTime;
use App\Models\Location;
use App\Models\TermRelation;
use App\Models\User;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use App\Models\Weekday;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class PlayerService
{
    public function show($id)
    {
        $user = User::with(
            'receivedRatings.rater.avatar', 'sentRatings',
            'receivedFavourites', 'gameType', 'gamePaymentType', 'skillLevel',
            'location', 'avatar', 'city', 'gameTime'
        )->find($id);
        if (!empty($user->avatar->url)) {
            $path = public_path($user->avatar->url);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                if ($type === 'jpg') $type = 'jpeg';
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
    public function save(User $user, $fields, CityService $cityService, ImageService $imageService, UserService $userService)
    {
        $fields = collect($fields);
        $city = $fields->pull('city');
        $avatar = $fields->pull('avatar');
        $gameType = $fields->pull('types');
        $gamePaymentType = $fields->pull('payment');
        $skillLevel = $fields->pull('skill_level');
        $gameDays = $fields->pull('days');


        $user->email = $fields['email'];
        $user->age = $fields['age'];
        $user->gender = $fields['gender'];
        $user->game_time_from = $fields['game_time_from'];
        $user->game_time_to = $fields['game_time_to'];
        $user->phone = $fields['phone'];
        $user->street = $fields['street'];


        if ($fields['oldPassword']) {
            $changed_pass = $userService->setChangedPassword($fields['oldPassword'], $fields['newPassword'], $user);
            if ($changed_pass) {
                $user->password = $changed_pass;
            } else {
                return redirect()->back()->with('falsePass', 'Вы ввели неправильный пароль!');
            }
        }

        if ($city) {
            $userCity = $cityService->ensureCity($city);
            $user->city_id = $userCity;
        }

        if ($avatar) {
            \DB::delete("
               DELETE FROM images WHERE imageable_type = 'App\\\\Models\\\\User'
               AND imageable_id = " . $user->id);
            $imagePath = "avatars/" . str_random(8) . '.jpg';
            $imageService->create(
                $avatar,
                $user,
                $imagePath
            );
        }

        $user->removeAllTerms();
        if ($gameType) $user->addTerms($gameType);
        if ($gamePaymentType) $user->addTerms($gamePaymentType);
        if ($skillLevel) $user->addTerms($skillLevel);
        if ($gameDays) {
            \DB::delete('DELETE FROM game_time WHERE user_id=' . $user->id);
            $gameDays = Weekday::whereIn('key', $gameDays)->get();
            foreach ($gameDays as $day) {
                \DB::insert(
                    "INSERT INTO game_time SET user_id=" . $user->id . ", weekday_id=$day->id"
                );
            }
        }
        if ($user->save()) {
            return $user;
        }
        return false;
    }


    public function search($fields, User $currentUser)
    {
        $dbQuery = User::with([
            'avatar', 'gameTime', 'city', 'location.city', 'receivedRatings', 'getGameType', 'getGamePaymentType', 'termRelations'
        ])
            ->select(['users.*'])
            ->groupBy(['users.id'])
            ->join('term_relations', 'users.id', '=', 'term_relations.relationable_id')
            ->join('rating', 'users.id', '=', 'rating.rateable_id')
            ->addSelect(\DB::raw('AVG(`rating`.`score`) as calculated_rating'))
            ->orderBy('calculated_rating', 'DESC')
            ->where('users.id', '<>', $currentUser->id);
        $gender = $fields['gender'];
        if ($gender !== null) {
            $dbQuery->whereIn('gender', $gender);
        }

        if ($city = $fields['city']) {
            $city_id = City::where('name', $city)->first()->id;
            $dbQuery->where('city_id', $city_id);
        }


        if ($fields['days']) {
            $days = [];
            foreach ($fields['days'] as $day) {
                $days[] = Weekday::where('key', $day)->first()->id;
            }
            $dbQuery->join('game_time', 'users.id', '=', 'game_time.user_id');
            $dbQuery->whereIn('game_time.weekday_id', $days);
        }

        if ($fields['from'] && $fields['to']) {
            $dayStart = '00:00:00';
            $dayEnd = '23:59:00';
            $from = empty($fields['from']) ?
                $dayStart :
                date('H:i:s', strtotime($fields['from']));
            $to = empty($fields['to']) ?
                $dayEnd :
                date('H:i:s', strtotime($fields['to']));
            $dbQuery
                ->where('game_time_from', '>=', $from)
                ->where('game_time_to', '<=', $to);
        }

        if ($fields['rating']) {
            preg_match_all('!\d!', $fields['rating'], $rating);
            $dbQuery
                ->where('rating.rateable_type', User::class)
                ->havingRaw(\DB::raw('`calculated_rating` >= ' . $rating[0][0]));
        }


        if ($gameTypes = $fields['types']) {
            $types = [];
            foreach ($gameTypes as $gameType) {
                $types[] = Term::where('key', $gameType)->first()->id;
            }
            $dbQuery->whereIn('term_id', $types);
        }

        if ($gamePaymentTypes = $fields['payment']) {
            $payment_types = [];
            foreach ($gamePaymentTypes as $gamePaymentType) {
                $payment_types[] = Term::where('key', $gamePaymentType)->first()->id;
            }
            $game_type = [];
            foreach ($dbQuery->get() as $user) {
                $types = $user->getGamePaymentType()->get();
                foreach ($types as $type) {
                    in_array($type->term_id, $payment_types) && !in_array($type->relationable_id, $game_type) ? $game_type[] = $type->relationable_id : '';
                }
            }
            $dbQuery = User::whereIn('id', $game_type);
        }


        $total = $dbQuery->get()->count();

        return ["total" => $total,
            "players" => $dbQuery
                ->limit(10)
                ->get()
                ->map(function (User $user) {
                    $user->setAppends(['calculated_rating']);
                    return $user;
                })
                ->sort(function ($a, $b) {
                    return $b->calculated_rating - $a->calculated_rating;
                })->values()];
    }


        public
        function getUserGameTime($user_id)
        {
            $game_times = GameTime::where('user_id', $user_id)->get();
            if ($game_times) {
                $data = [];
                foreach ($game_times as $game_time) {
                    $day = Weekday::where('id', $game_time->weekday_id)->first();
                    if ($day) {
                        $data[] = $day->key;
                    }else {
                        return false;
                    }
                }
            }else {
                return false;
            }
            return $data;
        }

        public
        function getUserGameType($user_id)
        {
            $vocabulary = Vocabulary::where('name', 'GameType')->first();
            if ($vocabulary) {
                $term_relations = TermRelation::where('relationable_id', $user_id)->where('vocabulary_id', $vocabulary->id)->get();
                $data = [];
                if ($term_relations) {
                    foreach ($term_relations as $term_relation) {
                        $terms = Term::where('id', $term_relation->term_id)->first();
                        if ($terms) {
                            $data[] = $terms->key;
                        }else {
                            return false;
                        }
                    }
                }else {
                    return false;
                }
            }else {
                return false;
            }
            return $data;
        }

        public
        function getUserGamePayment($user_id)
        {
            $vocabulary = Vocabulary::where('name', 'GamePaymentType')->first();
            if ($vocabulary) {
                $term_relations = TermRelation::where('relationable_id', $user_id)->where('vocabulary_id', $vocabulary->id)->get();
                $data = [];
                if ($term_relations) {
                    foreach ($term_relations as $term_relation) {
                        $terms = Term::where('id', $term_relation->term_id)->first();
                        if ($terms) {
                            $data[] = $terms->key;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            return $data;
        }

        /**
         * @param User $user
         * @param $cityId
         * @return \Illuminate\Database\Eloquent\Collection|static[]
         */
        public
        function mapLocation(User $user, $cityId)
        {
            $users = User::with(['location', 'avatar', 'receivedRatings'])
                ->where('id', '<>', $user->id)
                ->where('city_id', $cityId)
                ->whereHas('location')
                //->where('status', true)
                ->get();
            return $users->map(function (User $e) {
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
        public
        function saveLocation(User $user, $location)
        {
            if (!$user->city) return null;
            return Location::updateOrCreate([
                'id' => $user->id
            ], [
                'city_id' => $user->city->id,
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude']
            ]);
        }
    }
