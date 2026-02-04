<?php

namespace App\Http\Controllers\author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class authorLoginController extends Controller
{
    public function authorlogin(){
        
        return view('backEnd.auth.login');
    }
}