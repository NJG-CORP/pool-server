<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function avatar(){
        return $this->morphOne(Image::class, 'imageable');
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function sentInvitations(){
        return $this->hasMany(Invitation::class, 'inviter_id');
    }

    public function receivedInvitations(){
        return $this->hasMany(Invitation::class, 'invited_id');
    }

    public function sentRatings(){
        return $this->hasMany(Rating::class, 'rater_id', 'id');
    }

    public function receivedRatings(){
        return $this->hasMany(Rating::class, 'rated_id', 'id');
    }

    public function sentFavourites(){
        return $this->belongsToMany(User::class, 'favourites', 'from_id', 'to_id');
    }

    public function receivedFavourites(){
        return $this->belongsToMany(User::class, 'favourites', 'to_id', 'from_id');
    }

}
