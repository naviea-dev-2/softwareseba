<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        //dd(Route::currentRouteName());
        // $route_name = Route::currentRouteName();
        // if(str_contains($route_name , "ajax") || str_contains($route_name , "store") || str_contains($route_name , "update")){
             return $next($request);
        // }else{
        //     if(auth()->user()->user_type == 0){
        //         //return $next($request);
                
        //         if(can_p($route_name)){
        //             return $next($request);
        //         }
                
            
        //         return redirect()->route('dashboard');
        //     }else{
            
        //         if(can_p($route_name)){
        //             return $next($request);
        //         }
        //         if(str_contains($route_name , "ajax")){
        //             return $next($request);
        //         }
        //         return redirect()->route('dashboard');
        //     }
        // }
        
        
    }
}
