<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Web\Controller;

class ChatController extends Controller
{
    public function chat($id = 0)
    {
        return view('site.chat.chat', ['threadId' => $id]);
    }
}