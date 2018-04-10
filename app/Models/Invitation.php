<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = [];
    protected $hidden = [];

    public function inviter(){
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invited(){
        return $this->belongsTo(User::class, 'invited_id');
    }

    public function club(){
        return $this->belongsTo(Club::class);
    }
}
