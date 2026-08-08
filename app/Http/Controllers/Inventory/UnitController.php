<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Inventory\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class UnitController extends Controller
{
     public function index()
    {
        if(can_p('unit.index') == false){
            return redirect()->route('dashboard');
        }
        $data['units']=Unit::orderBy('id','DESC')->get();
        return view ('Inventory.unit.manage',$data);
    }
    function select2unit(Request $request){
        // $units = Unit::select('id', 'name')->where('category_id',$request->cat_id)->where("name", "LIKE", "%$request->value%")->get();
    $units = Unit::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
       $data[] = ['id' =>"", 'text' =>"Select"];
        foreach ($units as $unit) {
            $data[] = ['id' => $unit->id, 'text' => $unit->name];
        }
        return json_encode($data);
    }
    function select2unitProductBy(Request $request){
        $units = Unit::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        $data[] = ['id' =>"", 'text' =>"Select"];
        foreach ($units as $unit) {
            $data[] = ['id' => $unit->id, 'text' => $unit->name];
        }
        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(can_p('unit.add') == false){
            return redirect()->route('dashboard');
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('units')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
                'symbol'=>'required',
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('units')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
                    }),
                ],
                'symbol'=>'required',
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
                $data=new Unit();
            }
            else{
                $data=Unit::find($request->id);
            }
            $data->name=$request->name;
            $data->symbol=$request->symbol;
            // $data->business_type_id=auth()->user()->business->business_type_id;
            // $data->category_id=$request->category;
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
                'error' => 'Something went Wrong!',
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        if(can_p('unit.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Unit::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'symbol'=>$data->symbol]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        if(can_p('unit.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=Unit::find($id);
            $data->delete();
             DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('unit.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('unit.index')->with($notification);
        }

    }
}
