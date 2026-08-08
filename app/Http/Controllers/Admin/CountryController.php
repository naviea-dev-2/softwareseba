<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index()
    {
        return view ('admin.location.country.manage');
    }
    function ajaxCountry(Request $request){
        $columns = [
            'id',
            'name',
            'country_code',
        ];

        $totalData = Country::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $datalist = Country::query();
        if(!empty($search)){
           $datalist =$datalist->where("name","LIKE","%{$search}%")
                    ->where("country_code","LIKE","%{$search}%");  
        }
       
        $totalFiltered = $datalist->count();
        $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($datalist))
        {
           $i = $start == 0 ? 1 : $start+1;
            foreach ($datalist as $data_v) {
                $nestedData['id'] = $i;
                $nestedData['name'] = $data_v->name;
                $nestedData['country_code'] = $data_v->country_code;

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
        
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>'required',
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
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
                $data=new Country();
            }
            else{
                $data=Country::find($request->id);
            }
            $data->name=$request->name;
            $data->country_code=$request->code;
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
       
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Country::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'country_code'=>$data->country_code]);
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
            $data=Country::find($id);
           
            if($data){
                $data->delete();
                DB::commit();
                $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
                );

                return redirect()->route('admin.country.index')->with($notification);
            }else{
                    
                $notification=array(
                'message'=>"Not found",
                'alert-type'=>'error'
                );

                return redirect()->route('admin.country.index')->with($notification);
            }

          
        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('country.index')->with($notification);
        }
    }
    function select2Countries(Request $request){
        $countries = Country::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($countries as $country) {
            $data[] = ['id' => $country->id, 'text' => $country->name];
        }
        return json_encode($data);
    }
}
