<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caller;
use App\Models\Barangay;
use App\Models\CallerType;
use App\Models\Ticket;
use App\Services\SeriesService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class FeedbackController extends Controller
{

    public function __construct(
        SeriesService $series_service
    ) {
        $this->series_service = $series_service;
    }

    public function index()
    {
        return view('pages.feedback.rate');
    }
    public function about()
    {
        return view('pages.feedback.about-us');
    }
       
    // public function showTicket()
    // {
    //     return view('pages.feedback.track-view');
    // }
    public function publichome()
    {
        return view('auth.login');
    }
    public function showform()
    {
        $barangay = Barangay::all();
        $barangay = Barangay::all();
        $caller_types = CallerType::all();
        return view('pages.feedback.e-sumbong-form')->with(['caller_types'=>$caller_types, 'barangays'=>$barangay]);
       
    }
    public function rate()
    {
        return view('pages.feedback.rate', ['status' => 'responded' , 'ticket_status' => 'resolved']);
    }


    public function saveForm(Request $request)
    {

        // if (!isset($request->anonymous)) {
            $random = Str::random(4);
            $rule = [
                'firstname'  => 'required|string|max:100',
                'middlename'   => 'nullable|string|max:100',
                'lastname' => 'required|string|max:100',
                'contact_no' => 'required|numeric|digits_between:10,11',
            ];   
            $request->validate($rule);
           
            $caller = Caller::where("firstname", $request->firstname)
                ->where("middlename", $request->middlename)
                ->where("lastname", $request->lastname)->where("contact_no", $request->contact_no)->first();
              
                if (!$caller) {
                //save caller
              
                $caller=Caller::Create(
                    [
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'address' => $request->address,
                        'contact_no' => $request->contact_no,
                     
                        'email' => $request->email,    
                        'is_anonymous' =>$request->anonymous ? true: false,  
                        'barangay_id' => $request->barangay,  
                    ]);  
                    
            }
        // }else{
        //     $rule = [
        //         'contact_no' => 'required|string|max:12|min:12',
        //     ];    
        //     $request->validate($rule);
        //     $caller = Caller::where("contact_no", $request->contact_no)
        //     ->where("is_anonymous", "=", "1")->first();
        //     if (!$caller) {
        //         //save anon caller
        //         $caller=Caller::Create(
        //             [
        //                 'contact_no' => $request->contact_no,
        //                 'email' => $request->email,    
        //                 'is_anonymous' =>$request->anonymous ? true: false,  
        //                 'barangay_id' => $request->barangay,  
        //             ]);            
        //     }
        // }
        
        //save ticket
        $ticket=Ticket::Create(
            [
                'ticket_no' => $this->series_service->get('ticket'). '' . $random,
                'caller_id' => $caller->id,
                'call_type_id' => $request->call_type_id,
                'call_details' => $request->call_details,
                'ticket_category'=> 'online',
                'call_datetime' => \Carbon\Carbon::now(),
                //'agent_id' =>  Auth::id(),
                'call_status' =>'Requested',  
                'status' => 'created',    
            ]);   
    
            return redirect('/')->with(['success' => 'Successfully Submitted!', 'ticket_no' =>  $ticket->ticket_no]);
    }
}