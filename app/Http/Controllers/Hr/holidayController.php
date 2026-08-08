<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\MonthManage;
use App\Models\Hr\Holiday;
use App\User;
// use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class holidayController extends Controller
{
   public function view(){

        $data['holidays']=Holiday::orderBy('holidays.id','DESC')
                          ->get();

        $currentYear=Carbon::now()->format('Y');
        $data['months']=MonthManage::where('monthDate','LIKE',$currentYear.'%')->get();

        return view ('Hr.holiday.manage',$data);
    }

    public function store(Request $request){

        date_default_timezone_set('Asia/Dhaka');



        $validator = Validator::make($request->all(),[
            'monthID'=>'required',
            'startDate'=>'required',
            'endDate'=>'required',
            'day'=>'required',
            'description'=>'required'
        ],[
            'monthID.required'=> 'Month is required',
            'startDate.required'=> 'Start Date is required',
            'endDate.required'=> 'End Date is required',
            'day.required'=> 'Day is required',
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
                $data=new Holiday();
            }
            else{
                $data=Holiday::find($request->id);
            }
            $data->monthID=$request->monthID;
            $data->startDate=$request->startDate;
            $data->endDate=$request->endDate;
            $data->day=$request->day;
            $data->description=$request->description;
            $data->save();

            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Holiday add successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Holiday update successfully.',
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

        $data=Holiday::find($request->id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('holiday.view');

    }

    public function edit(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Holiday::find($request->id);
        }

        return response()->json(['holidayID'=>$data->id,'monthID' => $data->monthID,'startDate'=>$data->startDate,'endDate'=>$data->endDate,'day'=>$data->day,'description'=>$data->description]);
    }
}
