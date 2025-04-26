<?php

namespace App\Http\Controllers;
use App\Models\RequestDocument;
use App\Models\RegisteredDoc;
use App\Models\RequestType;
use App\Models\DocType;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class MasterlistController extends Controller
{
    // Display the masterlist view
    public function index()
    {
        return view('pages.masterlist.index');
    }
    
    public function view($requestID)
    {
        $document = RequestDocument::where('requestID', $requestID)->firstOrFail();
        $requestType = RequestType::all();
        $docType = DocType::all();
        $isEdit = 0;
    
        return view('pages.documents.display-registereddocument', compact('document', 'requestType', 'docType', 'isEdit'));
    }

    public function documentTally(){
        
        $user = Auth::user();
        $documents['qp'] = RequestDocument::where('docTypeID', '6')->where('requestStatus', 'Registered')->count();
        $documents['qm'] = RequestDocument::where('docTypeID', '1')->where('requestStatus', 'Registered')->count();
        $documents['pm'] = RequestDocument::where('docTypeID', '5')->where('requestStatus', 'Registered')->count();
        $documents['ftg'] = RequestDocument::whereIn('docTypeID', [2,3])->where('requestStatus', 'Registered')->count();
        echo json_encode($documents);
        exit;
    }
    
    public function getDataRequest($dataTable)
    {
        $requestDocuments = RegisteredDoc::with(['document.DocumentType', 'document.createdBy.staff.unit.getDivision'])
        ->select('RegisteredDoc.*') // Select all columns from RegisteredDoc
        ->whereHas('document', function ($query) {
            $query->where('requestStatus', 'Registered'); // Ensure the linked RequestDocument has "Registered" status
        });

        // Apply filters based on $dataTable
        if ($dataTable == '1') {
            $requestDocuments->whereHas('document', function ($query) {
                $query->where('docTypeID', 1);
            });
        } elseif ($dataTable == '4') {
            $requestDocuments->whereHas('document', function ($query) {
                $query->where('docTypeID', 4);
            });
        } elseif ($dataTable == '5') {
            $requestDocuments->whereHas('document', function ($query) {
                $query->where('docTypeID', 5);
            });
        } elseif ($dataTable == '6') {
            $requestDocuments->whereHas('document', function ($query) {
                $query->where('docTypeID', 6);
            });
        } elseif ($dataTable == '7') {
            $requestDocuments = RegisteredDoc::with(['document.DocumentType', 'document.createdBy.staff.unit.getDivision'])
                ->select('RegisteredDoc.*') // Select all columns from RegisteredDoc
                ->whereHas('document', function ($query) {
                    $query->where('requestStatus', 'Obsolete'); // Ensure the linked RequestDocument has "Obsolete" status
                });
        } else {
            $requestDocuments->whereHas('document', function ($query) {
                $query->whereIn('docTypeID', [2, 3]);
            });
        }

        // Return data for DataTables
        return DataTables::of($requestDocuments)
            ->addColumn('docTypeDesc', function ($row) {
                return $row->document && $row->document->DocumentType ? $row->document->DocumentType->docTypeDesc : "";
            })
            ->addColumn('requestor', function ($row) {
                return $row->document && $row->document->createdBy ? $row->document->createdBy->staff->fullname : "";
            })
            ->addColumn('unitName', function ($row) {
                return $row->document && $row->document->createdBy ? $row->document->createdBy->staff->unit->unitName : "";
            })
            ->addColumn('effectivityDate', function ($row) {
                return $row->effectivityDate ? $row->effectivityDate : "";
            })
            ->addColumn('docRefCode', function ($row) {
                return $row->document ? $row->document->docRefCode : "";
            })
            ->addColumn('docTitle', function ($row) {
                return $row->document ? $row->document->docTitle : "";
            })
            ->addColumn('currentRevNo', function ($row) {
                return $row->document ? $row->document->currentRevNo : "";
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-secondary btn" href="javascript:void(0)" onClick="displayRequest(' . $row->requestID . ')">
                            <span class="material-icons" style="font-size: 20px;">visibility</span>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getRevisionHistory($docRefCode)
    {
        $revisionHistory = RegisteredDoc::with(['document.DocumentType', 'document.createdBy.staff.unit.getDivision'])
            ->select('RegisteredDoc.*') // Select all columns from RegisteredDoc
            ->whereHas('document', function ($query) use ($docRefCode) {
                $query->where('docRefCode', $docRefCode) // First, filter by docRefCode
                      ->where(function ($subQuery) {    // Group the OR condition
                          $subQuery->where('requestStatus', 'Obsolete')
                                   ->orWhere('requestStatus', 'Registered');
                      });
            });

        // Return data for DataTables
        return DataTables::of($revisionHistory)
            ->addColumn('docRefCode', function ($row) {
                return $row->document ? $row->document->docRefCode : "";
            })
            ->addColumn('docTitle', function ($row) {
                return $row->document ? $row->document->docTitle : "";
            })
            ->addColumn('currentRevNo', function ($row) {
                return $row->document ? $row->document->currentRevNo : "";
            })
            ->addColumn('effectivityDate', function ($row) {
                return $row->effectivityDate ? $row->effectivityDate : "";
            })
            ->addColumn('requestReason', function ($row) {
                return $row->document ? $row->document->requestReason : "";
            })
            ->make(true);
    }
}