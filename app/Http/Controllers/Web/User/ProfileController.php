<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\GamePayment;
use App\Models\GameTime;
use App\Models\GameType;
use App\Models\TermRelation;
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
        return view('site.user.profile.profile', compact('user'));
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
            $game_times = GameTime::where('user_id', $user->id)->get();
            $terms = TermRelation::where('relationable_id', $user->id)->get();
            $voc_type = Vocabulary::where('name', 'GameType')->first();
            $voc_payment = Vocabulary::where('name', 'GamePaymentType')->first();

            //сначала удаляем прежние дни, если есть их
            if ($game_times){
                foreach ($game_times as $game_time){
                    $game_time->delete();
                }
            }
            //потом добавляем новые отмеченные дни
            foreach($request->days as $day){
                $weekday = new GameTime();
                $weekday->user_id = $user->id;
                $weekday->weekday_id = $day;
                $weekday->save();
            }

            //удаляем прежняя дата пользователья
            if ($terms){
                foreach ($terms as $term){
                    $term->delete();
                }
            }
            //потом добавляем новые отмеченные
            foreach($request->types as $type){
                $gametype = new TermRelation();
                $gametype->relationable_id = $user->id;
                $gametype->relationable_type = 'App\Models\User';
                $gametype->term_id = $type;
                $gametype->vocabulary_id = $voc_type->id;
                $gametype->save();
            }
            foreach($request->payment as $payment){
                $gamepayment = new TermRelation();
                $gamepayment->relationable_id = $user->id;
                $gamepayment->relationable_type = 'App\Models\User';
                $gamepayment->term_id = $payment;
                $gamepayment->vocabulary_id = $voc_payment->id;
                $gamepayment->save();
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