<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Services\PlayerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $validator2 = Validator::make($request->all(), [
            'types' => 'required',
            'gender' => 'required',
            'payment' => 'required',
            'days' => 'required',
        ]);
        if ($validator2->fails()) {
            return redirect()->back()->withErrors($validator2)->withInput();
        }
        $fields = $request->all();
        $back = (new PlayerService())->search($fields, \Auth::user());
        $results = $back['players'];
        $total = $back['total'];
        return view('site.pages.search', compact('results', 'total'));
    }
}
