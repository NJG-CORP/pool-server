<?php

namespace App\Http\Controllers;

use App\Services\ClubsService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * @var ClubsService
     */
    private $clubs;

    /**
     * ClubController constructor.
     * @param Request $request
     * @param ClubsService $clubs
     */
    public function __construct(Request $request, ClubsService $clubs)
    {
        parent::__construct($request);
        $this->clubs = $clubs;
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
}
