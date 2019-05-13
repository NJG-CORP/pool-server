<?php

namespace App\Services;

use App\Models\City;
use App\Models\GameTime;
use App\Models\Location;
use App\Models\TermRelation;
use App\Models\User;
use App\Models\Weekday;
use Carbon\Carbon;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class PlayerService
{
    public function show($id)
    {
        $user = User::with(
            'receivedRatings.rater.avatar', 'sentRatings',
            'receivedFavourites', 'gameType', 'gamePaymentType', 'skillLevel',
            'location', 'avatar', 'city', 'gameTime'
        )->find($id);
        if (!empty($user->getAvatarUrl())) {
            $path = public_path($user->getAvatarUrl());
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
    public function save(User $user, $fields, CityService $cityService, ImageService $imageService)
    {
        $fields = collect($fields);
        $city = $fields->pull('city');
        $avatar = $fields->pull('avatar');
        $gameType = $fields->pull('game_type');
        $gamePaymentType = $fields->pull('game_payment_type');
        $skillLevel = $fields->pull('skill_level');
        $gameDays = $fields->pull('game_days');
        $oldPass = $fields->pull('oldpass');
        $newPass = $fields->pull('newpass');

        foreach ($user->fillable as $key) {
            if ($value = $fields->pull($key, false)) {
                $user->{$key} = $value;
            }
        }

        if ($newPass !== null) {
            if (Hash::check($oldPass, $user->password)) {
                $user->password = Hash::make($newPass);
            }
        }


        if ($city) {
            if (is_array($city)) {
                $userCity = $cityService->ensureCity($city['id'], $city['name']);
                $user->city_id = $userCity->id;
            } else {
                $cityModel = City::firstOrCreate([
                    'name' => $city
                ]);
                $user->city_id = $cityModel->id;
            }
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

        if ($gameType) {
            if (is_array($gameType)) {
                foreach ($gameType as $type) {
                    $user->addTerm($type);
                }
            } else {
                $user->addTerm($gameType);
            }
        }
        if ($gamePaymentType) {
            if (is_array($gamePaymentType)) {
                foreach ($gamePaymentType as $type) {
                    $user->addTerm($type);
                }
            } else {
                $user->addTerm($gamePaymentType);
            }
        }
        if ($skillLevel) {
            if (is_array($skillLevel)) {
                foreach ($skillLevel as $type) {
                    $user->addTerm($type);
                }
            } else {
                $user->addTerm($skillLevel);
            }
        }

        if ($gameDays) {
            \DB::delete('DELETE FROM game_time WHERE user_id=' . $user->id);
            foreach ($gameDays as $day) {
                \DB::insert(
                    "INSERT INTO game_time SET user_id=" . $user->id . ", weekday_id=$day"
                );
            }
        }

        if ($user->save()) {
            return $user;
        }
        return false;
    }

    public function search($offset, $query, User $currentUser)
    {
        $dbQuery = User::with([
            'avatar', 'gameTime', 'city', 'location.city', 'receivedRatings'
        ])
            ->select(['users.*'])
            ->groupBy(['users.id'])
//            ->join('rating', 'users.id', '=', 'rating.rateable_id')
//            ->addSelect(\DB::raw('AVG(`rating`.`score`) as calculated_rating'))
//            ->orderBy('calculated_rating', 'DESC')
            ->where('users.id', '<>', $currentUser->id);

        $gender = $query->get('gender');
        if ($gender !== null) {
            $dbQuery->where('gender', $gender);
        }

        if ($cityId = $query->get('city_id')) {
            $dbQuery->where('city_id', $cityId);
        }

        if ($days = $query->get('days')) {
            $dbQuery->join('game_time', 'users.id', '=', 'game_time.user_id');
            $dbQuery->whereIn('game_time.weekday_id', $days);
        }

        if ($time = $query->get('time')) {
            $dayStart = '00:00:00';
            $dayEnd = '23:59:00';
            $from = empty($time['from']) ?
                $dayStart :
                date('H:i:s', strtotime($time['from']));
            $to = empty($time['to']) ?
                $dayEnd :
                date('H:i:s', strtotime($time['to']));
            $dbQuery
                ->where('game_time_from', '>=', $from)
                ->where('game_time_to', '<=', $to);
        }

        if ($rating = $query->get('rating')) {
            $dbQuery
                ->where('rating.rateable_type', User::class)
                ->havingRaw(\DB::raw('`calculated_rating` >= ' . $rating));
        }

        if ($gameTypes = $query->get('game_type')) {
            $dbQuery->getAllByTermId($gameTypes);
        }

        if ($gamePaymentTypes = $query->get('game_payment_type')) {
            $v = \Taxonomy::getVocabularyByName('GamePaymentType');
            /**
             * @var Collection $terms
             */
            $terms = $v->terms;
            $formattedTerms = [];
            $queryTerms = [];
            foreach ($terms as $term) {
                $formattedTerms[$term->name] = $term->id;
            }
            foreach ($gamePaymentTypes as $gamePaymentType) {
                if ($gamePaymentType == $formattedTerms['За счет партнера'])
                    $gamePaymentType = $formattedTerms['Беру на себя'];
                else if ($gamePaymentType == $formattedTerms['Беру на себя'])
                    $gamePaymentType = $formattedTerms['За счет партнера'];
                $queryTerms[] = $gamePaymentType;
            }
            $dbQuery->getAllByTermId($queryTerms);
        }
        $total = $dbQuery->get()->count();

        return [
            "total" => $total,
            "players" => $dbQuery
                ->offset($offset)
                ->limit(10)
                ->get()
                ->map(function (User $user) {
                    $user->setAppends(['calculated_rating']);
                    return $user;
                })
                ->sort(function ($a, $b) {
                    return $b->calculated_rating - $a->calculated_rating;
                })
                ->values()
        ];
    }

    public function getUserGameTime($user_id)
    {
        $game_times = GameTime::where('user_id', $user_id)->get();
        $data = [];
        foreach ($game_times as $game_time) {
            $day = Weekday::where('id', $game_time->weekday_id)->first();
            $data[] = $day->key;
        }
        return $data;
    }

    public function getUserGameType($user_id)
    {
        $vocabulary = Vocabulary::where('name', 'GameType')->first();
        $term_relations = TermRelation::where('relationable_id', $user_id)->where('vocabulary_id', $vocabulary->id)->get();
        $data = [];
        foreach ($term_relations as $term_relation) {
            $terms = Term::where('id', $term_relation->term_id)->first();
            $data[] = $terms->key;
        }
        return $data;
    }

    public function getUserGamePayment($user_id)
    {
        $vocabulary = Vocabulary::where('name', 'GamePaymentType')->first();
        $term_relations = TermRelation::where('relationable_id', $user_id)->where('vocabulary_id', $vocabulary->id)->get();
        $data = [];
        foreach ($term_relations as $term_relation) {
            $terms = Term::where('id', $term_relation->term_id)->first();
            $data[] = $terms->key;
        }
        return $data;
    }

    /**
     * @param User $user
     * @param $cityId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function mapLocation(User $user, $cityId)
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
    public function saveLocation(User $user, $location)
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

    public function prepareWebRequest(Request $request)
    {
        $query = collect([
            'gender', 'days', 'time', 'rating', 'game_type', 'game_payment_type', 'city_id'
        ]);
        $prepared = $query->mapWithKeys(function ($e) use ($request) {
            return [$e => $request->get($e)];
        });

        $result = [];

        if ($request->get('city')) {
            $city = $cityModel = City::firstOrCreate([
                'name' => $request->get('city')
            ]);

            $result['city_id'] = $city->id;
        }

        $result['time'] = [
            'from' => Carbon::createFromTime($request->get('game_time_from'), 0, 0)->format("H:i:s"),
            'to' => TimeService::mutateToTime($request->get('game_time_to')),
        ];


        if ($prepared->get('gender') == -1) {
            $result['gender'] = null;
            $prepared->forget('gender');
        }


        return $query->mapWithKeys(function ($e) use ($result, $prepared) {
            return [$e => $result[$e] ?? $prepared->get($e)];
        });
    }
}
