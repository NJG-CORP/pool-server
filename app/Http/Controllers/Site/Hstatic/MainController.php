<?php

namespace App\Http\Controllers\Site\Hstatic;

use App\Http\Controllers\Site\BaseController;

class MainController extends BaseController
{
    public $layout = 'layouts.default';

    public function index()
    {
        return view('site/main/main');
    }
}