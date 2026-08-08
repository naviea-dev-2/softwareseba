<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        if(can_p('vendor.index') == false){
            return redirect()->route('dashboard');
        }
        return view ('Inventory.vendor.manage');
    }
    function ajaxVendor(Request $request){
        $columns = array(
           0 => 'vendors.id',
           1 => 'vendors.image',
           2 => 'vendors.name',
           3 => 'vendors.email',
           4 => 'vendors.mobile',
           5 => 'vendors.address',
           7 => 'countries.name',
           8 => 'states.name',
           8 => 'cities.name',
           8 => 'vendors.zip_code',
           9 => 'options',
        );
        $totalData = Vendor::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $vendors = Vendor::leftjoin('countries','countries.id','vendors.country_id')
                            ->leftjoin('states','states.id','vendors.state_id')
                            ->leftjoin('cities','cities.id','vendors.city_id');
        // if(auth()->user()->user_type != 0 && auth()->user()?->role?->is_admin != 1){
        //     $purchases->where('purchases.branch_id',auth()->user()->branch_id);
        // }
        if(!empty($search))
        {
            $vendors = $vendors->where("vendors.name","LIKE","%{$search}%")
                        ->orWhere("vendors.email","LIKE","%{$search}%")
                        ->orWhere("vendors.mobile","LIKE","%{$search}%")
                        ->orWhere("vendors.address","LIKE","%{$search}%")
                        ->orWhere("countries.name","LIKE","%{$search}%")
                        ->orWhere("states.name","LIKE","%{$search}%")
                        ->orWhere("cities.name","LIKE","%{$search}%");

        }
        $totalFiltered = $vendors->count();
        $vendors = $vendors->select('vendors.*','countries.name as country_name','states.name as state_name','cities.name as city_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $data = array();
        if(!empty($vendors))
        {
            $i = $start == 0 ? 1 : $start+1;
            $p_edit = can_p('vendor.edit');
            $p_delete = can_p('vendor.delete');
            foreach($vendors as $vendor)
            {
                $nestedData['id'] = $i++;

                $nestedData['img'] = '<img src="'.$vendor->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] =$vendor->name;
                $nestedData['email'] =$vendor->email;
                $nestedData['mobile'] =$vendor->mobile;
                $nestedData['address'] =$vendor->address;
                $nestedData['address'] =$vendor->address;
                $nestedData['cn_name'] =$vendor->country_name;
                $nestedData['s_name'] =$vendor->state_name;
                $nestedData['ct_name'] =$vendor->city_name;
                $nestedData['zip_code'] =$vendor->zip_code;
                $nestedData['options'] = '';
                if($p_edit){
                    $nestedData['options'] .= '<a class="btn btn-primary data_edit m-0" href="javascript:void(0)" data-id="'.$vendor->id.'" style="padding:5px"> <i class="bx bx-edit m-0 b-0"></i></a>';
                }
                if($p_delete){
                    $nestedData['options'] .= '<a href="#" data-token="'.csrf_token().'" data-id="'.$vendor->id.'" class="del_data btn btn-danger m-0" style="padding:5px"> <i class="bx bx-trash m-0 b-0"></i></a>';
                }
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
    function autoSearch(Request $request){
        $vendors = Vendor::where($request->field,'like','%'.$request->value.'%')->orderBy(DB::raw("INSTR($request->field, '%$request->value%')"),'DESC')->get();


        $field = $request->field;
        $data = view('Inventory.purchase.auto-seach',compact('vendors','field'))->render();
        if($vendors->isEmpty()){
            return response()->json(array('status'=> 'error','message'=> 'search is empty','data'=>$data));
        }
        $f_data = $vendors[0];
         return response()->json(array('status'=> 'success','f_data'=>$f_data,'data'=>$data));
    }
    function select2Vendors(Request $request){
         $vendors = Vendor::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($vendors as $vendor) {
            $data[] = ['id' => $vendor->id, 'text' => $vendor->name];
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
        if(can_p('vendor.add') == false){
            return redirect()->route('dashboard');
        }
        if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=> 'required',
                'mobile'=> 'required',
                'email'=>[
                    'required',
                    Rule::unique('vendors')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
                'image'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=> 'required',
                'mobile'=> 'required',
                'email'=>[
                    'required',
                    'email',
                    Rule::unique('vendors')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
                    }),
                ],
                'image'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
        }
        if($validator->fails()){
            return response([
                'status' => 0,
                'email',
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Vendor();
                $file=$request->file('image');
                if($file){
                    $filename=date('YmdHi')."_vendor.".$file->getClientOriginalName();
                    $file->move(public_path('upload/vendors'),$filename);
                    $data->image=$filename;
                }
            }
            else{
                $data=Vendor::find($request->id);
                $file=$request->file('image');
                //return public_path('upload/vendors');
                if($file){
                    @unlink(public_path('upload/vendors/'.$data->image));
                    $filename=date('YmdHi')."_vendor.".$file->getClientOriginalName();
                    $file->move(public_path('upload/vendors'),$filename);
                    $data->image=$filename;
                }
            }
            $data->name=$request->name;
            $data->email=$request->email ?? '';
            $data->mobile=$request->mobile ?? '';
            $data->address=$request->address ?? '';
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zip_code=$request->zip_code ?? '';
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
                'data'=> $e->getMessage(),
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
        if(can_p('vendor.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Vendor::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'name'=>$data->name,'email'=>$data->email,'mobile'=>$data->mobile,'address'=>$data->address,'country_name'=>$data->country?->name,'country_id'=>$data->country_id,'state_name'=>$data->state?->name,'state_id'=>$data->state_id,'city_name'=>$data->city?->name,'city_id'=>$data->city_id,'image'=>$data->image_show]);
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
        if(can_p('vendor.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=Vendor::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('vendor.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('vendor.index')->with($notification);
        }
    }
}
