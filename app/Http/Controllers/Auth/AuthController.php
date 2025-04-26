<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){

        if (Auth::check()) {
            // Redirect authenticated user to dashboard (or wherever you want)
            return redirect('/dashboard');
        }
        
        return view('auth.login',['show' =>  true]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Get the authenticated user
    
            if ($user->isActive != true) { // Check for account activity *before* isNew
                Auth::logout(); // Log the user out since the attempt was successful
                return redirect('login')->with('error', 'Sorry, your account was deactivated.');
            }
    
            if ($user->isNew == true) {
                return redirect('setpassword');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return redirect('login')->with('error', 'You have entered invalid credentials');
    }

    public function logout(){
        Auth::logout();
        return redirect('login');
    }

    // public function home(){
    //     return view('home');
    // }

    public function setpassword(){
        return view('auth.setpassword');
    }

    public function storepassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:5|confirmed'
        ]);

        User::find($request->id)->update([
            'password' => Hash::make($request->password),
            'isNew'    => 0
        ]);

        return redirect('login')->with('success', 'Now try to login.');
    }
}

// class AuthController extends Controller
// {
//     public function login(){
//         return view('auth.login');
//     }

//     public function authenticate(Request $request)
//     {
//         $request->validate([
//             'username' => 'required|string',
//             'password' => 'required|string',
//         ]);
//         $credentials = $request->only('username', 'password');

//         if (Auth::attempt($credentials)) {
      
//             return redirect()->intended('home');
//                 } else{
//                     return redirect('login')->with('error', 'Sorry, wala ka account');
//                 }
//     }
//     public function home(){
//         return view('home');
//     }
//     public function logout(){
//         Auth::logout();
//         return redirect('login');
//     }
//     }
