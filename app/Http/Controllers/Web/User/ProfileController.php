<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\GamePayment;
use App\Models\GameTime;
use App\Models\GameType;
use App\Models\TermRelation;
use App\Models\UserGameTime;
use App\Models\UserGameTypes;
use App\Models\UserPayment;
use App\Services\InvitationService;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $types = UserGameTypes::where('user_id', $user->id)->first();
        $payments = UserPayment::where('user_id', $user->id)->first();
        $days = UserGameTime::where('user_id', $user->id)->first();
        return view('site.user.profile.profile', compact('user', 'types', 'payments', 'days'));
    }

    public function card(){
        $user = Auth::user();
        return view('site.user.profile.card', compact('user'));
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
            'age' => 'required|numeric',
            'types' => 'required',
            'days' => 'required',
            'payment' => 'required',
            'sex' => 'required',
            'email' => 'required|email',
        ]);
        $req_user = $request->id;
        $base_user = Auth::user()->id;
        if ($req_user == $base_user){
            $user = Auth::user();

            //обновляем(добавляем) дни игры пользователья
            $game_times = UserGameTime::where('user_id', $user->id)->first();
            if ($game_times) {
                in_array('monday', $request->days) ? $game_times->monday = 1 : $game_times->monday = 0;
                in_array('tuesday', $request->days) ? $game_times->tuesday = 1 : $game_times->tuesday = 0;
                in_array('wednesday', $request->days) ? $game_times->wednesday = 1 : $game_times->wednesday = 0;
                in_array('thursday', $request->days) ? $game_times->thursday = 1 : $game_times->thursday = 0;
                in_array('friday', $request->days) ? $game_times->friday = 1 : $game_times->friday = 0;
                in_array('saturday', $request->days) ? $game_times->saturday = 1 : $game_times->saturday = 0;
                in_array('sunday', $request->days) ? $game_times->sunday = 1 : $game_times->sunday = 0;
                $game_times->save();
            }else{
                $days = new UserGameTime();
                $days->user_id = $user->id;
                in_array('monday', $request->days) ? $days->monday = 1 : '';
                in_array('tuesday', $request->days) ? $days->tuesday = 1 : '';
                in_array('wednesday', $request->days) ? $days->wednesday = 1 : '';
                in_array('thursday', $request->days) ? $days->thursday = 1 : '';
                in_array('friday', $request->days) ? $days->friday = 1 : '';
                in_array('saturday', $request->days) ? $days->saturday = 1 : '';
                in_array('sunday', $request->days) ? $days->sunday = 1 : '';
                $days->save();
            }


            //обновляем(добавляем) типы игры пользователья
            $game_types = UserGameTypes::where('user_id', $user->id)->first();
            if ($game_types) {
                in_array('snooker', $request->types) ? $game_types->snooker = 1 : $game_types->snooker = 0;
                in_array('pool', $request->types) ? $game_types->pool = 1 : $game_types->pool = 0;
                in_array('russian', $request->types) ? $game_types->russian = 1 : $game_types->russian = 0;
                in_array('caromball', $request->types) ? $game_types->caromball = 1 : $game_types->caromball = 0;
                $game_types->save();
            }else{
                $types = new UserGameTypes();
                $types->user_id = $user->id;
                in_array('snooker', $request->types) ? $types->snooker = 1 : $types->snooker = 0;
                in_array('pool', $request->types) ? $types->pool = 1 : $types->pool = 0;
                in_array('russian', $request->types) ? $types->russian = 1 : $types->russian = 0;
                in_array('caromball', $request->types) ? $types->caromball = 1 : $types->caromball = 0;
                $types->save();
            }

            //обновляем(добавляем) метод оплаты пользователья
            $payments = UserPayment::where('user_id', $user->id)->first();
            if ($payments) {
                in_array('half', $request->payment) ? $payments->half = 1 : $payments->half = 0;
                in_array('me', $request->payment) ? $payments->me = 1 : $payments->me = 0;
                in_array('you', $request->payment) ? $payments->you = 1 : $payments->you = 0;
                in_array('unimportant', $request->payment) ? $payments->unimportant = 1 : $payments->unimportant = 0;
                $payments->save();
            }else{
                $pay = new UserPayment();
                $pay->user_id = $user->id;
                in_array('half', $request->payment) ? $pay->half = 1 : '';
                in_array('me', $request->payment) ? $pay->me = 1 : '';
                in_array('you', $request->payment) ? $pay->you = 1 : '';
                in_array('unimportant', $request->payment) ? $pay->unimportant = 1 : '';
                $pay->save();
            }


            //если пользователь хочет изменить пароль
            if ($request->oldPassword){
                //проверяем совпадение
                if (Hash::check($request->oldPassword, $user->password)){
                    $new_pass = $request->newPassword;
                    $new_pass = Hash::make($new_pass);
                }else{
                    return redirect()->back()->with('error', 'Вы ввели неправильный пароль!');
                }
            }


            //сохраняем данные
            $user->email = $request->email;
            $user->age = $request->age;
            $user->gender = $request->sex;
            $user->phone = $request->phone;
            $user->street = $request->location;
            $user->game_time_to = $request->time;
            if (!empty($new_pass)){$user->password = $new_pass;}
            $user->save();
            return redirect()->back()->with('success', 'Профиль успешно обновлен!');
        }else{
            return redirect()->back()->with('error', 'Что-то пошло не так.');
        }

    }

}