<?php

namespace App\Utils;

use Illuminate\Mail\Message;

class Utils{
    static function sendMail($text, $to, $subject = ""){
        \Mail::raw(
            $text,
            function (Message $message) use ($to, $subject){
                $message->from("tmpr@poolbuddy.ru", "Poolbuddy.ru");
                $message->subject($subject);
                $message->to($to);
            }
        );
    }
}

