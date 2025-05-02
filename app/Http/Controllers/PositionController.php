<?php

namespace App\Http\Controllers;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Position;


class PositionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            return datatables()->of(Position::select('*'))
                ->addColumn('action', '<a href="javascript:void(0)" onClick="editPosition({{ $id }})"         data-toggle="tooltip" data-original-title="Edit" class="btn btn-success btn-sm">Edit</a>
                                 <a href="javascript:void(0);" id="Testdelete" onclick="deleteposition(this)" data-id="{{ $id }}" data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger btn-sm">Delete</a>')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('libraries.position');
    }


    // Store
    public function PositionStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'position_name' => 'required|unique:Positions,position_name,' . $request->position_id . ',id',
        ]);

        if ($validator->fails()) {

            return response()->json(['errors' => $validator->errors()->all()]);
        } else {

            $Position   =   Position::updateOrCreate(
                ['id' => $request->position_id],
                [
                    'code' => $request->code,
                    'position_name' => $request->position_name,
        

                ]
            );

            return response()->json(['success' => 'Successfully saved.']);
        }
    }



}