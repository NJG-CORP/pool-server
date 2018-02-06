<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * @var RatingService $rating
     */
    private $rating;

    /**
     * PlayerController constructor.
     * @param Request $request
     * @param RatingService $rating
     */
    public function __construct(Request $request, RatingService $rating){
        parent::__construct($request);
        $this->rating = $rating;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function ratePlayer(Request $request, $id){
        $this->validateRequestData([
            "score" => "required|numeric|max:5|min:1",
            "comment" => "string|min:2"
        ]);
        $req = $this->request->all();
        $player = User::find($id);
        $res = $this->rating->rate(\Auth::user(), $player, $req['score'], empty($req['comment'])?"":$req['comment']);
        if ( $res ){
            return $this->responder->successResponse([
                "id" => $res
            ]);
        }
        return $this->responder->errorResponse($res);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function rateClub(Request $request, $id){
        $this->validateRequestData([
            "score" => "required|numeric|max:5|min:1",
        ]);
        $req = $this->request->all();
        $club = Club::find($id);
        $res = $this->rating->rate(\Auth::user(), $club, $req['score']);
        if ( $res ){
            return $this->responder->successResponse([
                "id" => $res
            ]);
        }
        return $this->responder->errorResponse($res);
    }
}
