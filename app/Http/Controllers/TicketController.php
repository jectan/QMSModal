<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caller;
use App\Models\Barangay;
use App\Models\CallerType;
use App\Models\Ticket;
use App\Models\OfficeAssignedTicket;
use App\Services\SeriesService;
use Illuminate\Support\Facades\Auth;
use App\Models\Office;
use App\Models\StartworkingLog;
use App\Models\OfficeAssignedTicketLog;
use App\Models\TicketLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class TicketController extends Controller

{
    public function __construct(SeriesService $series_service) 
    {
        $this->series_service = $series_service;
    }

    public function ticketTally(){
        
        $user = Auth::user();

        $tickets['total_ticket']  = Ticket::whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();
          
        $tickets['assigned'] = Ticket::where('status', 'assigned')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $tickets['working'] = Ticket::where('status', 'working')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $tickets['for-closing'] = Ticket::where('status', 'for-closing')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $tickets['closed'] =  Ticket::where('status', 'closed')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $tickets['feedback'] = Ticket::where('status', 'closed')->whereNotNull('date_rated')->whereHas('actions', function ($query) use($user){ $query->where('office_id', $user->staff->office_id);})->count();

     
        echo json_encode($tickets);
        exit;
    }

    public function index()
    {
        $user = Auth::user();
    
        if ($user->role_id == 4){
            
            $created = Ticket::where('status', 'created')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();
            
            $assigned = Ticket::where('status', 'assigned')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();
               
            $working = Ticket::where('status', 'working')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();

            $for_closing = Ticket::where('status', 'for-closing')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();

            $closed = Ticket::where('status', 'closed')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();

            $cancelled = Ticket::where('status', 'cancelled')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();

            $with_feedback = Ticket::where('status', 'closed')->whereNotNull('date_rated')->whereHas('actions', function ($query) use($user){
                $query->where('office_id', $user->staff->office_id);})->get();

        }else{ 
            $created = Ticket::where('status', 'created')->get();
            $assigned = Ticket::where('status', 'assigned')->get();
            $working = Ticket::where('status', 'working')->get();
            $for_closing = Ticket::where('status', 'for-closing')->get();
         
            $with_feedback = Ticket::where('status', 'closed')->whereNotNull('date_rated')->get();
            $cancelled = Ticket::where('status', 'cancelled')->get();
            $closed = Ticket::where('status', 'closed')->whereNull('date_rated')->get();
    }
        return view('pages.ticket.index', ['created' => $created, 'assigned' => $assigned, 'workings' => $working, 'for_closings' => $for_closing, 'closed' => $closed, 'with_feedbacks' => $with_feedback, 'cancelled' => $cancelled, 'closed' => $closed]);
    }
     
    public function cancel(Request $request)
    {
        $ticket = Ticket::find($request->id);
        $ticket->status    = 'cancelled';
        $ticket->status_updated_at   = \Carbon\Carbon::now();
        $ticket->updated_by_id     =  Auth::id();
        $ticket->remarks = $request->remarks;
        $ticket->update();
       
        return response()->json(array('success' => 'Successfully Cancelled'));
    }
     
    public function proceedTicket($caller_id)
    {
        $caller = Caller::findOrFail($caller_id);
        $caller_types = CallerType::all();
        return view('pages.ticket.proceed-ticket')->with(['caller' => $caller, 'caller_types' => $caller_types]);
    }
    public function show($id)
    {
        
        $ticket = Ticket::find($id);
        $caller = Caller::where('id', $ticket->caller_id)->first();
        $caller_types = CallerType::all();

        return view('pages.ticket.update', ['caller' => $caller, 'caller_types' => $caller_types, 'ticket' => $ticket]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $ticket = Ticket::find($id);
        $ticket->call_type_id = $request->call_type_id;
        $ticket->call_details = $request->call_details;
        $ticket->updated_by_id     =  Auth::id();
        $ticket->update();
        
        return redirect('/ticket/view/' . $id)->with(['success' => 'Successfully Updated!', 'ticket' => $ticket, 'id' => $id]);
    }
    public function closeticket(Request $request)
    {
        // dd($request->id);
        
        $id = $request->id;
        $ticket = Ticket::find($id);
        $ticket->status = 'closed';
        $ticket->updated_by_id     =  Auth::id();
        $ticket->status_updated_at=Carbon::now();
        $ticket->update();

        $ticket_log = new TicketLog();
        $ticket_log->ticket_id = $ticket->id;
        $ticket_log->status = "closed";
        $ticket_log->assigned_by_id =Auth::id();
        $ticket_log-> remarks =$request->remarks;
       
        $ticket_log->save();
        
        return redirect('/ticket/view/' . $id)->with(['success' => 'Successfully Updated!', 'ticket' => $ticket, 'id' => $id, ]);
    }

    public function store(Request $request)
    {
        $random = Str::random(4);
        $rule = [
            'contact_no' => 'required|numeric|digits_between:10,11',
            'call_details'  => 'required|string'
        ];

        $request->validate($rule);

        $ticket = Ticket::Create(
            [
                'ticket_no' => $this->series_service->get('ticket'). '' . $random,
                'caller_id' => $request->caller_id,
                'call_type_id' => $request->call_type_id,
                'call_details' => $request->call_details,
                'ticket_category'=> 'call',
                'call_datetime' => \Carbon\Carbon::now(),
                'created_by_id' =>  Auth::id(),
                'call_status' => $request->call_status,
                'status' => 'created',
                'status_updated_at' =>  Carbon::now()
            ]
        );
        $callerid = $request->caller_id;
        $caller = Caller::find($callerid);
        $caller->contact_no        = $request->contact_no;
        $caller->email             = $request->email;
        $caller->updated_by_id     =  Auth::id();
        $caller->update();

        $ticket_log = new TicketLog();
        $ticket_log->ticket_id = $ticket->id;
        $ticket_log->status = "created";
        $ticket_log->assigned_by_id =Auth::id();
        $ticket_log-> remarks =$request->remarks;
        $ticket_log->save();
        return redirect('/ticket')->with(['success' => 'Successfully Saved!']);
    }

    public function view($id)
    {
        $ticket = Ticket::with("actions","logs")->where('id', $id)->first();
        $offices = Office::all()->sortBy('name');
    
        $assigned_office_action  = null;
        if(Auth::user()->role_id == 4) {
            $office_id = Auth::user()->staff->office_id;
            $assigned_office_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $ticket->id)->first();
        }
        return view('pages.ticket.display-ticket', [
            'ticket' => $ticket, 
            'offices' => $offices,
            'assigned_office_action' => $assigned_office_action
        ]);
    }

    public function assignedOffice(Request $request)
    {
        try {
            // $ticket_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $request->ticket_id)->first();

            $office_exist = OfficeAssignedTicket::where("office_id", $request->office_id)->where("ticket_id", $request->ticket_id)->first();
            // $ticket_exist = OfficeAssignedTicket::where("ticket_id", $request->ticket_id)->first();

            if ($office_exist) {
                return response()->json(array('success' => false, "message" => "Office already exist"));
            }

            DB::beginTransaction();
            $assigned_office = OfficeAssignedTicket::create([
                'office_id' => $request->office_id,
                'ticket_id' => $request->ticket_id,
                'assigned_by_id' => Auth::id()
            ]);
            $ticket_log = new OfficeAssignedTicketLog();
            $ticket_log->assigned_office_ticket_id = $assigned_office->id;
            $ticket_log->status = "pending";
            $ticket_log->assigned_by_id =Auth::id();
            $ticket_log-> remarks =$request->remarks;
            $ticket_log-> office_id =$request->office_id;
            $ticket_log->save();
            $assigned_office->save();

            $ticket = Ticket::where("id",  $request->ticket_id)->first();
            if ($ticket->status == 'created') {
                $ticket->status = 'assigned';
                $ticket->update();

                $ticket_log = new TicketLog();
                $ticket_log->ticket_id = $ticket->id;
                $ticket_log->status = "assigned";
                $ticket_log->assigned_by_id =Auth::id();
                $ticket_log-> remarks =$request->remarks;    
                $ticket_log->save();
            }
            
    
            DB::commit();
            return response()->json(array('success' => true));
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollback();
            return response()->json(array('success' => false));
        }
    }

    public function startWorking(Request $request)
    {
        try {
            $office_id = Auth::user()->staff->office_id;
            $ticket_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $request->ticket_id)->first();
            
            DB::beginTransaction();
            $ticket_action->status = 'working';
            $ticket_action->status_updated_by_id = Auth::id();
            $ticket_action->status_updated_at = Carbon::now();
            $ticket_action->estimated_date = $request->estimated_date;
            $ticket_action->remarks = $request->remarks;
            $ticket_action->update();
            
            $ticket_log = new OfficeAssignedTicketLog();
            $ticket_log->assigned_office_ticket_id = $ticket_action->id;
            $ticket_log->status = "working";
            $ticket_log->assigned_by_id =Auth::id();
            $ticket_log-> remarks = $request->remarks;
            $ticket_log-> office_id =$office_id;
            $ticket_log->save();

            $ticket_actions = OfficeAssignedTicket::where("ticket_id", $request->ticket_id)->get();

            $to_update_status = true;
            foreach ($ticket_actions as $action) {
                if ($action->status != 'working') {
                    $to_update_status = false;
                    break;
                }
               
            }

            if ($to_update_status) {
                $ticket = Ticket::where("id",  $request->ticket_id)->first();
                $ticket->status = 'working';
                $ticket->status_updated_at = Carbon::now();
                $ticket->update();

                $ticket_log = new TicketLog();
                $ticket_log->ticket_id = $ticket->id;
                $ticket_log->status = "working";
                $ticket_log->assigned_by_id =Auth::id();
                $ticket_log-> remarks = $request->remarks;
                $ticket_log->save();
                
            }

            

            DB::commit();
            return redirect('/ticket/view/' . $request->ticket_id)->with('success', "successfully Updated");
        } catch (Exception $ex) {
            DB::rollback();
            return redirect('/ticket/view/' . $request->ticket_id)->with('error', $ex->getMessage());
        }
    }

    public function ticketProcessed(Request $request)
    {
        try {
            $office_id = Auth::user()->staff->office_id;
            $ticket_id = $request->ticket_id;
            $ticket_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $ticket_id)->first();

            DB::beginTransaction();
            $ticket_action->status = $request->status;
            $ticket_action->status_updated_by_id = Auth::id();
            $ticket_action->actual_date = Carbon::now();
            $ticket_action->work_done = $request->work_done;
            $ticket_action->update();

            $ticket_log = new OfficeAssignedTicketLog();
            $ticket_log->assigned_office_ticket_id = $ticket_action->id;
            $ticket_log->status =  $ticket_action->status;
            $ticket_log->assigned_by_id =Auth::id();
            $ticket_log->remarks = $request->remarks;
            $ticket_log->office_id =$office_id;
            $ticket_log->save();

            $ticket_actions = OfficeAssignedTicket::where("ticket_id", $ticket_id)->get();
            $to_update_status = true;
            foreach ($ticket_actions as $action) {
                if (!($action->status == 'resolved' || $action->status == 'unresolved')) {
                    $to_update_status = false;
                    break;
                }
           
               
            }

            if ($to_update_status) {
                $ticket = Ticket::where("id", $ticket_id)->first();
                $ticket->status = 'for-closing';
                $ticket->status_updated_at = Carbon::now();
                $ticket->update();

                $ticket_log = new TicketLog();
                $ticket_log->ticket_id = $ticket->id;
                $ticket_log->status = 'for-closing';
                $ticket_log->assigned_by_id =Auth::id();
                $ticket_log-> remarks = $request->remarks;
                $ticket_log->save();
            }

          
            DB::commit();
            return redirect('/ticket/view/' . $request->ticket_id)->with('success', "successfully Updated");
        } catch (Exception $ex) {
            DB::rollback();
            return redirect('/ticket/view/' . $request->ticket_id)->with('error', $ex->getMessage());
        }
    }

    // Display Office
    public function getOffice($id)
    {
        if (request()->ajax()) {

            $assigned_office = OfficeAssignedTicket::where('ticket_id', $id)->get();

            return datatables()->of($assigned_office)
                ->addColumn('action',  '<a href="javascript:void(0)" id="removebtn" onClick="removeUserOffice({{ $id }})" data-toggle="tooltip" data-original-title="Edit" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus"></i></a>')
                //  ->addColumn('action',  '<a href="javascript:void(0)" id="removeUserOffice" data-toggle="tooltip" data-original-title="Edit" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus"></i></a>')
                ->rawColumns(['action'])
                ->addColumn('office', function (OfficeAssignedTicket $ticket) {
                    return $ticket->office->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
    }

    //display assigned
    public function assigned($id)
    {
        if (request()->ajax()) {

            $assigned_office = OfficeAssignedTicket::where('ticket_id', $id)->get();
            return datatables()->of($assigned_office)
                ->addColumn('action',  '<a href="javascript:void(0)" data-target="#mdl-timeline-office{{$id}}" data-toggle="modal"  class="btn btn-outline-danger btn-sm"><i class="fas fa-eye"></i></a>')
                ->rawColumns(['action'])
                ->addColumn('office', function (OfficeAssignedTicket $ticket) {
                    return $ticket->office->name;
                })
                ->addColumn('assignedBy', function (OfficeAssignedTicket $ticket) {
                    return $ticket->assignedBy->staff->fullname;
                })
                ->addColumn('assignedAt', function (OfficeAssignedTicket $ticket) {
                    return $ticket->created_at;
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages.ticket.display-ticket');
    }

    public function actionStatus($id)
    {
        if (request()->ajax()) {

            $assigned_office = OfficeAssignedTicket::where('ticket_id', $id)->get();
            return datatables()->of($assigned_office)
                //  ->addColumn('action',  '<a href="javascript:void(0)" id="removebtn" data-toggle="tooltip" data-original-title="Edit" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus"></i></a>')
                //  ->rawColumns(['action'])
                ->addColumn('office', function (OfficeAssignedTicket $ticket) {
                    return $ticket->office->name;
                })

                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.ticket.display-ticket');
    }

    // Remove Office
    public function removeUserOffice($id)
    {
        $office_id = $id;
        $user_office = OfficeAssignedTicket::find($office_id);
        $user_office->delete();

        return response()->json(array('success' => true));
    }

   
 
}

