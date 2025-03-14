<?php

namespace App\Http\Controllers;

use App\Models\Caller;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class CallerController extends Controller
{
   
    public function index()
    {   
        $barangay = Barangay::all();
        $caller=Caller::all();
        return view('pages.caller.index', ['callers' => $caller,'barangays' => $barangay]);
        // $caller=Caller::where('is_anonymous', '0')->get();
        // $anoncaller=Caller::where('is_anonymous', '1')->get();
        // return view('pages.caller.index', ['callers' => $caller, 'anoncallers'=> $anoncaller]);
    }
    
    public function create()
    {        
        $barangay = Barangay::all();
        return view('pages.caller.create',['barangays' => $barangay]);
    }

    public function store(Request $request) 
    {
        // if (!isset($request->anonymous)) {
            $rule = [
                'firstname'  => 'required|string|max:100',
                'middlename'   => 'nullable|string|max:100',
                'lastname' => 'required|string|max:100',
                'contact_no' => 'required|numeric|digits_between:10,11',
            ];   
            $request->validate($rule);
            $checkifexist = Caller::where("firstname", $request->firstname)
                ->where("middlename", $request->middlename)
                ->where("lastname", $request->lastname)->where("contact_no", $request->contact_no)->first();
            if ($checkifexist) {
                return redirect('/caller/create')->with(['error' => 'Caller with this number already exist']);
            }
        // }else{
        //     $rule = [
        //         'contact_no' => 'required|string|max:11',
        //     ];    
        //     $request->validate($rule);
        //     $checkifexist = Caller::where("contact_no", $request->contact_no)
        //     ->where("is_anonymous", "=", "1")->first();
        //     if ($checkifexist) {
        //         return redirect('/caller/create')->with(['error' => 'Anonymous Caller with this number already exist']);
        //     } 
        // }

        $caller=Caller::Create(
            [
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'address' => $request->address,
                'contact_no' => $request->contact_no,
                'email' => $request->email,    
                // 'is_anonymous' =>$request->anonymous ? true: false,  
                'barangay_id' => $request->barangay, 
                'created_by_id' =>  Auth::id(),
            ]);    
        
        if($request->process == 'Proceed to Ticket') {
            return redirect('caller/'. $caller->id.'/documents/create')->with(['success' => 'You can now add ticket!']);
        } else {
            return redirect('/caller')->with(['success' => 'Successfully Saved!']);
        }
        
    }

    public function show($id)
    {   
        $barangay = Barangay::all();
        $caller = Caller::find($id);
        return view('pages.caller.update',['caller' => $caller,'barangays' => $barangay]);
    }

    public function view($id)
    {
        $caller = Caller::find($id);
        return view('pages.caller.view',['caller' => $caller]);
    }
    
    public function update(Request $request)
    {
        
        $rule = [
            'firstname'  => 'required|string|max:100',
            'middlename'   => 'nullable|string|max:100',
            'lastname' => 'required|string|max:100',
            'contact_no' => 'required|numeric|digits_between:10,11',
        ];      
        $request->validate($rule);

        $caller = Caller::find($request->id);
        $checkifexist = Caller::where("firstname", $request->firstname)
        ->where("middlename", $request->middlename)
        ->where("lastname", $request->lastname)->where("contact_no", $request->contact_no)->first();
        if ($checkifexist){
                    return redirect()->back()->with(['error' => 'Caller with this number already exist']);
            }else{
            $caller->firstname         = $request->firstname;
            $caller->middlename        = $request->middlename;
            $caller->lastname          = $request->lastname;
            $caller->address           = $request->address;
            $caller->barangay_id       = $request->barangay_id;
            $caller->contact_no        = $request->contact_no;
            $caller->email             = $request->email;
            $caller->updated_by_id     =  Auth::id();
            $caller->update();
            return redirect('/caller')->with(['success' => 'Successfully Updated!']);
            }
    }

    public function destroy(Request $request)
    {
        $caller = Caller::find($request->id);
        $caller->delete();
        return response()->json(array('success' => true));
    }


    
   
   
}
