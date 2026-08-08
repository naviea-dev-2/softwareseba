<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function index()
    {
       
        $data['cities']=City::orderBy('id','DESC')->get();
        return view ('admin.location.city.manage',$data);
    }
    function ajaxCity(Request $request){
        $columns = [
            'states.id',
            'cities.name',
            'states.name',
            'countries.name',
        ];

        $totalData = City::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = City::leftJoin('countries','countries.id','cities.country_id')
                    ->leftJoin('states','states.id','cities.state_id');
        if(!empty($search)){
           $datalist =$datalist->where("states.name","LIKE","%{$search}%")
                    ->where("countries.name","LIKE","%{$search}%")
                    ->where("cities.name","LIKE","%{$search}%");
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->select('cities.*','countries.name as c_name','states.name as s_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
           $i = $start == 0 ? 1 : $start+1;
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i;
                $nestedData['name'] = $data_v->name;
                $nestedData['c_name'] = $data_v->c_name;
                $nestedData['s_name'] = $data_v->s_name;

                $nestedData['options'] = '<a class="btn btn-primary data_edit" href="javascript:void(0)"  data-id="'.$data_v->id.'"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="#" data-id="'.$data_v->id.'" class="del_data btn btn-danger"><i class="bx bx-trash"></i></a>';
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data = [
           'draw' => intval($request->input('draw')),
           'recordsTotal' => intval($totalData),
           'recordsFiltered' => intval($totalFiltered),
           'data' => $data
        ];
   
        return response()->json($json_data);
    }
    function select2CitiesByState(Request $request){
        $cities = City::select('id', 'name')->where('state_id',$request->state_id)->where("name", "LIKE", "%$request->value%")->get();
        foreach ($cities as $city) {
            $data[] = ['id' => $city->id, 'text' => $city->name];
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
       //dd($request->all());
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'country'=>'required',
                'state'=>'required',
                'name'=>'required',
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'country'=>'required',
                'state'=>'required',
                'name'=>'required',
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
                $data=new City();
            }
            else{
                $data=City::find($request->id);
            }
            // dd($data);
            $data->name=$request->name;
            $data->country_id=$request->country;
            $data->state_id=$request->state;
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
            //dd($e->getMessage());
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
        
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=City::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'country_id'=>$data->country_id,'state_id'=>$data->state_id,'state_name'=>$data->state?->name,'country_name'=>$data->country?->name]);
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
       
        try{
            DB::beginTransaction();
            $data=City::find($id);
            if($data){
                $data->delete();
                DB::commit();
                $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
                );
    
                return redirect()->route('admin.city.index')->with($notification);
            }else{
                
                $notification=array(
                'message'=>"Not found",
                'alert-type'=>'error'
                );
    
                return redirect()->route('admin.city.index')->with($notification);
            }
           

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('admin.city.index')->with($notification);
        }
    }
}
