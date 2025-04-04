<?php

namespace App\Http\Controllers;
use App\Models\RequestDocument;
use App\Models\RegisteredDoc;
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
        /* $requestDocuments = RequestDocument::with(['DocumentType','createdBy.staff.unit.getDivision'])
            ->select('requestID', 'docTitle', 'docTypeID', 'currentRevNo', 'requestStatus', 'userID', 'docRefCode')
            ->where('requestStatus', 'Registered');
        if ($dataTable == '1') {
            $requestDocuments->where('docTypeID', 1);
        }
        elseif($dataTable == '6'){
            $requestDocuments->where('docTypeID', 6);
        }
        elseif($dataTable == '5'){
            $requestDocuments->where('docTypeID', 5);
        }
        elseif($dataTable == '4'){
            $requestDocuments->where('docTypeID', 1);
        }
        else{
            $requestDocuments->whereIn('docTypeID', [2,3]);
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
            ->addColumn('effectivityDate', function ($row) {
                return $row->effectivityDate ? $row->effectivityDate : "";
            })
            ->addColumn('docRefCode', function ($row) {
                return $row->docRefCode ? $row->docRefCode : "";
            })
            ->addColumn('docTitle', function ($row) {
                return $row->docTitle ? $row->docTitle : "";
            })
            ->addColumn('currentRevNo', function ($row) {
                return $row->currentRevNo ? $row->currentRevNo : "";
            })
            ->make(true); */

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
    } elseif ($dataTable == '6') {
        $requestDocuments->whereHas('document', function ($query) {
            $query->where('docTypeID', 6);
        });
    } elseif ($dataTable == '5') {
        $requestDocuments->whereHas('document', function ($query) {
            $query->where('docTypeID', 5);
        });
    } elseif ($dataTable == '4') {
        $requestDocuments->whereHas('document', function ($query) {
            $query->where('docTypeID', 1);
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
}
