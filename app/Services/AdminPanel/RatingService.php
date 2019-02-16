<?php

namespace App\Services\AdminPanel;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingService
{
    public static function getUnverifiedCount()
    {
        return Rating::query()->where(['is_verified' => 0])->count();
    }

    public function accept(int $id, int $value)
    {
        $review = Rating::query()->where(['id' => $id])->first();
        $review->is_verified = $value;
        return $review->save();
    }

    /**
     * @return all exixting user details
     */

    public function getAllRatingData()
    {
        $data = Rating::with(['rater', 'rateable'])->orderBy('is_verified', 'asc')->orderBy('id', 'DESC')->paginate(10);
        return $data;

    }

    /**
     * @role passing existing id to get respected details
     * @return all exixting user details
     */

    public function getDataForEdit($id)

    {
        $data = Rating::where('id', $id)->first();
        return $data;
    }

    /**
     * @role all data for update as $request
     * @return all exixting user details
     */
    public function updateDetalis(Request $request)
    {

        $data = Rating::find($request['item']);

        if (empty($data)) {
            return null;
        }

        $data->comment = $request['comment'];
        $data->score = $request['score'];


        $data->save();

        return $data;

    }

    /**
     * @role id for delete
     * @return all exixting user details
     */
    public function deleteUserDetails(Request $request)
    {


        $data = Rating::find($request['id']);

        $data->delete();


    }

}
