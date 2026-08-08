<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class SocialLoginController extends Controller
{
    public function redirectToGoogle(Request $request)
    {



        return Socialite::driver('google')->redirect();

    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $loginuser = User::where('google_id', $user->id)->orWhere('email',$user->email)->first();
            if($loginuser){
                Auth::login($loginuser);
                return redirect('dashboard');
            }else{
                return back()->with('error','Oops.Email Not Found!');
            }
        }catch (\Exception $e) {
            return back()->with('error','Oops.Something went wrong!');
        }
    }
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if($finduser){
                Auth::login($finduser);
                return redirect('dashboard');
            }else{
                return back()->with('error','Facebook Not Found');
            }
        }catch (\Exception $e) {
            return back()->with('error','Oops.Something went wrong!');
        }
    }
}
