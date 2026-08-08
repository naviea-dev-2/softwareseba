<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    function adminLogin(){
         return view('auth.login');
    }
    function setAdminLogin(Request $request){
        // $user = New Admin;
        // $user->name = "Admin";
        // $user->mobile = "";
        // $user->email = "admin@gmail.com";
        // $user->password = "123456789";
        // $user->save();
        $validators = Validator::make($request->all(), [
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validators->fails()){
           //  dd($validators->errors());
            return back()->withErrors($validators->errors())->withInput();
        }
        $remember_me = $request->has('remember_me') ? true : false;


        if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember_me)){
            return redirect('admin/dashboard');
        }
        return back()->with('error','Email or password is not Correct')->withInput();
    }
    function getLogin(){
        $data['software_services'] = \App\Models\SoftwareService::get();
        return view('auth.sign-in',$data);
    }
    function setLogin(Request $request){
        //dd($request->all());
        $validators = Validator::make($request->all(), [
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validators->fails()){
           //  dd($validators->errors());
            return back()->withErrors($validators->errors())->withInput();
        }
        // dd(auth()->attempt($request->only(['email','password'])));
        if(auth()->attempt($request->only(['email','password']))){

            return redirect()->route('dashboard');
        }
        // $credentials = [
        //     'mobile' => $request->email,
        //     'password' => $request->password,
        // ];
        // if(auth()->attempt($credentials)){

        //     return redirect('dashboard');
        // }
        return back()->with('error','Email/Phone Number or password is not Correct')->withInput();

    }
}
