<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            return datatables()->of(Role::select('*'))
            ->addColumn('action', '<class="btn-group">
               <a href="javascript:void(0)" onClick="editrole({{ $id }})" data-toggle="tooltip" class="btn btn-info btn-xs"><i class="fas fa-edit"></i></a>
               <a href="javascript:void(0);" onclick="deleteCase(this)" data-id="{{ $id }}" class="btn btn-info btn-xs"><i class="fas fa-trash"></i></a>')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.roles');
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
                'name'        => 'required|unique:roles,name,' . $request->role_id . ',id',
                
            ]);
    
            //validation
            if ($validator->fails()) {
    
                return response()->json(['errors' => $validator->errors()->all()]);
            } else {
                //Saves data if no duplicates
                $RoleId = $request->role_id;
                
                $Role  =   Role::updateOrCreate(['id' => $RoleId],
                            [
                            'name' => $request->name,
                            
                            ]);    
                    
                return Response()->json(['success' => 'Successfully Saved!']);
            }
     }
     public function edit(Request $request)
     {   
         $where = array('id' => $request->id);
         $Role  = Role::where($where)->first();
 
         return Response()->json($Role);
     }
     public function destroy($id)
     {
         
         $data = Role::find($id)->delete();
         return response()->json(array('success' => true));
     }
}