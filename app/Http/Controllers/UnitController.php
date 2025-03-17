<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Division;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
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
            return datatables()->of(Unit::select('unit.*', 'division.divName')
                ->leftJoin('division', 'unit.divID', '=', 'division.divID'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info mx-2" href="javascript:void(0)" onClick="editUnit({{ $unitID }})" data-toggle="tooltip" data-original-title="Edit">
                <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="deleteUnit(this)" data-id="{{ $unitID }}" data-toggle="tooltip" data-original-title="Delete">
                <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.unit');
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
            'unitName' => 'required|unique:unit,unitName,' . $request->id . ',unitID',],
            [ 'unitName.unique' => 'This Unit already Exist. Please choose a different name.',
            'unitName.required' => 'The Unit Name is required.'
        ]);
        if ($validator->fails()){

            return response()->json(['errors'=>$validator->errors()->all()]);
            
        }else{
            $unit = Unit::updateOrCreate(['unitID' => $request->unitID],
            [
                'unitID' => $request->unitID,
                'unitName' => $request->unitName,
                'divID' => $request->divID,
                'status' => $request->status,                    
            ]);    
                         
            return response()->json(['success'=> 'Successfully saved.', 'Unit' => $unit]);
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
        $where = array('unitID' => $request->unitID);
        $Unit  = Unit::where($where)->first();
      
        return response()->json($Unit);
    }
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Office  $Office
     * @return \Illuminate\Http\response
     */

    public function destroy(Unit $unit, Request $request, $id)
    {
        $Unit = Unit::where('unitID',$request->id)->delete();
        return response()->json(array('success' => true));
    }

    public function getDivisions()
    {
        $divisions = Division::select('divID', 'divName')->orderBy('divName', 'asc')->get();
        return response()->json(['data' => $divisions]);
    }
}
