<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Territory;
use App\Models\Inventory\Road;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class RoadController extends Controller
{
    public function index()
    {
        if(can_p('road.index') == false){
            return redirect()->route('dashboard');
        }
        $data['roads']=Road::orderBy('id','DESC')->get();
        return view ('Inventory.road.manage',$data);
    }
    function select2GenericList(Request $request){
        $roads = Road::select('id', 'name')->where("name", "LIKE", "%$request->value%")->where('status',1)->get();
        foreach ($roads as $road) {
            $data[] = ['id' => $road->id, 'text' => $road->name];
        }
        return json_encode($data);
    }
    public function store(Request $request)
    {
        if(can_p('road.add') == false){
            return redirect()->route('dashboard');
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('roads')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('roads')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Road();
            }
            else{
                $data=Road::find($request->id);
            }
            $data->name=$request->name;
            $data->territory_id=$request->territory;
            $data->status = 1;
            $data->save();
            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Save successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Update successfully.',
                ]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'ex'=> $e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }

    }
    public function edit(Request $request)
    {
        if(can_p('road.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Road::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'territory_name'=>$data->territory?->name,'territory_id'=>$data->territory_id]);
    }
    public function destroy(Request $request,$id)
    {
        if(can_p('road.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=Road::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('road.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('road.index')->with($notification);
        }
    }
}
