<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordEmail;
use App\Models\User;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;
    function getForgetPass(){
        return view('auth.forget_password');
    }
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    public function sentMailforgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if($user)
        {
            $token= $this->generateRandomString();
            $user->forget_token = $token;
            $user->forget_date = date("Y-m-d H:i:s");
            $user->update();
            // $user = $request->email;
            $send_mail = $user->email;
            $data['id']= $user->id;
            $data['token'] = $token;
            $details['email'] =  $send_mail;
            //dd(new ForgotPasswordEmail($data));
            //    dd($send_mail);
            $details['send_item']=new ForgotPasswordEmail($data);
            dispatch(new \App\Jobs\SendEmailJob($details));
            //Artisan::call('queue:listen');
            //Mail::to($send_mail)->send(new ForgotPasswordEmail($data));
            return redirect()->back()->with('message', 'Check your email and reset your Password, Thank You.');
        }else{
            return back()->with('error','Email is not Found');
        }
    }
    public function getResetPassword(Request $request,$id)
    {
        if(isset($request->token)){

            $data['user'] =$user= User::find($id);
            if($request->token == $user->forget_token){
                $date1 =  date("Y-m-d H:i:s");
                $t1 = strtotime( $date1);
                $t2 = strtotime( $user->forget_date );
                $diff =  $t1  - $t2;
                $hours = $diff / ( 60 * 60 );
                if($hours <= 1){
                    return view('auth.reset_password',$data);
                }else{
                    return redirect('/forgot-password')->with('message', 'Your token time is over, please resubmit your requset, Thank you.');//eamil send foget link
                }
               // dd($user);

            }else{
                return redirect('/forgot-password')->with('message', 'Your token is not match, please resubmit again, Thank You.');//eamil send foget link
            }
            //dd($id);

        }else{
            return redirect('/forgot-password')->with('message', 'Please resubmit your email, and reset your password. Thank You.');
        }
    }
    public function setResetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::find($id);
        $user->password = $request->password;
        $user->update();
        return redirect('/sign-in')->with('message', 'Your password is updated, Thank You.');
    }

}
