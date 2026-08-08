<?php

namespace App\Http\Controllers\RealState;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PropertyAmenity;
use Illuminate\Validation\Rule;
class PropertyAmenityController extends Controller
{
     public function index()
    {
        // if(can_p('property_amenity.index') == false){
        //     return redirect()->route('dashboard');
        // }
        return view ('RealState.amenity.manage');
    }
    function select2AmenityList(Request $request){
        $property_amenitys = PropertyAmenity::where('business_type_id',auth()->user()->business->business_type_id)->select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($property_amenitys as $property_amenity) {
            $data[] = ['id' => $property_amenity->id, 'text' => $property_amenity->name];
        }
        return json_encode($data);
    }
    
    function ajaxAmenity(Request $request){
         $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'status',
            3 => 'options',
        );
        $totalData = PropertyAmenity::where('business_type_id',auth()->user()->business->business_type_id)->count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if(empty($search))
        {
            $property_amenitys = PropertyAmenity::where('business_type_id',auth()->user()->business->business_type_id);
        }else{
            $property_amenitys = PropertyAmenity::where('business_type_id',auth()->user()->business->business_type_id)->where("name","LIKE","%{$search}%");

        }
        $totalFiltered = $property_amenitys->count();
        $property_amenitys = $property_amenitys->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($property_amenitys))
        {
            $i = $start == 0 ? 1 : $start+1;
            // $p_edit = can_p('property_amenity.edit');
            // $p_delete = can_p('property_amenity.delete');
            foreach($property_amenitys as $property_amenity)
            {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $property_amenity->name;
                $nestedData['options'] = '';
                // if($p_edit){
                    $nestedData['options'] .= '<a class="btn btn-primary amenity_data_edit me-2" href="javascript:void(0)" data-id="'.$property_amenity->id.'"><i class="bx bx-edit"></i></a>';
                // }

                // if($p_delete){
                    $nestedData['options'] .= '<a href="#" data-id="'.$property_amenity->id.'" class="amenity_del_data btn btn-danger"><i class="bx bx-trash"></i></a>';
                // }
                $data[] = $nestedData;

            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return json_encode($json_data);
    }
    public function store(Request $request)
    {
       
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('property_amenities')->where(function ($query) {
                        return $query->where('business_type_id', auth()->user()->business->business_type_id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('property_amenities')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_type_id', auth()->user()->business->business_type_id);
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
                $data=new PropertyAmenity();
            }
            else{
                $data=PropertyAmenity::find($request->id);
            }
            $data->name=$request->name;
            $data->business_type_id=auth()->user()->business->business_type_id;
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
                'msg' =>$e->getMessage(),
                'error' => 'Something went Wrong!',
            ]);
        }

    }
    public function edit(Request $request)
    {
        
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=PropertyAmenity::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name]);
    }
    public function destroy(Request $request,$id)
    {
       
        try{
            DB::beginTransaction();
            $data=PropertyAmenity::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('property_amenity.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('property_amenity.index')->with($notification);
        }

    }
}