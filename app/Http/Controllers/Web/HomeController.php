<?php

namespace App\Http\Controllers\Web;

use App\Models\GameTime;
use App\Models\User;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use function GuzzleHttp\Promise\all;
use Illuminate\Database\Eloquent\Collection;
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
           'days' => 'required',
           'types' => 'required',
           'payment' => 'required',
           'sex' => 'required'
        ]);

        $types = $request->types;
        $days = $request->days;
        $payment = $request->payment;
        $sex = $request->sex;

        $users = User::where('gender', $sex)->get();
        $users_id = [];
        foreach ($users as $user) {
            $users_id[] = $user->id;
        }

        in_array('pool', $types) ? $pool = 1 : '';
        in_array('snooker', $types) ? $snooker = 1 : '';
        in_array('russian', $types) ? $russian = 1 : '';
        in_array('caromball', $types) ? $caromball = 1 : '';
        in_array('half', $payment) ? $half = 1 : '';
        in_array('me', $payment) ? $me = 1 : '';
        in_array('you', $payment) ? $you = 1 : '';
        in_array('default', $payment) ? $default = 1 : '';
        in_array('monday', $payment) ? $monday = 1 : '';
        in_array('tuesday', $payment) ? $tuesday = 1 : '';
        in_array('wednesday', $payment) ? $wednesday = 1 : '';
        in_array('thursday', $payment) ? $thursday = 1 : '';
        in_array('friday', $payment) ? $friday = 1 : '';
        in_array('saturday', $payment) ? $saturday = 1 : '';
        in_array('sunday', $payment) ? $sunday = 1 : '';

        $searched_type = UserGameTypes::where('pool', $pool)->where('snooker', $snooker)->where('russian', $russian)->where('caromball', $caromball);
        $searched_payment = UserPayment::where('half', $half)->where('me', $me)->where('you', $me)->where('you', $you)->where('default', $default);
        $searched_days = UserGameTime::where('monday', $monday)->where('tuesday', $tuesday)->where('wednesday', $wednesday)->where('thursday', $thursday)->where('friday', $friday)->where('saturday', $saturday)->where('sunday', $sunday);


        return redirect()->back();
    }


}
