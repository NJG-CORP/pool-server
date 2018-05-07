<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\Vocabulary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

class PlayerService
{
    /**
     * @param $offset
     * @param Collection $query
     * @param User $currentUser
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function search($offset, $query, User $currentUser){
        $dbQuery = User::with([
            'avatar', 'gameTime', 'city', 'location.city', 'receivedRatings'
        ])
            ->select(['users.*'])
            ->groupBy(['users.id'])
//            ->join('rating', 'users.id', '=', 'rating.rateable_id')
//            ->addSelect(\DB::raw('AVG(`rating`.`score`) as calculated_rating'))
//            ->orderBy('calculated_rating', 'DESC')
            ->where('users.id', '<>', $currentUser->id );

        $gender = $query->get('gender');
        if ( $gender !== null ){
            $dbQuery->where('gender', $gender);
        }

        if ( $cityId = $query->get('city_id') ){
            $dbQuery->where('city_id', $cityId);
        }

        if ( $days = $query->get('days') ){
            $dbQuery->join('game_time', 'users.id', '=', 'game_time.user_id');
            $dbQuery->whereIn('game_time.weekday_id', $days);
        }

        if ( $time = $query->get('time') ){
            $dayStart = '00:00:00';
            $dayEnd = '23:59:00';
            $from = empty($time['from'])?
                $dayStart:
                date('H:i:s', strtotime($time['from']));
            $to = empty($time['to'])?
                $dayEnd:
                date('H:i:s', strtotime($time['to']));
            $dbQuery
                ->where('game_time_from', '>=', $from)
                ->where('game_time_to', '<=', $to);
        }

        if ( $rating = $query->get('rating') ){
            $dbQuery
                ->where('rating.rateable_type', User::class)
                ->havingRaw(\DB::raw('`calculated_rating` >= ' . $rating));
        }

        if ( $gameType = $query->get('game_type') ){
            $anyType = Term::where(['name' => 'Любой'])->first();
            if ( $gameType != $anyType->id ) {
                $dbQuery->getAllByTermId($gameType);
            }
        }

        if ( $gamePaymentType = $query->get('game_payment_type') ){
            $v = \Taxonomy::getVocabularyByName('GamePaymentType');
            /**
             * @var Collection $terms
             */
            $terms = $v->terms;
            $formattedTerms = [];
            foreach ($terms as $term){
                $formattedTerms[$term->name] = $term->id;
            }
            if ( $gamePaymentType != $formattedTerms['Не имеет значения'] ) {
                if ( $gamePaymentType == $formattedTerms['За счет партнера'] )
                    $gamePaymentType = $formattedTerms['Беру на себя'];
                else if ( $gamePaymentType == $formattedTerms['Беру на себя'] )
                    $gamePaymentType = $formattedTerms['За счет партнера'];
                $dbQuery->getAllByTermId($gamePaymentType);
            }
        }

        return $dbQuery
            ->groupBy(['users.id'])
            ->offset($offset)
            ->limit(10)
            ->get()
            ->map(function (User $user){
                $user->setAppends(['calculated_rating']);
                return $user;
            })
            ->sort(function($a, $b){
                return $a->calculated_rating - $b->calculated_rating;
            })->values();
    }

    public function show($id){
        return User::with(
            'receivedRatings.rater.avatar', 'sentRatings',
            'receivedFavourites', 'gameType', 'gamePaymentType', 'skillLevel',
            'location', 'avatar', 'city', 'gameTime'
        )->find($id);
    }

    /**
     * @param User $user
     * @param $fields
     * @param CityService $cityService
     * @param ImageService $imageService
     * @return User|bool
     */
    public function save(User $user, $fields, CityService $cityService, ImageService $imageService){
        $fields = collect($fields);
        $city = $fields->pull('city');
        $avatar = $fields->pull('avatar');
        $gameType = $fields->pull('game_type');
        $gamePaymentType = $fields->pull('game_payment_type');
        $skillLevel = $fields->pull('skill_level');
        $gameDays = $fields->pull('game_days');

        foreach ($fields as $key=>$value){
            $user->{$key} = $value;
        }
        if ( $city && $city['id'] ){
            $userCity = $cityService->ensureCity($city['id'], $city['name']);
            $user->city_id = $userCity->id;
        }

        if ( $avatar ){
            $imagePath = "avatars/" . $user->id . '.jpg';
            $imageService->create(
                $avatar,
                $user,
                $imagePath
            );
        }

        $user->removeAllTerms();
        if ( $gameType ) $user->addTerm($gameType);
        if ( $gamePaymentType ) $user->addTerm($gamePaymentType);
        if ( $skillLevel ) $user->addTerm($skillLevel);

        if ( $gameDays ){
            \DB::delete('DELETE FROM game_time WHERE user_id=' . $user->id);
            foreach ( $gameDays as $day ){
                \DB::insert(
                    "INSERT INTO game_time SET user_id=" . $user->id . ", weekday_id=$day"
                );
            }
        }

        if ( $user->save() ){
            return $user;
        }
        return false;
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
            //->where('status', true)
            ->get();
        return $users->map(function (User $e){
            return $e
                ->setHidden(array_merge($e->getHidden(), ['received_ratings']))
                ->setAppends(['calculated_rating']);
        });
    }
}
