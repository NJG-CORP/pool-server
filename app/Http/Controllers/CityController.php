<?php

namespace App\Http\Controllers;

use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @var CityService
     */
    protected $cities;

    /**
     * CityController constructor.
     * @param Request $request
     * @param CityService $cities
     */
    public function __construct(Request $request, CityService $cities)
    {
        parent::__construct($request);
        $this->cities = $cities;   
    }

    /**
     * @param Request $request
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \App\Exceptions\ControllableException
     */
    public function search(Request $request){
        $this->validateRequestData([
           "query" => "string"
        ]);
        $cities = $this->cities->search($request->get("query"));
        return $this->responder->successResponse([
            "cities" => $cities
        ]);
    }
}
