<?php

namespace App\Http\Controllers\Web;

use App\Models\Blog;
use App\Models\GameTime;
use App\Models\News;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use App\Services\PlayerService;
use App\Services\UserService;
use function GuzzleHttp\Promise\all;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class HomeController extends Controller
{
    public function index()
    {

        if ((new UserService())->getUser()) {
            return view('site.pages.search');
        } else {
            return view('site.main.main');
        }
    }

    public function search(Request $request)
    {
        $request->validate([
           'types' => 'required',
           'sex' => 'required',
           'payment' => 'required',
           'days' => 'required'
        ]);
        $fields = $request->all();
        $search = new PlayerService();
        $results = $search->search($fields);

        return view('site.pages.search', compact('results'));
    }

}
