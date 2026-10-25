<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Setting\Shift;
use Illuminate\Http\Request;
use App\User;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Concerns\ListsRecords;
class shiftManageController extends Controller
{
    use ListsRecords;
    public function index(Request $request)
    {
        // dd("ss");
        $shifts = Shift::query()
            ->when($request->filled('q'), fn ($q) => $q->where('shiftName', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('shiftName')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.shift.index', [
            'shifts'  => $shifts,
            'editing' => $this->editing($request, Shift::class),
        ]);
    }
    public function store(Request $request)
    {
        Shift::create($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('shift.index')->with('status', 'Shift saved.');
    }

    public function update(Request $request, Shift $shift)
    {
        $shift->update($this->validated($request));
        // $this->recalc->recalculateAll();

        return redirect()->route('shift.index')->with('status', 'Shift updated.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        // $this->recalc->recalculateAll();

        return back()->with('status', 'Shift deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'shiftName'       => ['required', 'string', 'max:255'],
            'startTime' => ['required', 'date_format:H:i'],
            'endTime'   => ['required', 'date_format:H:i'],
            'break_min'  => ['nullable', 'integer', 'min:0'],
            'status'     => ['required', 'in:Active,Inactive'],
        ]);
    }
    public function view(){
        
        if(can_p('viewDepartment') == false){
            return redirect()->route('dashboard');
        }
        $data['shifts']=Shift::orderBy('id','DESC')->get();
        return view ('Hr.shiftManage.manage',$data);
    }
     function select2Shift(Request $request){
        $data_list = Shift::select('id', 'shiftName')
        ->where("shiftName", "LIKE", "%$request->value%")
        
        ->take(20)->get();
       
        $data[] = ['id' => '', 'text' =>'Select Shift'];
        foreach ($data_list as $res) {
            $data[] = ['id' => $res->id, 'text' => $res->shiftName];
        }
        return json_encode($data);
    }

    // public function store(Request $request){
    //     if(can_p('shiftManage.add') == false){
    //         return redirect()->route('dashboard');
    //     }
    //     date_default_timezone_set('Asia/Dhaka');
    //     $validator = Validator::make($request->all(),[
    //         'shiftName'=>'required',
    //         'startTime'=>'required',
    //         'endTime'=>'required'
    //         ],[
    //         'shiftName.required'=> 'Shift Name is required',
    //         'startTime.required'=> 'Start Time is required',
    //         'endTime.required'=> 'End Time is required',
    //     ]);
    //     if($validator->fails()){
    //         return response([
    //             'status' => 0,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    //     try{
    //         DB::beginTransaction();
    //         if($request->id==0){
    //             $data=new Shift();
    //         }
    //         else{
    //             $data=Shift::find($request->id);
    //         }
    //         $data->shiftName=$request->shiftName;
    //         $data->startTime=$request->startTime;
    //         $data->endTime=$request->endTime;
    //         $data->save();

    //         DB::commit();
    //         if($request->id==0){
    //             return response([
    //                 'status' => 1,
    //                 'success' => 'Shift add successfully.',
    //             ]);
    //         }else{
    //             return response([
    //                 'status' => 1,
    //                 'success' => 'Shift update successfully.',
    //             ]);
    //         }
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         return response([
    //             'status' => 0,
    //             'error' => 'Something went Wrong!',
    //         ]);
    //     }

    //     //  $notification=array(
    //     //     'message'=>"Save Success",
    //     //     'alert-type'=>'success'
    //     //  );

    //     // return redirect()->route('shiftManage.view')->with($notification);
    // }

    // public function delete(Request $request){
    //     if(can_p('shiftManage.delete') == false){
    //         return redirect()->route('dashboard');
    //     }
    //     $data=Shift::find($request->id);
    //     $data->delete();
    //     $notification=array(
    //         'message'=>"Delete successfull",
    //         'alert-type'=>'success'
    //     );
    //     return redirect()->route('shiftManage.view');
    // }

    public function edit(Request $request){
        if(can_p('shiftManage.edit') == false){
            $data["msg"] ='Edit permision is not allowed';
            $data["error"] ='Edit permision is not allowed';
            $data["status"] ="no";
            return response()->json($data);
        }
        if(!$request->id){
           return response()->json(['status'=>'no','msg'=>'Not Found']);
        } else{
            $data=Shift::find($request->id);
        }
        $st_arr=explode(":",$data->startTime);
        if($st_arr[0] > 12){
            $st_time = $st_arr[0] - 12 .":".$st_arr[1]." PM";
        }else{
            $st_time =$data->startTime.' AM';
        }
        $et_arr=explode(":",$data->endTime);
        if($et_arr[0] > 12){
            $et_time = $et_arr[0] - 12 .":".$et_arr[1]." PM";
        }else{
            $et_time =$data->endTime.' AM';
        }
        return response()->json(['status'=>'ok',"st_time"=>$st_time,"et_time"=>$et_time,'shiftName' => $data->shiftName,'shiftID'=>$data->id,'startTime'=>$data->startTime,'endTime'=>$data->endTime]);
    }
}
