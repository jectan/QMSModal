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
use Illuminate\Validation\Rule;
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
        'docTitle' => [
            'required',
            'string',
            'max:255',
            Rule::unique('RequestDocument')->where(function ($query) {
                return $query->where('requestStatus', 'Requested');
            }),
        ],
        'requestReason' => 'required|string|max:500',
        'documentFile' => 'nullable|mimes:pdf',
        ], [
            'currentRevNo.required' => 'The Revision Number is required.',
            'docTitle.required' => 'The Document Title is required.',
            'docTitle.unique' => 'A similar document request already exist.',
            'requestReason.required' => 'The Reason for Request is required.',
            'requestReason.required' => 'The Reason for Request is required.',
            'documentFile.required' => 'The Uploaded Document is required.',
        ]);

        // Initialize file path variable
        $filePath = null;
        $status = 'Requested';

        // Check if a file is uploaded
        if ($request->hasFile('documentFile')) {
            $file = $request->file('documentFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public'); // Store in storage/app/public/documents
        }
        else{
            $filePath = $request->requestFileOld;
        }

        if (Auth::user() && Auth::user()->role_id == 4){
            $status = 'For Approval';
        }

        try {
            DB::beginTransaction(); // Start transaction
    
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
                'requestStatus' => $status,
            ]);

            if (Auth::user() && Auth::user()->role_id == 4){

                $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
                [
                    'requestID' => $requestDocument->requestID,
                    'reviewComment' => 'Submitted for Approval by ' . Auth::user()->staff->fullname,
                    'userID' => Auth::id(),
                    'reviewStatus' => 'Active',                  
                ]);
            }
    
            DB::commit(); // Commit transaction if all queries succeed
    
            return response()->json(['success'=> 'Successfully saved.', 'RequestDocument' => $requestDocument]);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if any query fails
    
            return response()->json([
                'error' => 'Failed to process the request. Please try again.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function storeEdit(Request $request)
    {
        $request->validate([
        'currentRevNoEdit' => 'required|numeric|min:0',
        'docTitleEdit' => [
            'required',
            'string',
            'max:255',
            Rule::unique('RequestDocument', 'docTitle')->where(function ($query) {
                return $query->where('requestStatus', 'Requested');
            }),
        ],
        'requestReasonEdit' => 'required|string|max:500',
        ], [
            'currentRevNoEdit.required' => 'The Revision Number is required.',
            'docTitleEdit.required' => 'The Document Title is required.',
            'docTitleEdit.unique' => 'A similar Document Request already exist.',
            'requestReasonEdit.required' => 'The Reason for Request is required.',
        ]);

        // Initialize file path variable
        $filePath = null;
        // Retrieve the old file path
        $oldFilePath = $request->requestFileOldEdit;
        $status = 'Requested';


        if (Auth::user() && Auth::user()->role_id == 4){
            $status = 'For Approval';
        }
        
        // Check if a file is uploaded
        if ($request->hasFile('documentFileEdit')) {
            $file = $request->file('documentFileEdit');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public'); // Store in storage/app/public/documents

            // Delete the old file if it exists and is different from the new one
            if ($oldFilePath && $oldFilePath != $filePath) {
                $oldFilePathFull = public_path('storage/' . $oldFilePath);
                if (file_exists($oldFilePathFull)) {
                    unlink($oldFilePathFull);
                }
            }
        }
        else{
            $filePath = $request->requestFileOldEdit;
        }

        $requestDocument = RequestDocument::updateOrCreate(['requestID' => $request->requestIDEdit],
        [
            'requestTypeID' => $request->requestTypeIDEdit,
            'docTypeID' => $request->docTypeIDEdit,
            'docRefCode' => $request->docRefCodeEdit,
            'currentRevNo' => $request->currentRevNoEdit,
            'docTitle' => $request->docTitleEdit,
            'requestReason' => $request->requestReasonEdit,
            'userID' => Auth::id(),
            'requestFile' => $filePath, // Save file path in DB
            'requestDate' => Carbon::now(),
            'requestStatus' => $status,                  
        ]);

        return response()->json(['success'=> 'Successfully saved.', 'RequestDocument' => $requestDocument]);
    }

    public function storeReview(Request $request)
    {
        $request->validate([
        'reviewComments' => 'required|string',
        ], [
            'reviewComments.required' => 'The Review Comments are required.',
        ]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => '"' . $request->reviewComments . '" by: ' . Auth::user()->staff->fullname,
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
        'reviewComment2' => 'required|string',
        ], [
            'reviewComment2.required' => 'The Comments are requireds.',
        ]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->approveID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => '"' . $request->reviewComment2 . '" by: ' . Auth::user()->staff->fullname,
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
        try {
            DB::beginTransaction(); // Start transaction
    
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
    
            DB::commit(); // Commit transaction if all queries succeed
    
            return response()->json(['success'=> 'Document Endorsed For Review.', 'RequestDocument' => $requestDocument]);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if any query fails
    
            return response()->json([
                'error' => 'Failed to process the request. Please try again.',
                'message' => $e->getMessage()
            ], 500);
        }
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
        'documentFile2' => 'nullable|mimes:pdf',
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
            $updatedRows = RequestDocument::where('docRefCode', $request->docRefCode2)
                ->where('requestStatus', 'Registered')
                ->update([
                    'requestStatus' => 'Obsolete',
                ]);

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
            $requestDocuments->where('requestStatus', '!=', 'Obsolete');
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
                $isEditHidden = "";
                $isDeleteHidden = "";
                $isApproveHidden = "Hidden";
                $isReviewHidden = "Hidden";
                $isRegisterHidden = "Hidden";

                if ((in_array($row->requestStatus, ['For Review', 'For Approval', 'For Registration', 'Registered', 'Obsolete', 'Cancelled']) || $row->userID !== Auth::id()) 
                    && Auth::user()->role_id !== 1) {
                    $isEditHidden = "hidden";
                }
                
                if ((in_array($row->requestStatus, ['For Registration', 'Registered', 'Obsolete']) || $row->userID !== Auth::id()) 
                    && Auth::user()->role_id !== 1) {
                    $isDeleteHidden = "hidden";
                }

                if ($row->requestStatus =='For Review' && (in_array(Auth::user()->role_id, [1,4])))
                {
                    $isReviewHidden = "";
                }
                elseif ($row->requestStatus =='For Approval' && (in_array(Auth::user()->role_id, [1,3])))
                {
                    $isApproveHidden = "";
                }
                elseif ($row->requestStatus =='For Registration' && (in_array(Auth::user()->role_id, [1,2])))
                {
                    $isRegisterHidden = "";
                }
                
                return '<button class="btn btn-sm btn-secondary btn" href="javascript:void(0)" onClick="displayRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">visibility</span>
                        </button>
                        
                        <button class="btn btn-sm btn-success mx-1" href="javascript:void(0)" onClick="reviewRequest(' . $row->requestID . ')"' . $isReviewHidden .'>
                            <span class="material-icons" style="font-size: 20px;">check_box</span>
                        </button>
                        
                        <button class="btn btn-sm btn-success mx-1" href="javascript:void(0)" onClick="approveRequest(' . $row->requestID . ')"' . $isApproveHidden .'>
                            <span class="material-icons" style="font-size: 20px;">check_box</span>
                        </button>
                        
                        <button class="btn btn-sm btn-success mx-1" href="javascript:void(0)" onClick="registerRequest(' . $row->requestID . ')"' . $isRegisterHidden .'>
                            <span class="material-icons" style="font-size: 20px;">check_box</span>
                        </button>
                        
                        <button class="btn btn-sm btn-info mx-1" href="javascript:void(0)" onClick="' . $onClickFunction . '"' . $isEditHidden .'>
                            <span class="material-icons" style="font-size: 20px;">edit</span>
                        </button>

                        <button class="btn btn-sm btn-danger mx-1" href="javascript:void(0)" onClick="cancelRequest(' . $row->requestID . ')"' . $isDeleteHidden .'>
                            <span class="material-icons" style="font-size: 20px;">delete</span>
                        </button>
                        ';
        
                /* return '<button class="btn btn-sm btn-secondary btn" href="javascript:void(0)" onClick="displayRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">visibility</span>
                        </button>

                        <button class="btn btn-sm btn-info mx-1" href="javascript:void(0)" onClick="' . $onClickFunction . '"' . $isEditHidden .'>
                            <span class="material-icons" style="font-size: 20px;">edit</span>
                        </button>

                        <!-- <button class="btn btn-sm btn-success mx-1" href="javascript:void(0)" onClick="registerDocument(' . $onClickFunction . ')"' . $isDeleteHidden .'>
                            <span class="material-icons" style="font-size: 20px;">check_box</span>
                        </button> -->

                        <button class="btn btn-sm btn-danger mx-1" href="javascript:void(0)" onClick="cancelRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">delete</span>
                        </button>
                        '; */
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function getReview($requestID)
    {
        // Fetch review history for the given request ID
        $reviews = ReviewDocument::where('requestID', $requestID)
            ->orderBy('reviewDate', 'asc') // Order by date
            ->get(['reviewDate', 'reviewComment']);
    
        // Return response as JSON
        return response()->json($reviews);
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
        $document = RequestDocument::select('requestID', 'docRefCode', 'currentRevNo', 'docTypeID')
            ->where('docRefCode', $request->docRefCode)
            ->where('requestStatus', 'Registered')
            ->first();

        if ($document) {
            return response()->json([
                'exists' => true,
                'currentRevNo' => $document->currentRevNo,
                'docTypeID' => $document->docTypeID, // ✅ Return the specific revision number
            ]);
        } else {
            return response()->json([
                'exists' => false,
                'currentRevNo' => null, // ✅ Ensure this key always exists
            ]);
        }
    }

    public function checkDuplicateRequest(Request $request)
    {
        $document = RequestDocument::select('requestID', 'docRefCode', 'requestStatus')
            ->where('docRefCode', $request->docRefCode)
            ->whereNotIn('requestStatus', ['Registered', 'Obsolete', 'Cancelled'])
            ->first();

        if ($document) {
            return response()->json([
                'exists' => true,
                'requestStatus' => $document->requestStatus,
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
        $isEdit = 0;
        $isRegister = 0;
    
        return view('pages.documents.display-document', compact('document', 'requestType', 'docType', 'isEdit', 'isRegister'));
    }
    
    public function viewEdit($requestID)
    {
        $document = RequestDocument::where('requestID', $requestID)->firstOrFail();
        $requestType = RequestType::all();
        $docType = DocType::all();
        $isEdit = 1;
        $isRegister = 0;
    
        return view('pages.documents.display-document', compact('document', 'requestType', 'docType', 'isEdit', 'isRegister'));
    }
    
    public function viewRegister($requestID)
    {
        $document = RequestDocument::where('requestID', $requestID)->firstOrFail();
        $requestType = RequestType::all();
        $docType = DocType::all();
        $isEdit = 0;
        $isRegister = 1;
    
        return view('pages.documents.display-document', compact('document', 'requestType', 'docType', 'isEdit', 'isRegister'));
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
}