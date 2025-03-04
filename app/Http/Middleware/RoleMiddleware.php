<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        if ($request->user() && $role[0] == "all" ) {
            return $next($request);
        }
        if ($request->user() && $role == $request->user()->hasRole($role) ) {
            return $next($request);
        }
        return $this->unauthorized();
    }

    private function unauthorized($message = null)
    {
        // return view('home');
        Auth::logout();
        return redirect('/login')->with('error',  'You are unauthorized to access this page');
        // return response()->json([
        //     'message' => $message ? $message :,
        //     'success' => false
        // ], 401);
   
     }
}