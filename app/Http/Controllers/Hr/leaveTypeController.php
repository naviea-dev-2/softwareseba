<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\LeaveType;
use App\User;

use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class leaveTypeController extends Controller
{
    public function view(){
        if(can_p('leaveType.view') == false){
            return redirect()->route('dashboard');
        }
        $data['leaveTypes']=LeaveType::orderBy('id','DESC')->get();
        return view ('Hr.leaveType.manage',$data);
    }

    public function store(Request $request){
        if($request->id==0){
            if(can_p('leaveType.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('leaveType.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        date_default_timezone_set('Asia/Dhaka');



        $validator = Validator::make($request->all(),[
            'leaveCode'=>'required',
            'description'=>'required'
        ],[
            'leaveCode.required'=> 'Leave Code is required',
            'description.required'=> 'Description is required',
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
                $data=new LeaveType();
            }
            else{
                $data=LeaveType::find($request->id);
            }
            $data->leaveCode=$request->leaveCode;
            $data->description=$request->description;
            $data->day=$request->day;
            $data->hour=$request->hour;
            $data->save();

            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Leave Type add successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Leave Type update successfully.',
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

    public function delete(Request $request,$id){
        if(can_p('leaveType.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=LeaveType::find($id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('leaveType.view');

    }

    public function edit(Request $request){
        if(can_p('leaveType.edit') == false){
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

           $data=LeaveType::find($request->id);
        }

        return response()->json(['status'=>1,'leaveCode' => $data->leaveCode,'leaveTypeID'=>$data->id,'description'=>$data->description,'day'=>$data->day,'hour'=>$data->hour]);
    }
}
