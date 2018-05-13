<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FavouriteService;
use App\Utils\R;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * @var FavouriteService $rating
     */
    private $favourite;

    public function __construct(Request $request, FavouriteService $favourite){
        parent::__construct($request);
        $this->favourite = $favourite;
    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function favouritePlayers(){
        $favourites = $this->favourite->getFavouritePlayersOfUser(\Auth::user());
        return $this->responder->successResponse([
            'players' => $favourites
        ]);
    }

    /**
     * @param Request $request
     * @param User $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavouritePlayer(Request $request, $id){
        $addedUser = User::find($id);
        if (
            $res = $this->favourite->addFavouritePlayer(
                \Auth::user(),
                $addedUser
            )
        ) {
            return $this->responder->successResponse([
                "player" => $addedUser
            ]);
        }
        return $this->responder->errorResponse(R::FAVOURITE_SAME_PLAYER);
    }

    /**
     * @param Request $request
     * @param User $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function removeFavouritePlayer(Request $request, User $id){
        $this->validateRequestData([
            "id" => "required|numeric"
        ]);
        $removedUser = User::findOrFail($id);
        if (
        $res = $this->favourite->removeFavouritePlayer(
            \Auth::user(),
            $removedUser
        )
        ) {
            return $this->responder->successResponse([
                "player" => $removedUser
            ]);
        }
        return $this->responder->errorResponse();
    }
}
