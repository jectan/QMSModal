<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{
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

    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'divName' => 'required|unique:Division,divName,' . $request->divID . ',divID',],
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

    public function edit(Request $request)
    {
        $where = array('divID' => $request->divID);
        $Division  = Division::where($where)->first();
      
        return response()->json($Division);
    }

    public function destroy(Division $division, Request $request, $id)
    {
        $Division = Division::where('divID',$request->id)->delete();
        return response()->json(array('success' => true));
    }

  /*   public function getOffices()
    {
        $empData['data'] = Division::orderby("name","asc")->get();
        return response()->json($empData);
    } */

    //Check If Division has Units
    public function checkHasUnit(Request $request)
    {
        $divID = $request->divID;

        $document = Unit::where('divID', $divID)
            ->exists();

        return response()->json(['exists' => $document]);
    }
}
