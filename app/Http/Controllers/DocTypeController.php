<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocType;
use App\Models\RequestDocument;
use Illuminate\Support\Facades\Validator;

class DocTypeController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(DocType::select('*'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info mx-2" href="javascript:void(0)" onClick="editDocType({{ $docTypeID }})" data-toggle="tooltip" data-original-title="Edit">
                <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="deleteDocType(this)" data-id="{{ $docTypeID }}" data-toggle="tooltip" data-original-title="Delete">
                <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.docType');
    }

    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'docTypeDesc' => 'required|unique:DocType,docTypeDesc,' . $request->docTypeID . ',docTypeID',],
            [ 'docTypeDesc.unique' => 'This Document Type already Exist. Please choose a different name.',
            'docTypeDesc.required' => 'The Document Type is required.'
        ]);
        if ($validator->fails()){

            return response()->json(['errors'=>$validator->errors()->all()]);
            
        }else{
            $docType = DocType::updateOrCreate(['docTypeID' => $request->docTypeID],
            [
                'docTypeID' => $request->docTypeID,
                'docTypeDesc' => $request->docTypeDesc,
                'status' => $request->status,                    
            ]);    
                         
            return response()->json(['success'=> 'Successfully saved.', 'office' => $docType]);
        }
    }

    public function edit(Request $request)
    {
        $where = array('docTypeID' => $request->docTypeID);
        $DocType  = DocType::where($where)->first();
      
        return response()->json($DocType);
    }

    public function destroy(DocType $docType, Request $request, $id)
    {
        $DocType = DocType::where('docTypeID',$request->id)->delete();
        return response()->json(array('success' => true));
    }

    //Check If Type has Documents
    public function checkHasDoc(Request $request)
    {
        $docTypeID = $request->docTypeID;

        $document = RequestDocument::where('docTypeID', $docTypeID)
            ->exists();

        return response()->json(['exists' => $document]);
    }

   /*  public function getOffices()
    {
        $empData['data'] = DocType::orderby("name","asc")->get();
        return response()->json($empData);
    } */
}
