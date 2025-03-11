<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestType;
use Illuminate\Support\Facades\Validator;

class RequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\response
     */
  /*   public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Office::select('*'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info" href="javascript:void(0)" onClick="editOffice({{ $id }})" data-toggle="tooltip" data-original-title="Edit">
                <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-info" href="javascript:void(0)" onclick="deleteOffice(this)" data-id="{{ $id }}" data-toggle="tooltip" data-original-title="Delete">
                <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.office');
    } */
      
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(RequestType::select('*'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info mx-2" href="javascript:void(0)" onClick="editRequestType({{ $requestTypeID }})" data-toggle="tooltip" data-original-title="Edit">
                <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="deleteRequestType(this)" data-id="{{ $requestTypeID }}" data-toggle="tooltip" data-original-title="Delete">
                <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.requestType');
    }

      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\response
     */
    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'requestTypeDesc' => 'required|unique:requestType,requestTypeDesc,' . $request->requestTypeID . ',requestTypeID',],
            [ 'requestTypeDesc.unique' => 'This Document Type already Exist. Please choose a different name.',
            'requestTypeDesc.required' => 'The Document Type is required.'
        ]);
        if ($validator->fails()){

            return response()->json(['errors'=>$validator->errors()->all()]);
            
        }else{
            $requestType = RequestType::updateOrCreate(['requestTypeID' => $request->requestTypeID],
            [
                'requestTypeID' => $request->requestTypeID,
                'requestTypeDesc' => $request->requestTypeDesc,
                'status' => $request->status,                    
            ]);    
                         
            return response()->json(['success'=> 'Successfully saved.', 'office' => $requestType]);
        }
    }
      
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Office  $Office
     * @return \Illuminate\Http\response
     */
    public function edit(Request $request)
    {
        $where = array('requestTypeID' => $request->requestTypeID);
        $RequestType  = RequestType::where($where)->first();
      
        return response()->json($RequestType);
    }
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Office  $Office
     * @return \Illuminate\Http\response
     */

    public function destroy(RequestType $requestType, Request $request, $id)
    {
        $RequestType = RequestType::where('requestTypeID',$request->id)->delete();
        return response()->json(array('success' => true));
    }

    public function getOffices()
    {
        $empData['data'] = RequestType::orderby("name","asc")->get();
        return response()->json($empData);
    }
}
