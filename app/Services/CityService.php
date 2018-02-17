<?php
namespace App\Services;

use App\Exceptions\ControllableException;
use App\Models\Club;
use App\Models\User;
use App\Utils\R;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class CityService
{
    public function search(){
        return Club::with(['rating', 'location', 'image'])->get();
    }
}
