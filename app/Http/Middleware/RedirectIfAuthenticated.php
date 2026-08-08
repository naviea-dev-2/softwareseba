<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if ($guard == "admin" && Auth::guard($guard)->check()) {
                return redirect('/admin/dashboard');
            }
            if(Auth::guard('admin')->check()){
                $route_name = Route::currentRouteName();
                if($route_name == "login"){
                    return redirect('/admin/dashboard');
                }
            }
            if(Auth::guard('web')->check()){
                $route_name = Route::currentRouteName();
                if($route_name == "sign_in" || $route_name == "sign_up"){
                    return redirect()->route('dashboard');
                }
            }
           /// dd($guard);
            if ($guard == "web" && Auth::guard($guard)->check()) {
                
                return redirect(RouteServiceProvider::HOME);
            }
        }
         //dd($guard);
        return $next($request);
    }
}
