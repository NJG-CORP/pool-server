<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ClubsService;
use App\Services\UserService;

class HomeController extends Controller
{
    public function index()
    {

        if ((new UserService())->getUser()) {
            return view('site.pages.search');
        } else {

            $clubs = (new ClubsService())->getList(ClubsService::LIMIT_LIST, true, null, null, [
                'lat' => [
                    'min' => 59.742724
                ]
            ]);

            $json_markers = (new ClubsService())->getMarkers($clubs);
            return view('site.main.main', compact('json_markers'));
        }
    }
}
