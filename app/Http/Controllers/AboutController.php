<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $page = 'About'; // for active nav highlightings
        return view('pages.aboutus.index', compact('page'));
    }
}
