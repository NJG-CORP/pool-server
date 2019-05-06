<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\GamePayment;
use App\Models\User;
use App\Services\CityService;
use App\Services\ImageService;
use App\Services\InvitationService;
use App\Services\PlayerService;
use App\Services\RatingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function card($id = 0)
    {
        if ($id != 0) {
            $user_profile = User::query()->get()->where('id', '=', $id)->first();
            if (!$user_profile) {
                throw new NotFoundHttpException();
            }
        } else {
            $user_profile = Auth::user();
        }
        $reviews = RatingService::getUserRevies($user_profile);
        return view('site.user.profile.card', compact('reviews', 'user_profile'));
    }

    public function invites()
    {
        return view('site.user.profile.invites', ['invites' => (new InvitationService())->invitationList(\Auth::user())]);
    }

    public function partners()
    {
        $partners = (new InvitationService())->partnersList(\Auth::user());
        return view('site.user.profile.partners', compact('partners'));
    }

    public function updateProfile(Request $request)
    {
        // валидация полей
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'age' => 'required|numeric',
            'game_type' => 'required',
            'game_days' => 'required',
            'game_payment_type' => 'required',
            'gender' => 'required',
        ]);
        if ($validator->fails()) {
            dd($validator);
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