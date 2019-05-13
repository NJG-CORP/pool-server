<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\ClubsService;
use App\Services\UserService;

class HomeController extends Controller
{
    public function index()
    {

        if ((new UserService())->getUser()) {
            $clubs = Club::query()->get(['title', 'id']);
            return view('site.pages.search', compact('clubs'));
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
