<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\GamePayment;
use App\Services\CityService;
use App\Services\ImageService;
use App\Services\InvitationService;
use App\Services\PlayerService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = new UserService();
        $user = $user->getUser();
        $player = new PlayerService();
        $types = $player->getUserGameType($user->id);
        $payment = $player->getUserGamePayment($user->id);
        $days = $player->getUserGameTime($user->id);
        return view('site.user.profile.profile', compact('types', 'payment', 'days'));
    }

    public function card()
    {
        return view('site.user.profile.card');
    }

    public function invites()
    {
        return view('site.user.profile.invites', ['result' => (new InvitationService())->invitationList(\Auth::user())]);
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'age' => 'required|numeric',
            'types' => 'required',
            'days' => 'required',
            'payment' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $profile = new PlayerService();
        if ($profile->save(Auth::user(), $request->all(), new CityService(), new ImageService())) {
            return redirect()->back()->with('success', 'Профиль успешно обновлен!');
        } else {
            return redirect()->back()->with('error', 'Что-то пошло не так.');
        }

    }

}