<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\MonthManage;
use App\User;
// use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class monthManageController extends Controller
{
    public function view(){
        if(can_p('monthManage.view') == false){
            return redirect()->route('dashboard');
        }
        $data['monthManages']=MonthManage::orderBy('id','DESC')->get();
        return view ('Hr.monthManage.manage',$data);
    }

    public function store(Request $request){
        if($request->id == 0){
            if(can_p('monthManage.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('monthManage.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        date_default_timezone_set('Asia/Dhaka');



        $validator = Validator::make($request->all(),[
            'monthDate'=>'required',
            'monthTotalDay'=>'required',
            'workingDay'=>'required',
            'holiday'=>'required'
        ],[
            'monthDate.required'=> 'Month Date is required',
            'monthTotalDay.required'=> 'Moth Total Day is required',
            'workingDay.required'=> 'Working Day is required',
            'holiday.required'=> 'Holiday is required',
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
                $data=new MonthManage();
            }
            else{
                $data=MonthManage::find($request->id);
            }
            $data->monthDate=$request->monthDate;
            $data->monthTotalDay=$request->monthTotalDay;
            $data->workingDay=$request->workingDay;
            $data->holiday=$request->holiday;
            $data->save();



            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Month add successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Month update successfully.',
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
        if(can_p('monthManage.delete') == false){
            return redirect()->route('dashboard');
        }
        $data=MonthManage::find($request->id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

        return redirect()->route('monthManage.view');

    }

    public function edit(Request $request){
        if(can_p('monthManage.edit') == false){
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

           $data=MonthManage::find($request->id);
        }

        return response()->json(['status'=>1,'monthManageID'=>$data->id,'monthDate' => $data->monthDate,'monthTotalDay'=>$data->monthTotalDay,'workingDay'=>$data->workingDay,'holiday'=>$data->holiday]);
    }
}
