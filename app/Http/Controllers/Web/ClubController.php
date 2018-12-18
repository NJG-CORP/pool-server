<?php

namespace App\Http\Controllers\Web;

use App\Services\ClubsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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