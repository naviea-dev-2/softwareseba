<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
class IsUserActive
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
        // if (Auth::check()) {
        //     if (Auth::user()->is_active) {
        //         return $next($request);
        //     }

        //     return redirect()->route('registration.next');
        // }

        // return $next($request);
        // if (auth()->user()->is_active == false) {
        //     return abort(403, '<h3>Your account is blocked</h3> <br> Please Contact Us: <a href="https://talikaapp.com/contact-us/">https://talikaapp.com/contact-us</a>');
        // }
        $au_business = auth()->user()->business;
        // dd($au_business);
        $free_exipre = false;
        if($au_business->user_type == 0){
            $results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
            $data = array();
            if($results){
                $dataObj = json_decode($results->option_value);
                $data['days'] = $dataObj->days;
            }else{
                $data['days'] = 0;
            }
            // dd($data['days']);
            // dd($au_business->start_date);
            $user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days']);
            $user_now_date = \Carbon\Carbon::now();
        
            if($user_now_date > $user_end_date){
                $free_exipre = true;
            }
        }
        if($free_exipre == false){
            return $next($request);
        }else{
            if(Route::is('dashboard') || Route::is('sign_out')){
                return $next($request);
            }else{
                return redirect()->route('dashboard');
            }
            
        }
        
    }
}
