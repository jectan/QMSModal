<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caller;
use App\Models\Barangay;
use App\Models\CallerType;
use App\Models\Ticket;
use App\Services\SeriesService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class FeedbackTrackController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

      
        }
        return view('layouts.tracks');
    }
    public function showFeedback(Request $request)
    {
        
        $searchid = $request->trackfeedback_ticket;
        $ticket = Ticket::where("ticket_no", $searchid)->first();
    if ($ticket) {
        
        
         if($ticket->rating == ''){

            } else{
            return redirect()->back()->with('error',  'You have submitted a feedback');
        }
        if($ticket->status == 'closed' )
        {
            // if($ticket->date_rated == '')
            // if($ticket->status == 'with-feedback' && $ticket->date_rated != '')
            // {
                return view('pages.feedback.rate',['ticket' =>  $ticket])->render();
         
        }else{
            return redirect()->back()->with('error',  'Oops.. Your ticket is still ongoing');
        }
    }
    else {
        return redirect()->back()->with('error',  'Sorry, Ticket not found');
    }
  
    //     $checkifexist = Ticket::where("ticket_no", $searchid)->first();
    //     // $checkifexist = Ticket::where("ticket_no", $searchid)->where('status','closed')->first();
    //     if ($checkifexist) {
    //         return view('pages.feedback.rate',['ticket' =>  $checkifexist])->render();
       
    // }
    
    // else {
      
    //         return redirect()->back()->with('error',  'Sorry, Ticket not found');

    //          }
  
    }
    public function savefeedback(Request $request)
    {
       
            $ticket = Ticket::find($request->id);
    
            $ticket->feedback=$request->feedback_details ? $request->feedback_details  : '';
            
            $ticket->rating = $request->rate;
            $ticket->date_rated=Carbon::now();
            $ticket->update();
            return redirect('/')->with(['success' => 'Successfully Submitted!']);
        
    }

   


  
}
     
