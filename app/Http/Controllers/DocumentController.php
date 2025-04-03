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
use App\Models\RegisteredDoc;
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

        if($request->requestStatus == 'For Review'){
            
            $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
            [
                'requestID' => $request->requestID,
                'reviewComment' => 'Submitted for Review by ' . Auth::user()->staff->fullname,
                'userID' => Auth::id(),
                'reviewStatus' => 'Active',                  
            ]);
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
        'reviewComments' => 'required|string|max:255',
        ], [
            'reviewComments.required' => 'The Review Comments are required.',
        ]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => $request->reviewComments . ' by: ' . Auth::user()->staff->fullname,
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
        'reviewComment2' => 'required|string|max:255',
        ], [
            'reviewComment2.required' => 'The Comments are requireds.',
        ]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->approveID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => $request->reviewComment2 . ' by: ' . Auth::user()->staff->fullname,
            'userID' => Auth::id(),
            'reviewStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Revision (Approval)',
        ]);
        
        return response()->json(['success'=> 'Comments Saved.', 'ReviewDocument' => $reviewDocument]);
    }

    public function forReview(Request $request)
    {
        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => 'Submitted for Review by ' . Auth::user()->staff->fullname,
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
        try {
            DB::beginTransaction(); // Start transaction
    
            // Create or update Review Document
            $reviewDocument = ReviewDocument::updateOrCreate(
                ['reviewID' => $request->reviewID],
                [
                    'requestID' => $request->requestID,
                    'reviewComment' => 'Reviewed by ' . Auth::user()->staff->fullname,
                    'userID' => Auth::id(),
                    'reviewStatus' => 'Active',                  
                ]
            );
    
            // Update Request Document
            $requestDocument = RequestDocument::updateOrCreate(
                ['requestID' => $request->requestID],
                [
                    'requestStatus' => 'For Approval',
                ]
            );
    
            DB::commit(); // Commit transaction if all queries succeed
    
            return response()->json([
                'success' => 'Document Endorsed For Approval.',
                'ReviewDocument' => $reviewDocument
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if any query fails
    
            return response()->json([
                'error' => 'Failed to process the request. Please try again.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function approved(Request $request)
    {
        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->approveID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => 'Approved by ' . Auth::user()->staff->fullname,
            'userID' => Auth::id(),
            'reviewStatus' => 'Active',                  
        ]);

        $requestDocumment = RequestDocument::updateOrCreate(['requestID' => $request->requestID],
        [
            'requestStatus' => 'For Registration',
        ]);
        
        return response()->json(['success'=> 'Document Endorsed For Registration.', 'ReviewDocument' => $reviewDocument]);
    }

    public function register(Request $request)
    {
        $request->validate([
        'currentRevNo2' => 'required|numeric|min:0',
        'docRefCode2' => 'required|string|max:255',
        'documentFile2' => 'nullable|mimes:pdf|max:2048',
        ], [
            'currentRevNo2.required' => 'The Revision Number is required.',
            'docRefCode2.required' => 'The Document Reference Code is required.',
            'documentFile2.required' => 'The Uploaded Document is required.',
        ]);

        $file = $request->file('documentFile2');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public'); // Store in storage/app/public/documents

        DB::beginTransaction();

        try {
            $requestDocument = RequestDocument::updateOrCreate(['requestID' => $request->requestID2],
            [
                'docRefCode' => $request->docRefCode2,
                'currentRevNo' => $request->currentRevNo2,
                'requestStatus' => 'Registered',
            ]);

            $registerDocument = RegisteredDoc::updateOrCreate(['regDocID' => $request->regDocID],
            [
                'requestID' => $request->requestID2,
                'docFile' => $filePath, // Save file path in DB
                'effectivityDate' => Carbon::now(), 
            ]);

            $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
            [
                'requestID' => $request->requestID2,
                'reviewComment' => 'Registered by ' . Auth::user()->staff->fullname,
                'userID' => Auth::id(),
                'reviewStatus' => 'Active',                  
            ]);

            DB::commit();
    
            return response()->json(['success' => 'Successfully saved.', 'RegisterDocument' => $registerDocument]);
        } catch (\Exception $e) {
            
            DB::rollback();
            return response()->json(['error' => 'Failed to save. Error: ' . $e->getMessage()], 500);
        }
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

        if (!in_array(Auth::user()->role_id, [1, 2, 3, 4])) {
            $requestDocuments->where('requestStatus', '!=', 'Registered');
            $requestDocuments->where('userID', Auth::id());
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

                if ((in_array($row->requestStatus, ['For Review', 'For Approval', 'For Registration', 'Registered']) || $row->userID !== Auth::id()) 
                    && Auth::user()->role_id !== 1) {
                    $isHidden = "hidden";
                }
        
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
            ->select('reviewID', 'reviewComment', 'reviewStatus', 'reviewDate')
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

    public function checkDocRefCode(Request $request)
    {
        $document = RequestDocument::select('requestID', 'docRefCode', 'currentRevNo')
            ->where('docRefCode', $request->docRefCode)
            ->where('requestStatus', 'Registered')
            ->first();

        if ($document) {
            return response()->json([
                'exists' => true,
                'currentRevNo' => $document->currentRevNo, // ✅ Return the specific revision number
            ]);
        } else {
            return response()->json([
                'exists' => false,
                'currentRevNo' => null, // ✅ Ensure this key always exists
            ]);
        }
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