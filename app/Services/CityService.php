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
    public function getList(){
        return Club::with(['rating', 'location', 'image'])->get();
    }

    public function getOne($id){
        return Club::with(['rating', 'location', 'image'])->find($id);
    }
}
