<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;

class MailController extends Controller
{
    public function index() {

        $data = [
            'subject' => 'My Email to you',
            'body' => 'Hello! This is my first email sent through Laravel.'
        ];
        
        try {
            // Create an instance of MailNotify
            $mailNotify = new MailNotify($data);

            // Call the to() method on the instance, not statically on the class
            Mail::to('ktimblaco25@gmail.com')->send($mailNotify);
            
            return response()->json(['Great! check your mail box']);

        } catch (\Exception $th) {
            dd($th);
            return response()->json(['Error in sending email']);
        }
    }
}