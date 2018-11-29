<?php

namespace App\Http\Controllers\Web;

use App\Models\GameTime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()){
            return view('site.pages.search');
        }
        else{
            return view('site.main.main');
        }
    }

    public function search(Request $request)
    {

        $request->validate([
           ''
        ]);

    }


}
