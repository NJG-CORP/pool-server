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
}
