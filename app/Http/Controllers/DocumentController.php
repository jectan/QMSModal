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
use Illuminate\Support\Facades\Storage;
use App\Models\Office;
use App\Models\StartworkingLog;
use App\Models\OfficeAssignedTicketLog;
use App\Models\TicketLog;
use App\Models\DocType;
use App\Models\RequestDocument;
use App\Models\ReviewDocument;
use App\Models\ApproveDocument;
use App\Models\RequestType;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{
    public function __construct(SeriesService $series_service) 
    {
        $this->series_service = $series_service;
    }

    public function store(Request $request)
    {
        $request->validate([
        'currentRevNo' => 'required|numeric|min:0',
        'docTitle' => 'required|string|max:255',
        'requestReason' => 'required|string|max:500',
        'documentFile' => 'nullable|mimes:pdf|max:2048',
        ], [
            'currentRevNo.required' => 'The Revision Number is required.',
            'docTitle.required' => 'The Document Title is required.',
            'requestReason.required' => 'The Reason for Request is required.',
            'documentFile.required' => 'The Uploaded Document is required.',
        ]);

        // Initialize file path variable
        $filePath = null;

        // Check if a file is uploaded
        if ($request->hasFile('documentFile')) {
            $file = $request->file('documentFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public'); // Store in storage/app/public/documents
        }
        else{
            $filePath = $request->requestFileOld;
        }

        $requestDocument = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestTypeID' => $request->requestTypeID,
            'docTypeID' => $request->docTypeID,
            'docRefCode' => $request->docRefCode,
            'currentRevNo' => $request->currentRevNo,
            'docTitle' => $request->docTitle,
            'requestReason' => $request->requestReason,
            'userID' => Auth::id(),
            'requestFile' => $filePath, // Save file path in DB
            'requestDate' => Carbon::now(),
            'requestStatus' => 'Requested',                  
        ]);

        return response()->json(['success'=> 'Successfully saved.', 'RequestDocument' => $requestDocument]);
    }
    
    public function storeEdit(Request $request)
    {
        $request->validate([
        'currentRevNo' => 'required|numeric|min:0',
        'docTitle' => 'required|string|max:255',
        'requestReason' => 'required|string|max:500',
        'documentFile' => 'nullable|mimes:pdf|max:2048',
        ], [
            'currentRevNo.required' => 'The Revision Number is required.',
            'docTitle.required' => 'The Document Title is required.',
            'requestReason.required' => 'The Reason for Request is required.',
            'documentFile.required' => 'The Uploaded Document is required.',
        ]);

        // Initialize file path variable
        $filePath = null;

        if(empty($request->requestID)){
            $isNew = true;
        }
        else{
            $isNew = false;
        }

        // Check if a file is uploaded
        if ($request->hasFile('documentFile')) {
            $file = $request->file('documentFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public'); // Store in storage/app/public/documents
            
            // Delete the old file if it exists
            $oldFile = $request->requestFileOld;
            if ($oldFile && Storage::exists($oldFile)) {
                Storage::delete($oldFile);
            }
        }
        else{
            $filePath = $request->requestFileOld;
        }

        $requestDocument = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestTypeID' => $request->requestTypeID,
            'docTypeID' => $request->docTypeID,
            'docRefCode' => $request->docRefCode,
            'currentRevNo' => $request->currentRevNo,
            'docTitle' => $request->docTitle,
            'requestReason' => $request->requestReason,
            'userID' => Auth::id(),
            'requestFile' => $filePath, // Save file path in DB
            'requestDate' => Carbon::now(),
            'requestStatus'  => $request->requestStatus,                  
        ]);
        
        return redirect()->back()->with('success', 'Successfully saved.');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
        'reviewComment' => 'required|string|max:255',
        ], [
            'reviewComment.required' => 'The Review Comments are required.',
        ]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => $request->reviewComment,
            'userID' => Auth::id(),
            'reviewStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Revision',
        ]);
        
        return response()->json(['success'=> 'Review Comments Saved.', 'ReviewDocument' => $reviewDocument]);
    }

    public function storeApprove(Request $request)
    {
        $request->validate([
        'approveComment' => 'required|string|max:255',
        ], [
            'approveComment.required' => 'The Comments are required.',
        ]);

        $approveDocument = ApproveDocument::updateOrCreate(['approveID' => $request->approveID],
        [
            'requestID' => $request->requestID,
            'approveComment' => $request->approveComment,
            'userID' => Auth::id(),
            'approveStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Revision (Approval)',
        ]);
        
        return response()->json(['success'=> 'Comments Saved.', 'ApproveDocument' => $approveDocument]);
    }

    public function forReview(Request $request)
    {
        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => 'Submitted for Review',
            'userID' => Auth::id(),
            'reviewStatus' => 'Active',                  
        ]);

        $requestDocument = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Review',
        ]);
        
        return response()->json(['success'=> 'Document Endorsed For Review.', 'RequestDocument' => $requestDocument]);
    }

    public function reviewed(Request $request)
    {
        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => 'Reviewed',
            'userID' => Auth::id(),
            'reviewStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Approval',
        ]);
        
        return response()->json(['success'=> 'Document Endorsed For Approval.', 'ReviewDocument' => $reviewDocument]);
    }

    public function approved(Request $request)
    {
        $approveDocument = ApproveDocument::updateOrCreate(['approveID' => $request->approveID],
        [
            'requestID' => $request->requestID,
            'approveComment' => 'Approved',
            'userID' => Auth::id(),
            'approveStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Registration',
        ]);
        
        return response()->json(['success'=> 'Document Endorsed For Registration.', 'ApproveDocument' => $approveDocument]);
    }

    public function validateRequest(Request $request)
    {
        try {
            return Validator::make($request->all(), [
                'docTitle'      => 'required|string|max:100|unique:RequestDocument,docTitle,' . $request->requestID . ',requestID',
                'docRefCode'    => 'nullable|string|max:20',
                'requestReason' => 'required|string|max:100',
            ], [
                'docTitle.required'  => 'The Document Title is required.',
                'docTitle.unique'    => 'This Document Title already exists.',
                'requestReason.required' => 'Reason for request is required.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDataRequest($status)
    {
        $requestDocuments = RequestDocument::with(['DocumentType'])
            ->select('requestID', 'docTitle', 'docTypeID', 'requestStatus', 'userID', 'docRefCode');
        if ($status !== '0') {
            $requestDocuments->where('requestStatus', $status);
        }
        else{
            $requestDocuments->where('requestStatus', '!=', 'Cancelled');
        }

        return DataTables::of($requestDocuments)
            ->addColumn('docTypeDesc', function ($row) {
                return $row->DocumentType ? $row->DocumentType->docTypeDesc : "";
            })
            ->addColumn('requestor', function ($row) {
                return $row->createdBy ? $row->createdBy->Staff->fullname : "";
            })
            ->addColumn('unitName', function ($row) {
                return $row->createdBy ? $row->createdBy->Staff->unit->unitName : "";
            })
            ->addColumn('action', function ($row) {
                $onClickFunction = "editRequest({$row->requestID})";
                $isHidden = "";

                if ((in_array($row->requestStatus, ['For Review', 'For Approval', 'For Registration']) || $row->userID !== Auth::id()) 
                    && Auth::user()->role_id !== 1) {
                    $isHidden = "hidden";
                }
                /*  elseif ($row->requestStatus === 'For Revision (Approval)') {
                    $onClickFunction = "editReview({$row->requestID})";
                } elseif ($row->requestStatus === 'For Approval') {
                    $onClickFunction = "editApproval({$row->requestID})";
                } elseif ($row->requestStatus === 'For Registration') {
                    $onClickFunction = "editRegistration({$row->requestID})";
                } else {
                    $onClickFunction = "editRequest({$row->requestID})";
                } */
        
                return '<button class="btn btn-sm btn-secondary btn" href="javascript:void(0)" onClick="displayRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">visibility</span>
                        </button>
                        <button class="btn btn-sm btn-info mx-1" href="javascript:void(0)" onClick="' . $onClickFunction . '"' . $isHidden .'>
                            <span class="material-icons" style="font-size: 20px;">edit</span>
                        </button>

                        <button class="btn btn-sm btn-danger mx-1" href="javascript:void(0)" onClick="cancelRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">delete</span>
                        </button>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function getReview($requestID)
    {
        $reviewDocuments = ReviewDocument::where('requestID', $requestID)
            ->select('reviewID', 'reviewComment', 'reviewStatus')
            ->get();
    
        return DataTables::of($reviewDocuments)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary">Edit</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
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

    public function getRequestType()
    {
        $requestType = RequestType::select('requestTypeID', 'requestTypeDesc')->get();
        return response()->json(['data' => $requestType]);
    }

    public function getDocType()
    {
        $docType = DocType::select('docTypeID', 'docTypeDesc')->get();
        return response()->json(['data' => $docType]);
    }

    public function getDocRefCode()
    {
        $docType = RequestDocument::select('docTypeID', 'docTypeDesc')->get();
        return response()->json(['data' => $docType]);
    }

    public function index()
    {
        return view('pages.documents.index');
    }
    
    public function view($requestID)
    {
        $document = RequestDocument::where('requestID', $requestID)->firstOrFail();
        $requestType = RequestType::all();
        $docType = DocType::all();
    
        return view('pages.documents.display-document', compact('document', 'requestType', 'docType'));
    }
    
    public function viewEdit($requestID)
    {
        $document = RequestDocument::where('requestID', $requestID)->firstOrFail();
        $requestType = RequestType::all();
        $docType = DocType::all();
        $isEdit = 1;
    
        return view('pages.documents.display-document', compact('document', 'requestType', 'docType', 'isEdit'));
    }

    public function update(Request $request)
    {
        $where = array('requestID' => $request->requestID);
        $RequestDocument  = RequestDocument::where($where)->first();
      
        return response()->json(array('success' => 'Successfully Updated'));
    }

    public function edit(Request $request)
    {
        $where = array('requestID' => $request->requestID);
        $RequestDocument  = RequestDocument::where($where)->first();
      
        return response()->json($RequestDocument);
    }
     
    public function cancel(Request $request)
    {
        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'Cancelled',
        ]);
        
        return response()->json(array('success' => 'Successfully Cancelled Request'));
    }



//TO REMOVE

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