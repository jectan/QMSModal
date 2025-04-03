<?php

namespace App\Http\Controllers;
use App\Models\RequestDocument;
use Illuminate\Support\Facades\Auth;

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
        $documents['qp'] = RequestDocument::where('docTypeID', '1')->where('requestStatus', 'Registered')->count();
        $documents['qm'] = RequestDocument::where('docTypeID', '6')->where('requestStatus', 'Registered')->count();
        $documents['pm'] = RequestDocument::where('docTypeID', '5')->where('requestStatus', 'Registered')->count();
        $documents['ftg'] = RequestDocument::where('docTypeID', [2,3])->where('requestStatus', 'Registered')->count();
        echo json_encode($documents);
        exit;
    }
    
    public function getDataRequest($dataTable)
    {
        $requestDocuments = RequestDocument::with(['DocumentType','createdBy.staff.unit.getDivision'])
            ->select('requestID', 'docTitle', 'docTypeID', 'currentRevNo', 'requestStatus', 'userID', 'docRefCode')
            ->where('requestStatus', 'Registered');
    
        if ($dataTable == '1') {
            $requestDocuments->where('docTypeID', 1);
        }
        elseif($dataTable == '2'){
            $requestDocuments->where('docTypeID', 1)
                ->whereHas('createdBy.staff.unit.getDivision', function ($query){
                    $query->where('divID', '3');
                });
        }
        elseif($dataTable == '3'){
            $requestDocuments->where('docTypeID', 1)
                ->whereHas('createdBy.staff.unit.getDivision', function ($query){
                    $query->where('divID', '2');
                });
        }
        elseif($dataTable == '4'){
            $requestDocuments->where('docTypeID', 1)
                ->whereHas('createdBy.staff.unit.getDivision', function ($query){
                    $query->where('divID', '1');
                });
        }
        else{
            //keeping this to check
        }
    
        return DataTables::of($requestDocuments)
            ->addColumn('docRefCode', function ($row) {
                return $row->docRefCode ? $row->docRefCode : "";
            })
            ->addColumn('docTitle', function ($row) {
                return $row->docTitle ? $row->docTitle : "";
            })
            ->addColumn('currentRevNo', function ($row) {
                return $row->currentRevNo ? $row->currentRevNo : "";
            })
            ->make(true);
    }    
}
