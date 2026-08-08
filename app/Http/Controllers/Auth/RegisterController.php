<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\Business;
// use App\Providers\RouteServiceProvider;
use App\Models\User;
// use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   

    /**
     * Where to redirect users after registration.
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
        // $this->middleware('guest');
    }
    function getSignUp(){
        return view('auth.sign-up');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    function postRegister(Request $request){
        //dd($request);
        $validators = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'max:11', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validators->fails()){
            return back()->withErrors($validators->errors())->withInput();
        }
        try{
            DB::beginTransaction();
            $business = New Business;
            $business->business_name = $request->business_name;
            $business->mobile_number = $request->full_number;
            $business->business_type_id = $request->business_type;
            $business->email = $request->email;
            $business->save();

            $user = new User;
            $user->name = $request->business_name;
            $user->email = $request->email;
            $user->mobile = $request->full_number;
            $user->business_id= $business->id;
            $user->password = Hash::make($request->password);
            $token= $this->generateRandomString(16);
            // dd($token);
            $user->email_verify_token = $token;
            $user->status = 1;

            $user->save();
        // Mail::to($user->email)->send(new VerifyEmail($user));
            //event(new Registered($user));
            DB::commit();
            return redirect()->route('sign_in')->with('success',"Register Successfully,Please Login in your Account.");
            //return redirect()->route('verification.notice',$user->id)->with('success','ssss');

        }catch(\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            return redirect()->back()->with('error',$e->getMessage());
        }

    }
    function getLogout(){
        auth()->logout();
        return redirect()->route('sign_in');
    }
    function getAdminLogout(){
        auth()->guard('admin')->logout();
        return redirect()->route('login');
    }
}
