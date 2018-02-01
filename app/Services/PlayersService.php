<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\User;
use App\Utils\R;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class PlayersService
{
    /**
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function getFavouritePlayersOfUser(User $user){
        $user->load('avatar');
        return $user->sentFavourites;
    }

    public function addFavouritePlayer(User $addingUser, User $addedUser){
        return \DB::table('favourites')->insert([
           'from_id' => $addingUser->id,
           'to_id' => $addedUser->id
        ]);
    }

    public function removeFavouritePlayer(User $addingUser, User $removedUser){
        $fav = \DB::table('favourites')->where([
            'from_id' => $addingUser->id,
            'to_id' => $removedUser->id
        ]);
        return \DB::table('favourites')->delete($fav->id);
    }

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
}
