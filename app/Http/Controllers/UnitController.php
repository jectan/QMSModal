<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Division;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
      
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

    public function edit(Request $request)
    {
        $where = array('unitID' => $request->unitID);
        $Unit  = Unit::where($where)->first();
      
        return response()->json($Unit);
    }

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

    //Check If Unit has Employees
    public function checkHasStaff(Request $request)
    {
        $unitID = $request->unitID;

        $document = Staff::where('unitID', $unitID)
            ->exists();

        return response()->json(['exists' => $document]);
    }
}
