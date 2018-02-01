<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllableException;
use App\Models\User;
use App\Services\PlayersService;
use App\Services\RatingService;
use App\Utils\R;
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
     * @param User $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function removeFavouritePlayer(Request $request, User $id){
        $this->validateRequestData([
            "id" => "required|number"
        ]);
        $removedUser = User::findOrFail($id);
        if (
        $res = $this->players->removeFavouritePlayer(
            \Auth::user(),
            $removedUser
        )
        ) {
            return $this->responder->successResponse();
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ControllableException
     */
    public function search(Request $request){
        $this->validateRequestData([
            "offset" => "required|number",
        ]);
        try {
            $res = $this->players->search($this->request->get('offset'), \Auth::user());
        } catch (\Throwable $e){
            throw new ControllableException($e->getMessage());
        }
        return $this->responder->successResponse([
            'users' => $res
        ]);
    }

    public function show(Request $request, $id){
        $user = $this->players->show($id);
        if ( $user ){
            return $this->responder->successResponse([
                'user' => $user
            ]);
        } else {
            return $this->responder->errorResponse(R::MODEL_NOT_FOUND);
        }
    }
}
