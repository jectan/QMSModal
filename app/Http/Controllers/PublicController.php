<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caller;
use App\Models\Barangay;
use App\Models\CallerType;
use App\Models\Ticket;
use App\Services\SeriesService;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

      
        }
        return view('layouts.tracks');
    }
    public function showTicket(Request $request)
    {
        // dd('hi');
        
        // dd('yaho');
        $searchid = $request->track_ticket;
    //    dd ($request->all()); //11
    //11
        // dd($searchid);
        $checkifexist = Ticket::where("ticket_no", $searchid)->first();
        // dd($checkifexist);
        if ($checkifexist) {
            return view('pages.feedback.track-view',['ticket' =>  $checkifexist])->render();
            
            
            // dd($checkifexist);
        //    return redirect('/home/track-view');
    }
    else {
        // $ticket = ticket::find($searchid);
        // $caller = Caller::findOrFail($searchid);
        return redirect()->back()->with('error',  'Sorry, Ticket not found');


        // $caller_types = CallerType::all();
       
        // return view('pages.feedback.track-view');
             }
    // return view('pages.feedback.track-view');
    }
}

   


  

     
