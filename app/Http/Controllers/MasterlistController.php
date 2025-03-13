<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterlistController extends Controller
{
    // Display the masterlist view
    public function index()
    {
        return view('pages.masterlist.index');
    }
}
