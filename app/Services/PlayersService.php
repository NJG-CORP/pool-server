<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\User;
use App\Utils\R;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class PlayersService
{
    public function search($offset, User $currentUser){
        return User::with([
            'receivedRatings', 'location.city', 'avatar'
        ])
            ->where('id', '<>', $currentUser->id )
            ->offset($offset)
            ->limit(10)
            ->get();
    }

    public function show($id){
        return User::with(
            'receivedRatings', 'sentRatings',
            'sentFavourites', 'receivedFavourites',
            'location.city', 'avatar'
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
        $city = $fields->get('city');
        $fields->forget('city');
        $avatar = $fields->get('avatar');
        $fields->forget('avatar');

        foreach ($fields as $key=>$value){
            $user->{$key} = $value;
        }
        if ( $city ){
            $cityService->ensureCity($city['id'], $city['name']);
        }

        if ( $avatar ){
            $imagePath = public_path("assets/images/avatars/" . $user->id);
            $imageService->create(
                $avatar,
                $user,
                $imagePath
            );
        }

        if ( $user->save() ){
            return $user;
        }
        return false;
    }

    public function mapLocation($cityId){
        return User::with(['location', 'avatar'])
            ->where('city_id', $cityId)
            ->where('status', true)
            ->get();
    }
}
