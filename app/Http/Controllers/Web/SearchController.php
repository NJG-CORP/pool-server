<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        return view('site.pages.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'game_type' => 'required',
            'gender' => 'required',
            'game_payment_type' => 'required',
            'days' => 'required'
        ]);
        session(['_old_input' => $request->toArray()]);
        $service = new PlayerService();

        $request = $service->prepareWebRequest($request);

        $results = $service->search(
            $request->get('page', 0) * 10,
            $request,
            \Auth::user()
        );

        return view('site.pages.search', compact('results'));
    }
}
