<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllableException;
use App\Services\PlayersService;
use App\Utils\R;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @var PlayersService $players
     */
    private $players;

    /**
     * PlayerController constructor.
     * @param Request $request
     * @param PlayersService $players
     */
    public function __construct(Request $request, PlayersService $players){
        parent::__construct($request);
        $this->players = $players;
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
            'players' => $res
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ControllableException
     */
    public function mapLocation(Request $request){
        $this->validateRequestData([
           'city_id' => 'required'
        ]);
        $players = $this->players->mapLocation($this->request->get('city_id'));
        return $this->responder->successResponse([
            'players' => $players
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id){
        $user = $this->players->show($id);
        if ( $user ){
            return $this->responder->successResponse([
                'player' => $user
            ]);
        } else {
            return $this->responder->errorResponse(R::MODEL_NOT_FOUND);
        }
    }
}
