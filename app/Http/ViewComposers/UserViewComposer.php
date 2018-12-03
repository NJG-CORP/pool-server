<?php

namespace App\Http\ViewComposers;

use App\Services\UserService;
use Illuminate\Contracts\View\View;

class UserViewComposer {

    public function compose(View $view) {
        $user = (new UserService())->getUser();
        $view->with('user', $user);
    }
}