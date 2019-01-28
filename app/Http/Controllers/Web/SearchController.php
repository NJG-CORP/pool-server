<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'types' => 'required',
            'sex' => 'required',
            'payment' => 'required',
            'days' => 'required'
        ]);
        $fields = $request->all();
        $results = (new PlayerService())->search($fields);
        return view('site.pages.search', compact('results'));
    }
}
