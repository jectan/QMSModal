<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Office;
use App\Models\Staff;
use App\Models\UserRole;
use Exception;


class AccountController extends Controller
{
    // Select all Account to display in DataTables
    public function index()
    {
        $users = User::all();
        return view('pages.accounts.index', ['users' => $users]);
    }

    // Show Staff Account Form  for new
    public function create()
    {
        $roles = Role::all();
        $offices = Office::all();
        return view('pages.accounts.create',['roles' => $roles, 'offices' => $offices]);
    }

    // Save Staff Account Form to database
    public function store(Request $request)
    {
        $firstname  = $request->firstname;
        $middlename = $request->middlename;
        $lastname   = $request->lastname;
        $job_title  = $request->job_title;
        $office_id  = $request->office_id;
        
        $staffs = Staff::all();
        foreach($staffs as $staff){
            if($staff->firstname == $firstname && $staff->middlename == $middlename && $staff->lastname == $lastname && $staff->job_title == $job_title && $staff->office_id == $office_id){
                return redirect()->back()->with('error-user', 'Staff already registered!');
            }
        }
        
        $request->validate([
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'office_id'  => 'required',
            'role_id'    => 'required',
            'email'      => 'nullable|email',
            'username'   => 'required|string|max:255|unique:users',
        ]);
        
        try {
            DB::beginTransaction();
            
            $defaultPass = '*1234#';
            $user = User::create([
                'username'   => $request->username,
                'password'   => Hash::make($defaultPass),
                'role_id'   => $request->role_id,
            ]);

            $staff = Staff::create([
                'firstname'  => $request->firstname,
                'middlename' => $request->middlename,
                'lastname'   => $request->lastname,
                'job_title'  => $request->job_title,
                'contact_no' => $request->contact_no, 
                'email'      => $request->email, 
                'user_id'    => $user->id, 
                'office_id'  => $request->office_id, 
                'role_id'  => $request->role_id, 
            ]);

            // foreach($request->role_id as $role_id){
            //     UserRole::create([
            //         'user_id'   => $user->id,
            //         'role_id'   => $role_id
            //     ]);
            // }

            DB::commit();
            return redirect('accounts')->with('success', 'Account successfully created');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollback();
            return redirect('accounts')->with('error', $ex->getMessage());
        }
    }

    // Display Staff Account Info Form
    public function show($id)
    {   
        $user = User::find($id);
        $roles = Role::all();
        $offices = Office::all();

        return view('pages.accounts.update',['user' => $user, 'roles' => $roles, 'offices' => $offices]);
    }

    // Save updates of Staff Account Info
    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $request->user_id . ',id',
        ]);

        try {
            DB::beginTransaction();
            $staff = Staff::find($request->staff_id);
            $staff->firstname  = $request->firstname;
            $staff->middlename = $request->middlename;
            $staff->lastname   = $request->lastname;
            $staff->job_title  = $request->job_title;
            $staff->office_id  = $request->office_id;
            $staff->role_id     = $request->role_id;
            $staff->update();

            $user = User::find($request->user_id);
            $user->username = $request->username;
            $user->update();

            DB::commit();
            return redirect('accounts')->with('success', 'Successfully updated');

        } catch (Exception $ex) {
            DB::rollback();
            return redirect('accounts')->with('error', $ex->getMessage());
        }
    }

    // Delete Staff Account on database
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(array('success' => true));
    }    

    // Deactivate Staff Account 
    public function deactivate(Request $request)
    {
        $user = User::find($request->id);
        $user->isActive = $request->setActive;
        $user->blocked_at = \Carbon\Carbon::now();
        $user->blocked_by_id = Auth::id();
        $user->update();

        $message = $request->setActive ? 'activated' : 'deactivated';
        return response()->json(array( 'message' => 'Account has been '.$message ));
    }

    // Reset Password of Staff Account
    public function resetPassword($id)
    {
        $user = User::find($id);
        $defaultPass = '*1234#';
        $user->password = Hash::make($defaultPass);
        $user->isNew = 1;
        $user->update();
        
        return response()->json(array('success' => true));
    }

    // Display UserRoles of Staff Account
    public function getUserRole($id)
    {
        if(request()->ajax()) {
            $user_roles = DB::table('user_roles')->select(DB::raw('user_roles.id AS user_role_id, roles.name AS role_name'))
                ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->whereRaw('roles.id = user_roles.role_id')
                ->where(['user_roles.user_id' => $id])
                ->groupBy('user_role_id','role_name')
                ->get();

            return datatables()->of($user_roles)
                ->addColumn('action', '<a href="javascript:void(0)" id="removebtn" onClick="removeUserRole({{ $user_role_id }})" data-toggle="tooltip" data-original-title="Edit" class="btn btn-outline-danger btn-sm"><i class="fas fa-minus"></i></a>')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    // Add UserRoles of Staff Account
    public function addUserRole(Request $request)
    {
        $user_roles = UserRole::all();
        foreach($user_roles as $user_role){
            if( $user_role->user_id == $request->user_id && $user_role->role_id == $request->role_id ){
                return response()->json(array('success' => false));
            }
        }

        try {
            DB::beginTransaction();
            $user_role = UserRole::create([
                'user_id' => $request->user_id,
                'role_id' => $request->role_id
            ]);

            DB::commit();
            return response()->json(array('success' => true));
        } catch (Exception $ex) {
            DB::rollback();
            return redirect('accounts')->with('error', $ex->getMessage());
        }
    }

    // Remove UserRole of Staff Account
    public function removeUserRole($id)
    {
        $user_role_id = $id;
        $user_role = UserRole::find($user_role_id);
        $user_role->delete();

        return response()->json(array('success' => true));
    }
}
