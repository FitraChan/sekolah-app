<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Home extends Controller
{
    public function index()
    {
		return view('dashboard')->with(['drop_down'=>null, 'side'=>'admin']);
    }
}
