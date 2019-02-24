<?php

namespace App\Http\Controllers\Web\Club;

use App\Http\Controllers\Controller;
use App\Services\ClubsService;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function list(Request $request)
    {
        $name = $request->get('club_search', null);
        $clubs = (new ClubsService())->getList(ClubsService::LIMIT_LIST, true, $name);
        $json_markers = (new ClubsService())->getMarkers($clubs);
        return view('site.clubs.list', compact('clubs', 'json_markers'));
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
        $review_form = RatingService::canUserRate($club, \Auth::user());
        $partners_review = $club->rating;

        return view('site.clubs.single', compact('club', 'review_form', 'partners_review'));
    }

    public function suggestClub(string $name)
    {
        $results = (new ClubsService())->findSuggestion($name);

        return response()
            ->json([
                'results' => $results
            ]);
    }

    public function searchClubs(string $city)
    {
        try {
            $clubs = (new ClubsService())->findByCity($city);
        } catch (\Exception $exception) {
            throw new NotFoundHttpException();
        }

        return response()
            ->json($clubs);
    }
}