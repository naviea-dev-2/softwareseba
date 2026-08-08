<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\LeavePart;
use App\User;

use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class leavePartController extends Controller
{
    public function view(){
        if(can_p('leavePart.view') == false){
            return redirect()->route('dashboard');
        }
        $data['leaveParts']=LeavePart::orderBy('id','DESC')->get();
        return view ('Hr.leavePart.manage',$data);
    }

    public function store(Request $request){
        if($request->id==0){
            if(can_p('leavePart.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('leavePart.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        date_default_timezone_set('Asia/Dhaka');


       $validator = Validator::make($request->all(),[
              'levaePartName'=>'required',
              'day'=>'required'
        ],[
            'levaePartName.required'=> 'Leave Part Name is required',
            'day.required'=> 'Day is required',
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new LeavePart();
            }
            else{
                $data=LeavePart::find($request->id);
            }
            $data->levaePartName=$request->levaePartName;
            $data->day=$request->day;
            $data->save();



            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Leave Part add successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Leave Part update successfully.',
                ]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'error' => 'Something went Wrong!',
            ]);
        }
    }

    public function delete(Request $request){
        if(can_p('leavePart.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=LeavePart::find($request->id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('leavePart.view');

    }

    public function edit(Request $request){
        if(can_p('leavePart.edit') == false){
            return response([
                'status' => 0,
                'msg' => 'Edit permission is not allowed',
            ]);
        }
        if (!$request->id) {
            return response([
                'status' => 0,
                'msg' => 'Not Found',
            ]);
        } else {

           $data=LeavePart::find($request->id);
        }

        return response()->json(['status'=>1,'levaePartName' => $data->levaePartName,'leavePartID'=>$data->id,'day'=>$data->day]);
    }
}
