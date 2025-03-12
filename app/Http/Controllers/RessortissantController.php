<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RessortissantController extends Controller
{
    public function index()
    {
        return view('ressortissant/dashboard');
    }
}
