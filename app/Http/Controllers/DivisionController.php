<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
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
            return datatables()->of(Division::select('*'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info mx-2" href="javascript:void(0)" onClick="editDivision({{ $divID }})" data-toggle="tooltip" data-original-title="Edit">
                <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="deleteDivision(this)" data-id="{{ $divID }}" data-toggle="tooltip" data-original-title="Delete">
                <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.division');
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
            'divName' => 'required|unique:division,divName,' . $request->divID . ',divID',],
            [ 'divName.unique' => 'This Division Name already Exist. Please choose a different name.',
            'divName.required' => 'The Division Name is required.'
        ]);
        if ($validator->fails()){

            return response()->json(['errors'=>$validator->errors()->all()]);
            
        }else{
            $division = Division::updateOrCreate(['divID' => $request->divID],
            [
                'divID' => $request->divID,
                'divName' => $request->divName,
                'status' => $request->status,                    
            ]);    
                         
            return response()->json(['success'=> 'Successfully saved.', 'office' => $division]);
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
        $where = array('divID' => $request->divID);
        $Division  = Division::where($where)->first();
      
        return response()->json($Division);
    }
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Office  $Office
     * @return \Illuminate\Http\response
     */

    public function destroy(Division $division, Request $request, $id)
    {
        $Division = Division::where('divID',$request->id)->delete();
        return response()->json(array('success' => true));
    }

    public function getOffices()
    {
        $empData['data'] = Division::orderby("name","asc")->get();
        return response()->json($empData);
    }
}
