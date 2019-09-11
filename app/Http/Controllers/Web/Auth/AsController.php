<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Web\Controller;
use App\Models\User;
use Auth;

class AsController extends Controller
{
    public function loginAs(int $userId)
    {
        Auth::guard()->login(User::query()->where('id', $userId)->first());
        return redirect('/');
    }
}