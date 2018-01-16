<?php

namespace App\Http\Controllers;

use App\Http\Responder;
use App\Models\User;
use App\Services\PlayersService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @var PlayersService
     */
    private $players;
    /**
     * @var Responder
     */
    private $responder;

    /**
     * PlayerController constructor.
     * @param Request $request
     * @param PlayersService $players
     * @param Responder $responder
     */
    public function __construct(Request $request, PlayersService $players, Responder $responder){
        $this->request = $request;
        $this->players = $players;
        $this->responder = $responder;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function favouritePlayers(){
        $favourites = $this->players->getFavouritePlayersOfUser(\Auth::user());
        return $this->responder->successResponse($favourites);
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
            $result = $this->players->addFavouritePlayer(
                \Auth::user(),
                $addedUser
            )
        ) {
            return $this->responder->successResponse(true);
        }
        return $this->responder->errorResponse();
    }
}
