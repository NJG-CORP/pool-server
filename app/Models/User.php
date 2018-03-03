<?php

namespace App\Models;

use Devfactory\Taxonomy\TaxonomyTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, TaxonomyTrait;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'deleted_at'
    ];

    protected $guarded = [];
    //protected $appends = ['calculated_rating'];

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

    public function sentRatings(){
        return $this->hasMany(Rating::class, 'rater_id', 'id');
    }

    public function receivedRatings(){
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function sentFavourites(){
        return $this->belongsToMany(User::class, 'favourites', 'from_id', 'to_id');
    }

    public function receivedFavourites(){
        return $this->belongsToMany(User::class, 'favourites', 'to_id', 'from_id');
    }

    public function getCalculatedRatingAttribute(){
        return collect($this->receivedRatings)->avg(function($e){
            return $e->score;
        });
    }

    public function gameTime(){
        return $this
            ->belongsToMany(
                Weekday::class,
                'game_time',
                'user_id',
                'weekday_id'
        );
    }
}
