<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return view('index')->with('title', 'Eskwela4EveryJuan');
    }

    public function home() {
        return view('home')->with('title', 'Eskwela4EveryJuan');
    }

    public function landing() {
        return view('landing')->with('title', 'Eskwela4EveryJuan | Home Page');
    }

    public function terms() {
        return view('terms&condition')->with('title', 'Terms & Services');
    }
    
    public function data_privacy() {
        return view ('data_privacy')->with('title', 'Data Privacy Act of 2012');
    }
}
