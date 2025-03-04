<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\response
     */
    public function index()
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
            'code' => 'required|unique:offices,code,' . $request->id . ',id',
            'name' => 'required|unique:offices,name,' . $request->id . ',id',
            'head' => 'nullable',
        ]);
        
        if ($validator->fails()){

            return response()->json(['errors'=>$validator->errors()->all()]);
            
        }else{
            $office = Office::updateOrCreate(['id' => $request->office_id],
            [
                'code' => $request->code,
                'name' => $request->name,
                'head' => $request->head,                    
            ]);    
                         
            return response()->json(['success'=> 'Successfully saved.', 'office' => $office]);
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
        $where = array('id' => $request->id);
        $Office  = Office::where($where)->first();
      
        return response()->json($Office);
    }
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Office  $Office
     * @return \Illuminate\Http\response
     */

    public function destroy(Office $office, Request $request, $id)
    {
        $Office = Office::where('id',$request->id)->delete();
        return response()->json(array('success' => true));
    }

    public function getOffices()
    {
        $empData['data'] = Office::orderby("name","asc")->get();
        return response()->json($empData);
    }
}
