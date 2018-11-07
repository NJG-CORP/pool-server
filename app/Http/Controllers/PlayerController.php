<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllableException;
use App\Services\CityService;
use App\Services\ImageService;
use App\Services\PlayerService;
use App\Utils\R;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * @var PlayerService $players
     */
    protected $players;

    /**
     * @var CityService $cities
     */
    protected $cities;

    /**
     * @var ImageService $images
     */
    protected $images;

    /**
     * PlayerController constructor.
     * @param Request $request
     * @param PlayerService $players
     * @param CityService $cities
     * @param ImageService $images
     */
    public function __construct(
        Request $request,
        PlayerService $players,
        CityService $cities,
        ImageService $images
){
        parent::__construct($request);
        $this->players = $players;
        $this->cities = $cities;
        $this->images = $images;
    }

    public function selfInfo(Request $request){
        $info = $this->players->show(
            \Auth::user()->id
        );
        return $this->responder->successResponse([
           "player" => $info
        ]);
    }

    public function update(Request $request){
        $res = $this->players->save(
            \Auth::user(),
            $request->except(['api_token']),
            $this->cities,
            $this->images
        );
        if ( $res ){
            return $this->responder->successResponse([
                "player" => $res
            ]);
        }
        return $this->responder->errorResponse();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ControllableException
     */
    public function search(Request $request){
        $this->validateRequestData([
            "offset" => "required|numeric",
            "gender" => "numeric|nullable",
            "days" => "array|nullable",
            "time.from" => "string|nullable",
            "time.to" => "string|nullable",
            "city_id" => "numeric",
            'rating' => 'numeric|min:1|max:5|nullable',
            "game_type" => "array|nullable",
            "game_payment_type" => "array|nullable"
        ]);
        try {
            $query = collect([
                'gender', 'days', 'time', 'rating', 'game_type', 'game_payment_type', 'city_id'
            ]);
            $res = $this->players->search(
                $this->request->get('offset'),
                $query->mapWithKeys(function ($e) {
                    return [$e=>$this->request->get($e)];
                }),
                \Auth::user()
            );
        } catch (\Throwable $e){
            throw new ControllableException($e->getMessage());
        }
        return $this->responder->successResponse($res);
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
        $players = $this->players->mapLocation(\Auth::user(), $this->request->get('city_id'));
        return $this->responder->successResponse([
            'players' => $players
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws ControllableException
     */
    public function updateLocation(){
        $this->validateRequestData([
            'latitude' => 'required|string',
            'longitude' => 'required|string'
        ]);
        $location = $this->players->saveLocation(\Auth::user(), $this->request->except('api_token'));
        return $this->responder->successResponse([
            'location' => $location
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
