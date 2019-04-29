<?php


namespace App\Http\Controllers\Web;


use App\Models\Club;
use App\Services\RatingService;
use Auth;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function rateClub(Request $request, $club_id)
    {
        if (!Auth::user()) {
            return back();
        }

        $request->validate([
            "rating" => "required|numeric|max:5|min:1",
            "review_text" => "required|string",
        ]);

        $req = $request->all();
        $club = Club::find($club_id);
        $res = (new RatingService())->rate(Auth::user(), $club, $req['rating'], $req['review_text']);
        return back();
    }
}