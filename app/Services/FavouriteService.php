<?php
namespace App\Services;

use App\Models\User;

class FavouriteService
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
}
