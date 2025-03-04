<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Office;
use App\Models\OfficeAssignedTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index(){
   
        $array = [];
        $resolve=[];
        $assigned=[];
        $feedback=[];
       
       //-------- Chart for Tickets Resolved --------//
        $tickets_one = Ticket::whereHas("actions", function($query){ 
            $query->where("status", "resolved")
            ->whereRaw('status_updated_at <= DATE_ADD(created_at, INTERVAL 1 DAY)')->with("ticket");
         })->where('status', 'closed')->count();

        $tickets_three = Ticket::whereHas("actions", function($query){ 
                $query->where("status", "resolved")
                ->whereRaw('status_updated_at <= DATE_ADD(created_at, INTERVAL 3 DAY)')->with("ticket");
             })->where('status', 'closed')->count();

        $tickets_five = Ticket::whereHas("actions", function($query){ 
                $query->where("status", "resolved")
                ->whereRaw('status_updated_at <= DATE_ADD(created_at, INTERVAL 5 DAY)')->with("ticket");
             })->where('status', 'closed')->count();

        $tickets_seven = Ticket::whereHas("actions", function($query){ 
                $query->where("status", "resolved")
                ->whereRaw('status_updated_at <= DATE_ADD(created_at, INTERVAL 7 DAY)')->with("ticket");
             })->where('status', 'closed')->count();

        $tickets_thirty = Ticket::whereHas("actions", function($query){ 
                $query->where("status", "resolved")
                ->whereRaw('status_updated_at <= DATE_ADD(created_at, INTERVAL 30 DAY)')->with("ticket");
             })->where('status', 'closed')->count();

        $greater_thirty = Ticket::whereHas("actions", function($query){ 
                $query->where("status", "resolved")
                ->whereRaw('status_updated_at > DATE_ADD(created_at, INTERVAL 30 DAY)')->with("ticket");
             })->where('status', 'closed')->count();

            $resolve['label_type'][] = '1 day'; //label
            $resolve['data_type'][] =  $tickets_one;   
            $resolve['label_type'][] = '3 days'; //label
            $resolve['data_type'][] =  $tickets_three; 
            $resolve['label_type'][] = '5 days'; //label
            $resolve['data_type'][] =  $tickets_five;   
            $resolve['label_type'][] = '7 days'; //label
            $resolve['data_type'][] =  $tickets_seven; 
            $resolve['label_type'][] = '30 days'; //label
            $resolve['data_type'][] =  $tickets_thirty;
            $resolve['label_type'][] = '> 30 days'; //label
            $resolve['data_type'][] =  $greater_thirty;

         $array['resolve_data'] = json_encode($resolve);
        
         //-------- Chart for Tickets Assigned --------//
         $tickets = Ticket::all();
         $assigned = array();
         $within_one_count = 0;
         $within_three_count = 0;
         $more_than_three_count=0;
         foreach ($tickets as $ticket) {
                     $within_one = Ticket::whereHas("actions", function($query) use ($ticket){
                        $query->where("status", "pending")->whereDate('created_at' ,'<=', Carbon::parse($ticket->created_at)->addDays(1) )
                        ->with("ticket");})->where('status', 'assigned')->count();

                     if ( $within_one) {
                         $within_one_count +=  $within_one;
                     }
                    $within_three = Ticket::whereHas("actions", function($query) use ($ticket){
                            $query->where("status", "pending")->whereDate('created_at' ,'<=', Carbon::parse($ticket->created_at)->addDays(3) )
                            ->with("ticket");})->where('status', 'assigned')->count();
     
                            if ( $within_three) {
                             $within_three_count +=  $within_three;
                         }
                    $more_than_three = Ticket::whereHas("actions", function($query) use ($ticket){
                            $query->where("status", "pending")->whereDate('created_at' ,'>', Carbon::parse($ticket->created_at)->addDays(3) )
                            ->with("ticket");})->where('status', 'assigned')->count();
                            if ( $more_than_three) {
                             $more_than_three_count +=  $more_than_three;
                         }      
        }
         $assigned['label_type'][] = '1 day';
         $assigned['data_type'][] = $within_one_count;
     
         $assigned['label_type'][] = '3 day';
         $assigned['data_type'][] = $within_three_count;
     
         $assigned['label_type'][] = 'more than 3 day';
         $assigned['data_type'][] = $more_than_three_count;

         $array['assigned_data'] = json_encode($assigned);
       
      
            
       //-------- Chart for Tickets Feedback --------//
       
       $feedback_one = Ticket::where('status', 'closed')
       ->whereRaw('date_rated <= DATE_ADD(status_updated_at, INTERVAL 1 DAY)')->count();
       $feedback_three = Ticket::where('status', 'closed')
       ->whereRaw('date_rated <= DATE_ADD(status_updated_at, INTERVAL 3 DAY)')->count();
       $feedback_greater_three = Ticket::where('status', 'closed')
       ->whereRaw('date_rated > DATE_ADD(status_updated_at, INTERVAL 3 DAY)')->count();


        $feedback['label_type'][] = '1 day';
         $feedback['data_type'][] = $feedback_one;
     
         $feedback['label_type'][] = '3 day';
         $feedback['data_type'][] = $feedback_three;
     
         $feedback['label_type'][] = 'more than 3 day';
         $feedback['data_type'][] = $feedback_greater_three;

         $array['feedback_data'] = json_encode($feedback);


        //--------for progress bar       
        $array['total_Ratings']  = DB::table('tickets')->where('status','closed')->where('status_updated_at', '!=',' ')->count();
        $array['five_star'] = DB::table('tickets')->where('rating','5')->count();
        $array['four_star'] = DB::table('tickets')->where('rating','4')->count();
        $array['three_star'] = DB::table('tickets')->where('rating','3')->count();
        $array['two_star'] = DB::table('tickets')->where('rating','2')->count();
        $array['one_star'] = DB::table('tickets')->where('rating','1')->count();


        return view('home', $array);
    }

    public function compute(){

        $total['totalTickets']  = DB::table('tickets')->count();
        $total['created'] = DB::table('tickets')->where('status','created')->count();
        $total['assigned'] = DB::table('tickets')->where('status','assigned')->count();
        $total['working'] = DB::table('tickets')->where('status','working')->count();
        $total['for-closing'] = DB::table('tickets')->where('status','for-closing')->count();
        $total['closed'] = DB::table('tickets')->where('status','closed')->count();
       
        echo json_encode($total);
        exit;
    }

    public function topTenHighOffices(Request $request)
    {         
        if($request->ajax())
        {
            $offices = Office::all();
            $office_tmp = array();
            foreach ($offices as $office) {
                    $total_processed = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("status",'closed')
                        ->where('status_updated_at', '!=',' ')->count();

                    $total_five_star = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("rating",'5')->count();
                    $total_four_star = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("rating",'4')->count();
                array_push(
                    $office_tmp,
                    array(
                        'office' => $office->name,                      
                        'total_processed' => $total_processed,
                        'total_five_star' => $total_five_star,
                        'total_four_star' => $total_four_star,
                    )
                );
            }
            return datatables()->of($office_tmp)->addIndexColumn()->make(true);
        }
    }

    public function topTenLowOffices(Request $request)
    {         
        if($request->ajax())
        {
            $offices = Office::all();
            $office_tmp = array();
            foreach ($offices as $office) {
                    $total_processed = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("status",'closed')
                        ->where('status_updated_at', '!=',' ')->count();

                    $total_two_star = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("rating",'2')->count();
                    $total_one_star = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("rating",'1')->count();
                array_push(
                    $office_tmp,
                    array(
                        'office' => $office->name,                      
                        'total_processed' => $total_processed,
                        'total_two_star' => $total_two_star,
                        'total_one_star' => $total_one_star,
                    )
                );
            }
            return datatables()->of($office_tmp)->addIndexColumn()->make(true);
        }
    }

    public function topFiveUnresolved(Request $request)
    {         
        if($request->ajax())
        {
            $offices = Office::all();
            $office_tmp = array();
            foreach ($offices as $office) {
                    $total_processed = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->count();

                    $total_unresolved = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->where("status",'unresolved')->count();
                array_push(
                    $office_tmp,
                    array(
                        'office' => $office->name,                      
                        'total_processed' => $total_processed,
                        'total_unresolved' => $total_unresolved,
                    )
                );
            }
            return datatables()->of($office_tmp)->addIndexColumn()->make(true);
        }
    }


    public function topFiveResolved(Request $request)
    {         
        if($request->ajax())
        {
            $offices = Office::all();
            $office_tmp = array();
            foreach ($offices as $office) {
                    $total_processed = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->count();
                        

                    $one_day = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })
                              ->whereDate("updated_at",'<=', Carbon::parse($office->created_at)->addDays(1))
                              ->where("status",'unresolved')->count();
                    
                    $three_days = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                         $query->where("office_id",$office->id ); })
                               ->whereDate("updated_at",'<=', Carbon::parse($office->created_at)->addDays(3))
                               ->where("status",'unresolved')->count();

                        
                array_push(
                    $office_tmp,
                    array(
                        'office' => $office->name,                      
                        'total_processed' => $total_processed,
                        'one_day' => $one_day,
                        'three_days' => $three_days,
                    )
                );
            }
            return datatables()->of($office_tmp)->addIndexColumn()->make(true);
        }
    }


    public function topLowResolved(Request $request)
    {         
        if($request->ajax())
        {
            $offices = Office::all();
            $office_tmp = array();
            foreach ($offices as $office) {
                
                    $total_processed = Ticket::whereHas("actions", function($query) use ($office){
                        $query->where("office_id",$office->id ); })->count();
                        
                    $five_days = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })
                              ->whereDate("updated_at",'<=', Carbon::parse($office->created_at)->addDays(5))
                              ->where("status",'unresolved')->count();
                    
                    $seven_days = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })
                               ->whereDate("updated_at",'<=', Carbon::parse($office->created_at)->addDays(7))
                               ->where("status",'unresolved')->count();

                    $thirty_days = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })
                                ->whereDate("updated_at",'<=', Carbon::parse($office->created_at)->addDays(30))
                                ->where("status",'unresolved')->count();

                    $more_than = OfficeAssignedTicket::whereHas("ticket", function($query) use ($office){
                        $query->where("office_id",$office->id ); })
                                ->whereDate("updated_at",'>', Carbon::parse($office->created_at)->addDays(30))
                                ->where("status",'unresolved')->count();
                        
                array_push(
                    $office_tmp,
                    array(
                        'office' => $office->name,                      
                        'total_processed' => $total_processed,
                        'five_days' => $five_days,
                        'seven_days' => $seven_days,
                        'thirty_days' => $thirty_days,
                        'more_than' => $more_than,
                    )
                );
            }
            return datatables()->of($office_tmp)->addIndexColumn()->make(true);
        }
    }
}
