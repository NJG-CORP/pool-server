<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\PlayerService;
use Auth;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $clubs = Club::query()->get(['title', 'id']);
        return view('site.pages.search', compact('clubs'));
    }

    public function search(Request $request)
    {
        session(['_old_input' => $request->toArray()]);
        $service = new PlayerService();

        $request = $service->prepareWebRequest($request);
        $clubs = Club::query()->get(['title', 'id']);

        $results = $service->search(
            $request->get('page', 0) * 10,
            $request,
            Auth::user()
        );

        return view('site.pages.search', compact('results', 'clubs'));
    }
}
