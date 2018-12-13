<?php

namespace App\Models;

use App\Services\UserService;
use Devfactory\Taxonomy\Models\Term;
use Devfactory\Taxonomy\Models\TermRelation;
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
//    protected $fillable = [
//        'email', 'age', 'sex', 'phone', 'street', 'game_time_to', 'types', 'days', 'payment', 'gender', 'external_id', 'api_token',  'remember_token'
//    ];

    protected $hidden = [
        'password', 'deleted_at'
    ];

    protected $guarded = [];
    //protected $appends = ['calculated_rating'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    const
        STATUS_PRO = 1;

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

    public function getGameType(){
        $vocabulary = \Taxonomy::getVocabularyByName('GameType');
        return $this->related()
            ->where('vocabulary_id', $vocabulary->id)->with(['term']);
    }

    public function getSkillLevel(){
        $vocabulary = \Taxonomy::getVocabularyByName('SkillLevel');
        return $this->related()
            ->where('vocabulary_id', $vocabulary->id)->with(['term']);
    }

    public function getGamePaymentType(){
        $vocabulary = \Taxonomy::getVocabularyByName('GamePaymentType');
        return $this->related()
            ->where('vocabulary_id', $vocabulary->id)->with(['term']);
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
    
    public function devices(){
        return $this->hasMany(Device::class);
    }

    public function getUserName()
    {
        return (new UserService())->getUserName($this);
    }

    public function isPro()
    {
        return $this->status === self::STATUS_PRO;
    }


}
