<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancierController extends Controller
{
    public function index()
    {
        return view('financier.dashboard');
    }
}
