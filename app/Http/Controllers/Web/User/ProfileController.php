<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\GamePayment;
use App\Models\GameType;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use App\Services\InvitationService;
use App\Services\PlayerService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = new UserService();
        $user = $user->getUser();
        $player = new PlayerService();
        $types = $player->getUserGameType($user->id);
        $payments = $player->getUserGamePayment($user->id);
        $days = $player->getUserGameTime($user->id);
        return view('site.user.profile.profile', compact('types', 'payments', 'days'));
    }

    public function card(){
        return view('site.user.profile.card');
    }

    public function invites()
    {
        return view('site.user.profile.invites',['result' => (new InvitationService())->invitationList(\Auth::user())]  );
    }

    public function partners()
    {
        return view('site.user.profile.partners');
    }

    public function chat()
    {
        return view('site.user.profile.chat');
    }


    public function updateProfile(Request $request)
    {
        // валидация полей
        $request->validate([
            'email' => 'required|email',
            'age' => 'required|numeric',
            'types' => 'required',
            'days' => 'required',
            'payment' => 'required',
            'sex' => 'required',
        ]);
        $fields = $request->all();
        $req_user = $fields['id'];
        $base_user = (new UserService())->getUser()->id;
        if ($req_user == $base_user){
            $profile = new PlayerService();
            $profile->save($fields);
            return redirect()->back()->with('success', 'Профиль успешно обновлен!');
        }else{
            return redirect()->back()->with('error', 'Что-то пошло не так.');
        }

    }

}