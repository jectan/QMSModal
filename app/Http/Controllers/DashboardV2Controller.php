<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardV2Controller extends Controller
{
    //
    public function index()
    {
        return view('homev2');
    }
}
