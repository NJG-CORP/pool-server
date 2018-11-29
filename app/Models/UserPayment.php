<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table = 'user_payments';
    protected $fillable = [
        'user_id', 'half', 'me', 'you', 'default'
    ];

    public $timestamps = false;
}
