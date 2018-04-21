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
        return $user
            ->sentFavourites()
            ->with(['city', 'avatar'])
            ->get()
            ->map(function (User $e){
                $e->setAppends(['calculated_rating']);
                $e->setHidden(array_merge($e->getHidden(), ['receivedRatings']));
                return $e;
            });
    }

    public function addFavouritePlayer(User $addingUser, User $addedUser){
        $f = \DB::table('favourites')
            ->where('from_id', $addingUser->id)
            ->where('to_id', $addedUser->id)
            ->first();
        if ( $f ) return $f;
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
