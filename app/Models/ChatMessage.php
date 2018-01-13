<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
