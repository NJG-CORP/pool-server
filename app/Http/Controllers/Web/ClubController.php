<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ClubsService;

class ClubController extends Controller
{
    public function club()
    {
        $clubs = (new ClubsService())->getList();
        return view('site.pages.clubs', compact('clubs'));
    }

    public function showClub($id)
    {
        $club = (new ClubsService())->getOne($id);
        return view('site.pages.clubs-single', compact('club'));
    }
}