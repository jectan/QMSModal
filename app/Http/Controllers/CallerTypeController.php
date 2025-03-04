<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallerType;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CallerTypeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            return datatables()->of(CallerType::select('*'))
            ->addColumn('action', '<class="btn-group">
               <a href="javascript:void(0)" onClick="editcaller({{ $id }})" data-toggle="tooltip" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
               <a href="javascript:void(0);" onclick="deletecaller(this)" data-id="{{ $id }}" class="btn btn-info btn-xs"><i class="fas fa-trash"></i></a>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.caller-type');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    { 
        $validator = Validator::make($request->all(), [
                'name'        => 'required|unique:caller_types,name,' . $request->caller_id . ',id',
                
            ]);
    
            //validation
            if ($validator->fails()) {
    
                return response()->json(['errors' => $validator->errors()->all()]);
            } else {
                //Saves data if no duplicates
                $CallerTypeId = $request->caller_id;
                
                $CallerType  =   CallerType::updateOrCreate(['id' => $CallerTypeId],
                            [
                            'name' => $request->name,
                            'description' => $request->description,
                            
                            ]);    
                    
                return Response()->json(['success' => 'Successfully Saved!']);
            }
     }
     public function edit(Request $request)
     {   
         $where = array('id' => $request->id);
         $CallerType  = CallerType::where($where)->first();
 
         return Response()->json($CallerType);
     }
     public function destroy($id)
     {
         
         $data = CallerType::find($id)->delete();
         return response()->json(array('success' => true));
     }
}