<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Services\ClubsService;
use App\Services\RatingService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * @var ClubsService
     */
    private $clubs;
    /**
     * @var RatingService $rating
     */
    private $rating;

    /**
     * ClubController constructor.
     * @param Request $request
     * @param ClubsService $clubs
     * @param RatingService $rating
     */
    public function __construct(Request $request, ClubsService $clubs, RatingService $rating)
    {
        parent::__construct($request);
        $this->clubs = $clubs;
        $this->rating = $rating;
    }

    public function list(){
        $clubs = $this->clubs->getList();
        return $this->responder->successResponse([
            'models' => $clubs
        ]);
    }

    public function byId(Request $request, $id){
        $club = $this->clubs->getOne($id);
        return $this->responder->successResponse([
            'model' => $club
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ControllableException
     */
    public function rate(Request $request, $id){
        $this->validateRequestData([
            "score" => "required|number|max:5|min:1"
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
