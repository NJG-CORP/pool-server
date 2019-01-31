<?php

namespace App\Http\Controllers\Web\Club;

use App\Http\Controllers\Controller;
use App\Services\ClubsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function list()
    {
        $clubs = (new ClubsService())->getList();
        return view('site.pages.clubs', compact('clubs'));
    }

    public function viewId($id)
    {
        $club = (new ClubsService())->getOne($id);
        if (!$club) {
            throw new NotFoundHttpException;
        }

        return redirect()->route('club.show', ['url' => $club->url]);
    }

    public function view(string $url)
    {
        $club = (new ClubsService())->getClubByUrl($url);
        if (!$club) {
            throw new NotFoundHttpException;
        }

        return view('site.pages.clubs-single', compact('club'));
    }
}