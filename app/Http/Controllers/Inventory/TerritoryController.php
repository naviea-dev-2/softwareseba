<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Territory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class TerritoryController extends Controller
{
    public function index()
    {
        if(can_p('territory.index') == false){
            return redirect()->route('dashboard');
        }
        $data['territories']=Territory::orderBy('id','DESC')->get();
        return view ('Inventory.territory.manage',$data);
    }
    function select2TerritoryList(Request $request){
        $territories = Territory::select('id', 'name')->where("name", "LIKE", "%$request->value%")->where('status',1)->get();
        foreach ($territories as $territory) {
            $data[] = ['id' => $territory->id, 'text' => $territory->name];
        }
        return json_encode($data);
    }
    public function store(Request $request)
    {
        if(can_p('territory.add') == false){
            return redirect()->route('dashboard');
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('territories')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('territories')->where(function ($query) use ($id) {
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
                $data=new Territory();
            }
            else{
                $data=Territory::find($request->id);
            }
            $data->name=$request->name;
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
        if(can_p('territory.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Territory::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name]);
    }
    public function destroy(Request $request,$id)
    {
        if(can_p('territory.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=Territory::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('territory.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('territory.index')->with($notification);
        }
    }
}
