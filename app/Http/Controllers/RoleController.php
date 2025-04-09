<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            return datatables()->of(Role::select('*'))
            ->addColumn('action', 
            '<div class="btn-group">
                <button type="button" class="btn btn-sm btn-info mx-2" href="javascript:void(0)" onClick="editrole({{ $id }})" data-toggle="tooltip" data-original-title="Edit">
                    <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button type="button" class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="deleteRole(this)" data-id="{{ $id }}" data-toggle="tooltip" data-original-title="Delete">
                    <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>'
        )
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('libraries.roles');
    }

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

    //Check If Unit has Employees
    public function checkHasUser(Request $request)
    {
        $roleID = $request->id;

        $document = User::where('role_id', $roleID)
            ->exists();
        return response()->json(['exists' => $document]);
    }
}