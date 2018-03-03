<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\Club;
use App\Models\Rating;
use App\Models\User;
use App\Utils\R;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

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
}
