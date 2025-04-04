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
use Yajra\DataTables\Facades\DataTables;

class DashboardV2Controller extends Controller
{

    public function documentTally(){
        
        $user = Auth::user();

        $documents['register'] = RequestDocument::where('requestStatus', 'Registered')->count();
        $documents['review'] = RequestDocument::where('requestStatus', 'For Review')->count();
        $documents['approval'] = RequestDocument::where('requestStatus', 'For Approval')->count();
        $documents['archive'] = RequestDocument::where('requestStatus', 'Archive')->count();
        $documents['qpt'] = RequestDocument::where('docTypeID', '1')->count();
        $documents['pmt'] = RequestDocument::where('docTypeID', '4')->count();
        $documents['ftg'] = RequestDocument::whereIn('docTypeID', [2,3])->count();
        echo json_encode($documents);
        exit;
    }

    public function index(){

        return view('homev2');
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
