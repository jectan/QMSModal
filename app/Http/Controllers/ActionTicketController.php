<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeAssignedTicket;

class ActionTicketController extends Controller
{
    public function store(Request $request)
    {  
            $action_ticket = OfficeAssignedTicket::updateOrCreate(['id' => $request->id],
            [ 
                'office' =>$request->office,
                'status'=>$request->status,
                'work_start'=>$request->work_start,
                'estimated_time'=>$request->estimated_time,
                'remarks'=>$request->remarks,
                'actual_date'=>$request->actual_date,               
            ]);   

               
            return response()->json(['success'=> 'Successfully saved.', 'action_ticket' => $action_ticket]);
   }
    
      
   
}
