<?php

namespace App\Http\Controllers\RealState;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MemberType;
use Illuminate\Validation\Rule;
class MemberTypeController extends Controller
{
     public function index()
    {
        // if(can_p('property_amenity.index') == false){
        //     return redirect()->route('dashboard');
        // }
        return view ('RealState.member_type.manage');
    }
    function select2MemberTypeList(Request $request){
        $member_types = MemberType::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($member_types as $member_type) {
            $data[] = ['id' => $member_type->id, 'text' => $member_type->name];
        }
        return json_encode($data);
    }
    
    function ajaxAmenity(Request $request){
         $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'options',
        );
        $totalData = MemberType::count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if(empty($search))
        {
            $member_types = MemberType::query();
        }else{
            $member_types = MemberType::where("name","LIKE","%{$search}%");

        }
        $totalFiltered = $member_types->count();
        $member_types = $member_types->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($member_types))
        {
            $i = $start == 0 ? 1 : $start+1;
            // $p_edit = can_p('property_amenity.edit');
            // $p_delete = can_p('property_amenity.delete');
            foreach($member_types as $member_type)
            {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $member_type->name;
                $nestedData['options'] = '';
                // if($p_edit){
                    $nestedData['options'] .= '<a class="btn btn-primary amenity_data_edit me-2" href="javascript:void(0)" data-id="'.$member_type->id.'"><i class="bx bx-edit"></i></a>';
                // }

                // if($p_delete){
                    $nestedData['options'] .= '<a href="#" data-id="'.$member_type->id.'" class="amenity_del_data btn btn-danger"><i class="bx bx-trash"></i></a>';
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
                    Rule::unique('member_types')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('member_types')->where(function ($query) use ($id) {
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
                $data=new MemberType();
            }
            else{
                $data=MemberType::find($request->id);
            }
            $data->name=$request->name;
            // $data->business_type_id=auth()->user()->business->business_type_id;
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

           $data=MemberType::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name]);
    }
    public function destroy(Request $request,$id)
    {
       
        try{
            DB::beginTransaction();
            $data=MemberType::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('member_type.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('member_type.index')->with($notification);
        }

    }
}