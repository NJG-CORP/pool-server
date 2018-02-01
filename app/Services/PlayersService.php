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
        ])->where('id', '<>', $currentUser->id )->offset($offset)->limit(10)->get();
    }

    public function show($id){
        return User::with(
            'receivedRatings', 'sentRatings',
            'sentFavourites', 'receivedFavourites',
            'location.city', 'avatar'
        )->find($id);
    }

    public function mapLocation($cityId){
        return User::with(['location', 'avatar'])
            ->where('city_id', $cityId)
            ->where('status', true)
            ->get();
    }
}
