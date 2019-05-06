<?php

namespace App\Models;

use App\Services\UserService;
use Devfactory\Taxonomy\TaxonomyTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use stdClass;
use Taxonomy;

/**
 * Class User
 * @package App\Models
 * @property Image $avatar
 */
class User extends Authenticatable
{
    const
        SKILL_LEVEL_PROFI = 11,
        SKILL_LEVEL_NORMAL = 10,
        SKILL_LEVEL_WEAK = 9,
        PAYMENT_TYPE_ALL = 4,
        PAYMENT_TYPE_HALF = 5,
        PAYMENT_TYPE_ME = 6,
        PAYMENT_TYPE_YOU = 7,
        PAYMENT_TYPE_UNIMPORTANT = 8,
        GAME_TYPE_POOL = 3,
        GAME_TYPE_SNOOKER = 2,
        GAME_TYPE_RUSSIAN = 1;

    use Notifiable, SoftDeletes, TaxonomyTrait;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $fillable = [
        'name', 'surname', 'source', 'email', 'age', 'gender', 'phone', 'street', 'game_time_to', 'game_time_from', 'days', 'payment', 'gender', 'external_id', 'api_token', 'remember_token'
    ];

    protected $hidden = [
        'password', 'deleted_at'
    ];

    protected $guarded = [];
    //protected $appends = ['calculated_rating'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    const
        STATUS_PRO = 1;

    public function avatar(){
        return $this->morphOne(Image::class, 'imageable') ?? Image::getDefaultImage();
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

    public function gameType(){
        $vocabulary = Taxonomy::getVocabularyByName('GameType');
        return $this->related()
            ->where('vocabulary_id', $vocabulary->id)->with(['term']);
    }

    public function skillLevel(){
        $vocabulary = Taxonomy::getVocabularyByName('SkillLevel');
        return $this->related()
            ->where('vocabulary_id', $vocabulary->id)->with(['term']);
    }

    public function gamePaymentType(){
        $vocabulary = Taxonomy::getVocabularyByName('GamePaymentType');
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

    public function getAvatarUrl(): string
    {
        if(!$this->avatar)
        {
            $this->avatar = new stdClass();
            $this->avatar->url = Image::getDefaultImage()['url'];
        }
        return $this->avatar->url;
    }

    public function isAdmin(): bool
    {
        return (bool)$this->is_admin;
    }

    public function isSkillLevel(int $id)
    {
        foreach ($this->skillLevel as $level) {
            if ($level->term_id === $id) {
                return true;
            }
        }

        return false;
    }

    public function isGamePaymentType(int $id)
    {
        foreach ($this->gamePaymentType as $level) {
            if ($level->term_id === $id) {
                return true;
            }
        }

        return false;
    }

    public function isGameType(int $id)
    {
        foreach ($this->gameType as $level) {
            if ($level->term_id === $id) {
                return true;
            }
        }

        return false;
    }

    public function getAddress()
    {
        $address = [];
        if ($this->city) {
            $address[] = $this->city->name;
        }
        if ($this->street) {
            $address[] = $this->street;
        }

        return implode(', ', $address);
    }

    public function getFullUsername()
    {
        $name = $this->name;

        if ($this->age) {
            $name = $name . ', ' . $this->age;
        }
        return $name;
    }

    public function getGameTypes()
    {
        $types = [];
        foreach ($this->gameType as $type) {
            $types[] = $type->term->name;
        }
        return !empty($types) ? implode(', ', $types) : 'Еще не выбрано';
    }
}
