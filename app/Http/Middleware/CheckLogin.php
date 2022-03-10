<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Config;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // nếu k đăng nhập thàng công thì redirect
            return redirect()->route('g_login');
        }
        else {
            if(Auth::user()->level != Config::get('constants.ADMIN_LEVEL')) {
                return redirect()->route('index');
            }
        }
        return $next($request);
    }
}
