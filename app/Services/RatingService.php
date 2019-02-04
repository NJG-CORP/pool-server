<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\Rating;
use App\Models\User;
use App\Utils\R;
use Illuminate\Database\Eloquent\Model;

class RatingService
{
    /**
     * @param User $rater
     * @param Model $model
     * @param $score
     * @param string $comment
     * @return mixed
     * @throws ControllableException
     */
    public function rate(User $rater, Model $model, $score, $comment = ""){
        $class = get_class($model);
        if ( $class === User::class && $rater->id === $model->id ){
            throw new ControllableException(R::RATING_SAME_MODEL);
        }
        return Rating::firstOrCreate([
            "rater_id" => $rater->id,
            "rateable_id" => $model->id,
            "rateable_type" => $class,
        ],[
            "score" => $score,
            "comment" => $comment
        ]);
    }

    /**
     * @param Model $model
     * @param User|null $rater
     * @return bool
     * @throws ControllableException
     */
    public static function canUserRate(Model $model, $rater): bool
    {
        if (!$rater) {
            return false;
        }
        $class = get_class($model);
        if ($class === User::class && $rater->id === $model->id) {
            throw new ControllableException(R::RATING_SAME_MODEL);
        }

        return !Rating::query()->where([
            'rater_id' => $rater->id,
            'rateable_id' => $model->id,
            'rateable_type' => $class
        ])->exists();
    }
}
