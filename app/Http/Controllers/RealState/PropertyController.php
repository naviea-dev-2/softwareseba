<?php

namespace App\Http\Controllers\RealState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Property;
class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('RealState.property.manage');
    }
    function select2PropertyList(Request $request){
        $properties = Property::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($properties as $property) {
            $data[] = ['id' => $property->id, 'text' => $property->name];
        }
        return json_encode($data);
    }
    function ajaxProperty(Request $request){
        $columns = array(
            0 => 'properties.id',
            1 => 'properties.thumb_image',
            2 => 'properties.name',
            3 => 'properties.price',
            4 => 'countries.name',
            5 => 'states.name',
            6 => 'cities.name',
            7 => 'properties.zipcode',
            8 => 'properties.address',
            9 => 'options',
        );
        $totalData = Property::count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if(empty($search))
        {
            $properties = Property::leftJoin("countries","countries.id","properties.country_id")
            ->leftJoin("states","states.id","properties.state_id")
            ->leftJoin("cities","cities.id","properties.city_id");
        }else{
            $properties = Member::leftJoin("countries","countries.id","properties.country_id")
            ->leftJoin("states","states.id","properties.state_id")
            ->leftJoin("cities","cities.id","properties.city_id")
            ->where(function($q){
                $q->where("properties.name","like","%{$search}%")
                ->orWhere("countries.name","like","%{$search}%")
                ->orWhere("states.name","like","%{$search}%")
                ->orWhere("cities.name","like","%{$search}%")
                ->orWhere("properties.zipcode","like","%{$search}%")
                ->orWhere("properties.price","like","%{$search}%")
                ->orWhere("properties.address","like","%{$search}%");
            });

        }
        $totalFiltered = $properties->count();
        $properties = $properties->select("properties.*","countries.name as country_name","states.name as state_name","cities.name as city_name")->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($properties))
        {
            $i = $start == 0 ? 1 : $start+1;
            foreach($properties as $property)
            {
                $nestedData['id'] = $i++;

                $nestedData['image'] = '<img src="'.$property->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] = $property->name;
                $nestedData['price'] = $property->price;
                $nestedData['country'] = $property->country_name;
                $nestedData['state'] = $property->state_name;
                $nestedData['city'] = $property->city_name;
                $nestedData['zipcode'] = $property->zipcode;
                $nestedData['address'] = $property->address;
                $nestedData['options'] = '';

                $nestedData['options'] .= '<a href="'.route('property.edit',$property->id).'" class="btn btn-primary property_data_edit me-2"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="javascript:void(0)" data-id="'.$property->id.'" class="btn btn-danger property_del_data"><i class="bx bx-trash"></i></a>';

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
    public function indexUser()
    {
        return view ('RealState.property.manage_user');
    }
    function ajaxPropertyUser(Request $request){
        $columns = array(
            0 => 'properties.id',
            1 => 'properties.thumb_image',
            2 => 'properties.name',
            3 => 'properties.price',
            4 => 'countries.name',
            5 => 'states.name',
            6 => 'cities.name',
            7 => 'properties.zipcode',
            8 => 'properties.address',
        );
        $totalData = Property::count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $user = auth()->user();
        if(empty($search))
        {
            $properties = Property::leftJoin("countries","countries.id","properties.country_id")
            ->leftJoin("states","states.id","properties.state_id")
            ->leftJoin("cities","cities.id","properties.city_id")
            ->whereHas('deposits', function ($q) use ($user) {
                $q->where('member_id', $user->member_id);
            });
        }else{
            $properties = Member::leftJoin("countries","countries.id","properties.country_id")
            ->leftJoin("states","states.id","properties.state_id")
            ->leftJoin("cities","cities.id","properties.city_id")
             ->whereHas('deposits', function ($q) use ($user) {
                $q->where('member_id', $user->member_id);
            })
            ->where(function($q){
                $q->where("properties.name","like","%{$search}%")
                ->orWhere("countries.name","like","%{$search}%")
                ->orWhere("states.name","like","%{$search}%")
                ->orWhere("cities.name","like","%{$search}%")
                ->orWhere("properties.zipcode","like","%{$search}%")
                ->orWhere("properties.price","like","%{$search}%")
                ->orWhere("properties.address","like","%{$search}%");
            });

        }
        $totalFiltered = $properties->count();
        $properties = $properties->select("properties.*","countries.name as country_name","states.name as state_name","cities.name as city_name")->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($properties))
        {
            $i = $start == 0 ? 1 : $start+1;
            foreach($properties as $property)
            {
                $nestedData['id'] = $i++;

                $nestedData['image'] = '<img src="'.$property->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] = $property->name;
                $nestedData['price'] = $property->price;
                $nestedData['country'] = $property->country_name;
                $nestedData['state'] = $property->state_name;
                $nestedData['city'] = $property->city_name;
                $nestedData['zipcode'] = $property->zipcode;
                $nestedData['address'] = $property->address;
                $nestedData['options'] = '';

                $nestedData['options'] .= '<a href="'.route('property.edit',$property->id).'" class="btn btn-primary property_data_edit me-2"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="javascript:void(0)" data-id="'.$property->id.'" class="btn btn-danger property_del_data"><i class="bx bx-trash"></i></a>';

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
    public function create()
    {
        return view ('RealState.property.create');
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('properties')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
            'country'=>['required'],
            'state'=>['required'],
            'city'=>['required'],
            'zip_code'=>['required'],
            'address'=>['required'],
            'thumbnail'=>'image|mimes:jpeg,png,jpg,webp',
        ]);

         try{
            DB::beginTransaction();
            
            $data=new Property();
            $file=$request->file('thumbnail');
            if($file){
                $filename=date('YmdHi')."_thumb_image".$file->getClientOriginalName();
                $file->move(public_path('upload/property'),$filename);
                $data->thumb_image=$filename;
            }
            $data->name=$request->name;
            $data->description=$request->description ?? '';
            $data->price=$request->price ?? 0;
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zipcode=$request->zip_code ?? '';
            $data->address=$request->address ?? '';
            $data->save();
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('property.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
             $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    public function edit($id)
    {
        $data['property'] = Property::find($id);
        return view ('RealState.property.edit',$data);
    }
    public function update(Request $request,$id)
    {
        //dd($request->all());
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('properties')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
            ],
            'thumbnail'=>'image|mimes:jpeg,png,jpg,webp',
            'country'=>['required'],
            'state'=>['required'],
            'city'=>['required'],
            'zip_code'=>['required'],
            'address'=>['required'],
        ]);

         try{
            DB::beginTransaction();
            
            $data=Property::find($request->id);
            $file=$request->file('thumbnail');
            if($file){
                @unlink(public_path('upload/property/'.$data->thumb_image));
                $filename=date('YmdHi')."_thumb_image".$file->getClientOriginalName();
                $file->move(public_path('upload/property'),$filename);
                $data->thumb_image=$filename;
            }
            $data->name=$request->name;
            $data->description=$request->description ?? '';
            $data->price=$request->price ?? 0;
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zipcode=$request->zip_code ?? '';
            $data->address=$request->address ?? '';
            $data->save();
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('property.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
             $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    public function destroy(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $data=Property::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );
            return redirect()->route('property.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );
            return redirect()->route('property.index')->with($notification);
        }

    }
}