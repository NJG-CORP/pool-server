<?php

namespace App\Http\Controllers;

use App\Http\Responder;
use App\Models\User;
use App\Services\PlayersService;
use App\Services\RatingService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @var PlayersService $players
     */
    private $players;
    /**
     * @var RatingService $rating
     */
    private $rating;

    /**
     * PlayerController constructor.
     * @param Request $request
     * @param PlayersService $players
     * @param RatingService $rating
     */
    public function __construct(Request $request, PlayersService $players, RatingService $rating){
        parent::__construct($request);
        $this->players = $players;
        $this->rating = $rating;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function favouritePlayers(){
        $favourites = $this->players->getFavouritePlayersOfUser(\Auth::user());
        return $this->responder->successResponse([
            'models' => $favourites
        ]);
    }

    /**
     * @param Request $request
     * @param User $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function addFavouritePlayer(Request $request, User $id){
        $this->validateRequestData([
            "id" => "required|number"
        ]);
        $addedUser = User::findOrFail($id);
        if (
            $res = $this->players->addFavouritePlayer(
                \Auth::user(),
                $addedUser
            )
        ) {
            return $this->responder->successResponse([
                "id" => $res
            ]);
        }
        return $this->responder->errorResponse();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function rate(Request $request, $id){
        $this->validateRequestData([
            "score" => "required|number|max:5|min:1",
            "comment" => "string|min:1"
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
}
