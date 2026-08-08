<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\LeavePart;
use App\Models\Hr\LeaveType;
use App\Models\Hr\LeaveTagline;
use App\User;

use Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class leaveTaglineController extends Controller
{
    public function view(){
        if(can_p('leaveTagline.view') == false){
            return redirect()->route('dashboard');
        }
        $data['leaveTaglines']=LeaveTagline::orderBy('id','DESC')
                            ->get();

        $data['leaveTypes']=LeaveType::orderBy('id','DESC')->get();
        $data['leaveParts']=LeavePart::orderBy('id','DESC')->get();
        return view ('Hr.leaveTagline.manage',$data);
    }

    public function store(Request $request){
        //dd("ss");
        if($request->id==0){
            if(can_p('leaveTagline.add') == false){
                return response()->json(['status'=>0,'error'=>'Add Permission is not found']);
            }
        }else{
            if(can_p('leaveTagline.edit') == false){
                return response()->json(['status'=>0,'error'=>'Edit Permission is not found']);
            }
        }
        date_default_timezone_set('Asia/Dhaka');
        $validator = Validator::make($request->all(), [
            'leaveTypeID' => 'required',
            'leavePartID' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'status'=>0,
                'errors'=>$validator->errors()->all()
            ]);
        }
        try{
            DB::beginTransaction();
           
            if($request->id==0){

                $data=new LeaveTagline();
            }
            else{
                $data=LeaveTagline::find($request->id);
            }
            $data->leaveTypeID=$request->leaveTypeID;
            $data->leavePartID=$request->leavePartID;
            $data->save();

            DB::commit();
            if($request->id==0){
                return response()->json(['status'=>1,'success'=>'Save Success']);
            }else{
                return response()->json(['status'=>1,'success'=>'Save Success']);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>0,'error'=>$e->getMessage()]);
            
        }

      

    }

    public function delete(Request $request,$id){
        if(can_p('leaveTagline.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=LeaveTagline::find($id);

        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('leaveTagline.view');

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

           $data=LeaveTagline::find($request->id);
           $leaveTypes=LeaveType::orderBy('id','DESC')->get();
           $leaveParts=LeavePart::orderBy('id','DESC')->get();

           // leave type
           $leaveTypeID='';
            foreach($leaveTypes as $leaveType){

                if($leaveType->id==$data->leaveTypeID){
                    $leaveTypeID.='<option value="'.$leaveType->id.'" selected>'.$leaveType->leaveCode.' - '.$leaveType->description.'</option>';
                }
                else{
                   $leaveTypeID.='<option value="'.$leaveType->id.'">'.$leaveType->leaveCode.' - '.$leaveType->description.'</option>';
                }

            }

           // leave part
           $leavePartID='';
            foreach($leaveParts as $leavePart){
                if($leavePart->id==$data->leavePartID){
                    $leavePartID.='<option value="'.$leavePart->id.'" selected>'.$leavePart->levaePartName.' - '.$leavePart->day.'</option>';
                }else{
                   $leavePartID.='<option value="'.$leavePart->id.'">'.$leavePart->levaePartName.' - '.$leavePart->day.'</option>';
                }

            }

        }

        return response()->json(['status'=>1,'leavePartID' => $leavePartID,'leaveTaglinID'=>$data->id,'leaveTypeID'=>$leaveTypeID]);
    }
}
