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
use App\Models\RequestDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class DocumentController extends Controller

{
    public function __construct(SeriesService $series_service) 
    {
        $this->series_service = $series_service;
    }

    public function documentTally(){
        
        $user = Auth::user();

        $documents['register'] = RequestDocument::where('requestStatus', 'Registered')->count();
        $documents['review'] = RequestDocument::where('requestStatus', 'For Review')->count();
        $documents['approval'] = RequestDocument::where('requestStatus', 'For Approval')->count();
        $documents['archive'] = RequestDocument::where('requestStatus', 'Archive')->count();

/*         $documents['total_ticket']  = Ticket::whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();
          
        $documents['assigned'] = Ticket::where('status', 'assigned')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $documents['working'] = Ticket::where('status', 'working')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $documents['for-closing'] = Ticket::where('status', 'for-closing')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $documents['closed'] =  Ticket::where('status', 'closed')->whereHas('actions', function ($query) use($user){
            $query->where('office_id', $user->staff->office_id);})->count();

        $documents['feedback'] = Ticket::where('status', 'closed')->whereNotNull('date_rated')->whereHas('actions', function ($query) use($user){ $query->where('office_id', $user->staff->office_id);})->count(); */

     
        echo json_encode($documents);
        exit;
    }

    public function create()
    {
        
    }

    public function index()
    {
        $user = Auth::user();
    
        if ($user->role_id == 4){
            
            $requestDocumments = RequestDocument::with(['createdBy', 'documentType', 'requestType'])
            ->where('requestStatus', '!=', 'Deleted', function ($query) use($user){
                $query->where('unitID', $user->staff->unitID);})
            ->get();

            /* $created = Ticket::where('status', 'created')->whereHas('actions', function ($query) use($user){
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
                $query->where('office_id', $user->staff->office_id);})->get(); */

        }else{ 
            $requestDocuments = RequestDocument::where('requestStatus', '!=', 'Deleted')->get();
            $created = Ticket::where('status', 'created')->get();
            $assigned = Ticket::where('status', 'assigned')->get();
            $working = Ticket::where('status', 'working')->get();
            $for_closing = Ticket::where('status', 'for-closing')->get();
         
            $with_feedback = Ticket::where('status', 'closed')->whereNotNull('date_rated')->get();
            $cancelled = Ticket::where('status', 'cancelled')->get();
            $closed = Ticket::where('status', 'closed')->whereNull('date_rated')->get();
    }
        return view('pages.documents.index', ['created' => $created, 'requestDocuments' => $requestDocuments, 'assigned' => $assigned, 'workings' => $working, 'for_closings' => $for_closing, 'closed' => $closed, 'with_feedbacks' => $with_feedback, 'cancelled' => $cancelled, 'closed' => $closed]);
    }
     
    public function cancel(Request $request)
    {
        $document = Ticket::find($request->id);
        $document->status    = 'cancelled';
        $document->status_updated_at   = \Carbon\Carbon::now();
        $document->updated_by_id     =  Auth::id();
        $document->remarks = $request->remarks;
        $document->update();
       
        return response()->json(array('success' => 'Successfully Cancelled'));
    }
     
    public function proceedTicket($caller_id)
    {
        $caller = Caller::findOrFail($caller_id);
        $caller_types = CallerType::all();
        return view('pages.documents.proceed-ticket')->with(['caller' => $caller, 'caller_types' => $caller_types]);
    }
    public function show($id)
    {
        
        $document = Ticket::find($id);
        $caller = Caller::where('id', $document->caller_id)->first();
        $caller_types = CallerType::all();

        return view('pages.documents.update', ['caller' => $caller, 'caller_types' => $caller_types, 'ticket' => $document]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $document = Ticket::find($id);
        $document->call_type_id = $request->call_type_id;
        $document->call_details = $request->call_details;
        $document->updated_by_id     =  Auth::id();
        $document->update();
        
        return redirect('/documents/view/' . $id)->with(['success' => 'Successfully Updated!', 'ticket' => $document, 'id' => $id]);
    }
    public function closeticket(Request $request)
    {
        // dd($request->id);
        
        $id = $request->id;
        $document = Ticket::find($id);
        $document->status = 'closed';
        $document->updated_by_id     =  Auth::id();
        $document->status_updated_at=Carbon::now();
        $document->update();

        $document_log = new TicketLog();
        $document_log->ticket_id = $document->id;
        $document_log->status = "closed";
        $document_log->assigned_by_id =Auth::id();
        $document_log-> remarks =$request->remarks;
       
        $document_log->save();
        
        return redirect('/documents/view/' . $id)->with(['success' => 'Successfully Updated!', 'ticket' => $document, 'id' => $id, ]);
    }

    public function store(Request $request)
    {
        $random = Str::random(4);
        $rule = [
            'contact_no' => 'required|numeric|digits_between:10,11',
            'call_details'  => 'required|string'
        ];

        $request->validate($rule);

        $document = Ticket::Create(
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

        $document_log = new TicketLog();
        $document_log->ticket_id = $document->id;
        $document_log->status = "created";
        $document_log->assigned_by_id =Auth::id();
        $document_log-> remarks =$request->remarks;
        $document_log->save();
        return redirect('/documents')->with(['success' => 'Successfully Saved!']);
    }

    public function view($id)
    {
        $document = Ticket::with("actions","logs")->where('id', $id)->first();
        $offices = Office::all()->sortBy('name');
    
        $assigned_office_action  = null;
        if(Auth::user()->role_id == 4) {
            $office_id = Auth::user()->staff->office_id;
            $assigned_office_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $document->id)->first();
        }
        return view('pages.documents.display-ticket', [
            'ticket' => $document, 
            'offices' => $offices,
            'assigned_office_action' => $assigned_office_action
        ]);
    }

    public function assignedOffice(Request $request)
    {
        try {
            // $document_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $request->ticket_id)->first();

            $office_exist = OfficeAssignedTicket::where("office_id", $request->office_id)->where("ticket_id", $request->ticket_id)->first();
            // $document_exist = OfficeAssignedTicket::where("ticket_id", $request->ticket_id)->first();

            if ($office_exist) {
                return response()->json(array('success' => false, "message" => "Office already exist"));
            }

            DB::beginTransaction();
            $assigned_office = OfficeAssignedTicket::create([
                'office_id' => $request->office_id,
                'ticket_id' => $request->ticket_id,
                'assigned_by_id' => Auth::id()
            ]);
            $document_log = new OfficeAssignedTicketLog();
            $document_log->assigned_office_ticket_id = $assigned_office->id;
            $document_log->status = "pending";
            $document_log->assigned_by_id =Auth::id();
            $document_log-> remarks =$request->remarks;
            $document_log-> office_id =$request->office_id;
            $document_log->save();
            $assigned_office->save();

            $document = Ticket::where("id",  $request->ticket_id)->first();
            if ($document->status == 'created') {
                $document->status = 'assigned';
                $document->update();

                $document_log = new TicketLog();
                $document_log->ticket_id = $document->id;
                $document_log->status = "assigned";
                $document_log->assigned_by_id =Auth::id();
                $document_log-> remarks =$request->remarks;    
                $document_log->save();
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
            $document_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $request->ticket_id)->first();
            
            DB::beginTransaction();
            $document_action->status = 'working';
            $document_action->status_updated_by_id = Auth::id();
            $document_action->status_updated_at = Carbon::now();
            $document_action->estimated_date = $request->estimated_date;
            $document_action->remarks = $request->remarks;
            $document_action->update();
            
            $document_log = new OfficeAssignedTicketLog();
            $document_log->assigned_office_ticket_id = $document_action->id;
            $document_log->status = "working";
            $document_log->assigned_by_id =Auth::id();
            $document_log-> remarks = $request->remarks;
            $document_log-> office_id =$office_id;
            $document_log->save();

            $document_actions = OfficeAssignedTicket::where("ticket_id", $request->ticket_id)->get();

            $to_update_status = true;
            foreach ($document_actions as $action) {
                if ($action->status != 'working') {
                    $to_update_status = false;
                    break;
                }
               
            }

            if ($to_update_status) {
                $document = Ticket::where("id",  $request->ticket_id)->first();
                $document->status = 'working';
                $document->status_updated_at = Carbon::now();
                $document->update();

                $document_log = new TicketLog();
                $document_log->ticket_id = $document->id;
                $document_log->status = "working";
                $document_log->assigned_by_id =Auth::id();
                $document_log-> remarks = $request->remarks;
                $document_log->save();
                
            }

            

            DB::commit();
            return redirect('/documents/view/' . $request->ticket_id)->with('success', "successfully Updated");
        } catch (Exception $ex) {
            DB::rollback();
            return redirect('/documents/view/' . $request->ticket_id)->with('error', $ex->getMessage());
        }
    }

    public function ticketProcessed(Request $request)
    {
        try {
            $office_id = Auth::user()->staff->office_id;
            $document_id = $request->ticket_id;
            $document_action = OfficeAssignedTicket::where("office_id", $office_id)->where("ticket_id", $document_id)->first();

            DB::beginTransaction();
            $document_action->status = $request->status;
            $document_action->status_updated_by_id = Auth::id();
            $document_action->actual_date = Carbon::now();
            $document_action->work_done = $request->work_done;
            $document_action->update();

            $document_log = new OfficeAssignedTicketLog();
            $document_log->assigned_office_ticket_id = $document_action->id;
            $document_log->status =  $document_action->status;
            $document_log->assigned_by_id =Auth::id();
            $document_log->remarks = $request->remarks;
            $document_log->office_id =$office_id;
            $document_log->save();

            $document_actions = OfficeAssignedTicket::where("ticket_id", $document_id)->get();
            $to_update_status = true;
            foreach ($document_actions as $action) {
                if (!($action->status == 'resolved' || $action->status == 'unresolved')) {
                    $to_update_status = false;
                    break;
                }
           
               
            }

            if ($to_update_status) {
                $document = Ticket::where("id", $document_id)->first();
                $document->status = 'for-closing';
                $document->status_updated_at = Carbon::now();
                $document->update();

                $document_log = new TicketLog();
                $document_log->ticket_id = $document->id;
                $document_log->status = 'for-closing';
                $document_log->assigned_by_id =Auth::id();
                $document_log-> remarks = $request->remarks;
                $document_log->save();
            }

          
            DB::commit();
            return redirect('/documents/view/' . $request->ticket_id)->with('success', "successfully Updated");
        } catch (Exception $ex) {
            DB::rollback();
            return redirect('/documents/view/' . $request->ticket_id)->with('error', $ex->getMessage());
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
                ->addColumn('office', function (OfficeAssignedTicket $document) {
                    return $document->office->name;
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
                ->addColumn('office', function (OfficeAssignedTicket $document) {
                    return $document->office->name;
                })
                ->addColumn('assignedBy', function (OfficeAssignedTicket $document) {
                    return $document->assignedBy->staff->fullname;
                })
                ->addColumn('assignedAt', function (OfficeAssignedTicket $document) {
                    return $document->created_at;
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('pages.documents.display-ticket');
    }

    public function actionStatus($id)
    {
        if (request()->ajax()) {

            $assigned_office = OfficeAssignedTicket::where('ticket_id', $id)->get();
            return datatables()->of($assigned_office)
                //  ->addColumn('action',  '<a href="javascript:void(0)" id="removebtn" data-toggle="tooltip" data-original-title="Edit" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus"></i></a>')
                //  ->rawColumns(['action'])
                ->addColumn('office', function (OfficeAssignedTicket $document) {
                    return $document->office->name;
                })

                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.documents.display-ticket');
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

