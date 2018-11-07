<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\InvitationService;

class ProfileController extends Controller
{
    public function invites()
    {
        return view('site.user.profile.invites',['result' => (new InvitationService())->invitationList(\Auth::user())]  );
    }
}