<?php

namespace App\Http\Controllers\Web;

use App\Mail\ReviewMail;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class ContactsController extends Controller
{
    public function index()
    {
        return view('site.pages.contacts');
    }

    public function review(Request $request)
    {
        $request->validate([
           'phone' => 'required',
           'email' => 'required|email',
           'location' => 'required'
        ]);

        $review = new Review();
        $review->email = $request->email;
        $review->phone = $request->phone;
        $review->location = $request->location;
        $review->save();

        $data = [];
        $data['message'] = 'Empty';
        $data['email'] = $request->email;
        $data['location'] = $request->location;
        $data['phone'] = $request->phone;
        Mail::to(env('ADMIN_EMAIL', ''))->queue(new ReviewMail($data));

        return redirect()->back()->with('success', 'Спасибо за отзыв');
    }
}
