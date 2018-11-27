<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\InvitationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
            $types = $request->types;
            $types = json_encode($types);
            $days = $request->days;
            $days = json_encode($days);
            $payment = $request->payment;
            $payment = json_encode($payment);

            $user->email = $request->email;
            $user->age = $request->age;
            $user->gender = $request->sex;
            $user->phone = $request->phone;
            $user->street = $request->location;
            $user->game_time_to = $request->time;
            $user->game_types = $types;
            $user->days = $days;
            $user->payment = $payment;
            $user->save();
            return redirect()->back()->with('success', 'Your profile was successfully changed!');
        }else{
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }

}