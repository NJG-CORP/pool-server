<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CityController extends Controller
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
}
