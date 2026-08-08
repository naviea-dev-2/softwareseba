<?php

namespace App\Http\Controllers\RealState;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('RealState.member.manage');
    }
    function select2MemberList(Request $request){
        $members = Member::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($members as $member) {
            $data[] = ['id' => $member->id, 'text' => $member->name];
        }
        return json_encode($data);
    }
    function ajaxMember(Request $request){
        $columns = array(
            0 => 'members.id',
            1 => 'members.image',
            2 => 'members.name',
            3 => 'members.email',
            4 => 'members.mobile',
            5 => 'countries.name',
            6 => 'states.name',
            7 => 'cities.name',
            8 => 'members.zipcode',
            9 => 'members.address',
            10 => 'options',
        );
        $totalData = Member::count();


        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        if(empty($search))
        {
            $members = Member::leftJoin("countries","countries.id","members.country_id")
            ->leftJoin("states","states.id","members.state_id")
            ->leftJoin("cities","cities.id","members.city_id");
        }else{
            $members = Member::leftJoin("countries","countries.id","members.country_id")
            ->leftJoin("states","states.id","members.state_id")
            ->leftJoin("cities","cities.id","members.city_id")
            ->where(function($q){
                $q->where("members.name","like","%{$search}%")
                ->orWhere("members.email","like","%{$search}%")
                ->orWhere("members.mobile","like","%{$search}%")
                ->orWhere("countries.name","like","%{$search}%")
                ->orWhere("states.name","like","%{$search}%")
                ->orWhere("cities.name","like","%{$search}%")
                ->orWhere("members.zipcode","like","%{$search}%")
                ->orWhere("members.address","like","%{$search}%");
            });
        }
        $totalFiltered = $members->count();
        $members = $members->select("members.*","countries.name as country_name","states.name as state_name","cities.name as city_name")->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        $data = array();
        if(!empty($members))
        {
            $i = $start == 0 ? 1 : $start+1;
           
            foreach($members as $member)
            {
                $nestedData['id'] = $i++;

                $nestedData['image'] = '<img src="'.$member->image_show.'" style="height:50px;width:50px;">';
                $nestedData['name'] = $member->name;
                $nestedData['email'] = $member->email;
                $nestedData['mobile'] = $member->mobile;
                $nestedData['country'] = $member->country_name;
                $nestedData['state'] = $member->state_name;
                $nestedData['city'] = $member->city_name;
                $nestedData['zipcode'] = $member->zipcode;
                $nestedData['address'] = $member->address;
                $nestedData['options'] = '';

                $nestedData['options'] .= '<a href="'.route('member.edit',$member->id).'" class="btn btn-primary member_data_edit me-2"><i class="bx bx-edit"></i></a>';
                $nestedData['options'] .= '<a href="javascript:void(0)" data-id="'.$member->id.'" class="btn btn-danger member_del_data"><i class="bx bx-trash"></i></a>';

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
        return view ('RealState.member.create');
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('members')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
            'mobile'=>[
                'required',
                Rule::unique('members')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],
            'image'=>'image|mimes:jpeg,png,jpg,webp',
        ]);

         try{
            DB::beginTransaction();
            
            $data=new Member();
            $filename = null;
            $filename1 = null;
            $memberPath = public_path('upload/member');
            $userPath = public_path('upload/business_user');
            if ($request->hasFile('image')) {
                $filename=date('YmdHi')."_member".$request->file('image')->getClientOriginalName();
                $filename1=date('YmdHi')."_business_user".$request->file('image')->getClientOriginalName();
                $request->file('image')->move($memberPath,$filename);
                $data->image=$filename;
            }

           
            $data->member_type_id=$request->member_type;
            $data->name=$request->name;
            $data->email=$request->email ?? '';
            $data->mobile=$request->mobile ?? "";
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zipcode=$request->zip_code ?? '';
            $data->address=$request->address ?? '';
            $data->save();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            if ($filename) {
                copy("$memberPath/$filename", "$userPath/$filename1");
                $user->image=$filename1;
            }
            $user->business_id = auth()->user()->business->id;
            $user->member_id = $data->id;
            $user->user_type = 1;
            $user->status = 1;
            $user->password =  Hash::make(123456789);
            $user->save();

            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('member.index')->with($notification);
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
        $data['member'] = Member::find($id);
        return view ('RealState.member.edit',$data);
    }
    function editMember($id){
        // $user = User::find($id);
        $data['member'] = Member::find($id);
        return  view('RealState.member.user_edit',$data);
        // return  view('user.edit',$data);
    }
    public function updateMember(Request $request,$id)
    {
        // dd($request->all());
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('members')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
               
            ],
            'mobile'=>[
                'required',
                Rule::unique('members')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
            ],
            'image'=>'image|mimes:jpeg,png,jpg,webp',
        ]);

         try{
            DB::beginTransaction();
            
            $data=Member::find($request->id);
            $filename = null;
            $filename1 = null;
            $memberPath = public_path('upload/member');
            $userPath = public_path('upload/business_user');
            if ($request->hasFile('image')) {
                @unlink(public_path('upload/member/'.$data->image));
                $filename=date('YmdHi')."_member".$request->file('image')->getClientOriginalName();
                $filename1=date('YmdHi')."_business_user".$request->file('image')->getClientOriginalName();
                $request->file('image')->move($memberPath,$filename);
                $data->image=$filename;
            }
            
            $data->member_type_id=$request->member_type;
            $data->name=$request->name;
            $data->email=$request->email ?? '';
            $data->mobile=$request->mobile ?? "";
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zipcode=$request->zip_code ?? '';
            $data->address=$request->address ?? '';
            $data->save();

            $user = User::where("member_id",$data->id)->first();
            if($user == null){
                $user = new User();
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            if ($filename) {
                @unlink(public_path('upload/business_user/'.$user->image));
                copy("$memberPath/$filename", "$userPath/$filename1");
                $user->image=$filename1;
            }
            $user->save();
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('user.member.edit',$data->id)->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
             $notification=array(
                'message'=>"Save Failed",
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification)->withInput($request->all());
        }
    }
    public function update(Request $request,$id)
    {
        //dd($request->all());
        $this->validate($request,[
            'name'=>[
                'required',
                Rule::unique('members')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
               
            ],
            'mobile'=>[
                'required',
                Rule::unique('members')->where(function ($query) use ($id) {
                    return $query->where('id', '!=', $id)
                        ->where('business_id', auth()->user()->business->id);
                }),
            ],
            'image'=>'image|mimes:jpeg,png,jpg,webp',
        ]);

         try{
            DB::beginTransaction();
            
            $data=Member::find($request->id);
            $filename = null;
            $filename1 = null;
            $memberPath = public_path('upload/member');
            $userPath = public_path('upload/business_user');
            if ($request->hasFile('image')) {
                @unlink(public_path('upload/member/'.$data->image));
                $filename=date('YmdHi')."_member".$request->file('image')->getClientOriginalName();
                $filename1=date('YmdHi')."_business_user".$request->file('image')->getClientOriginalName();
                $request->file('image')->move($memberPath,$filename);
                $data->image=$filename;
            }
            $data->member_type_id=$request->member_type;
            $data->name=$request->name;
            $data->email=$request->email ?? '';
            $data->mobile=$request->mobile ?? "";
            $data->country_id=$request->country ?? 0;
            $data->state_id=$request->state ?? 0;
            $data->city_id=$request->city ?? 0;
            $data->zipcode=$request->zip_code ?? '';
            $data->address=$request->address ?? '';
            $data->save();

            $user = User::where("member_id",$data->id)->first();
            if($user == null){
                $user = new User();
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            if ($filename) {
                @unlink(public_path('upload/business_user/'.$user->image));
                copy("$memberPath/$filename", "$userPath/$filename1");
                $user->image=$filename1;
            }
            
            // $user->business_id = auth()->user()->business->id;
            // $user->member_id = $data->id;
            // $user->user_type = 1;
            // $user->status = 1;
            // $user->password =  Hash::make(123456789);
            $user->save();
            DB::commit();
            $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

            return redirect()->route('member.index')->with($notification);
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
            $data=Member::find($id);
            @unlink(public_path('upload/member/'.$data->image));
            $user = User::where("member_id",$data->id)->first();
            if($user){
                @unlink(public_path('upload/business_user/'.$user->image));
                $user->delete();
            }
            $data->delete();
            DB::commit();
            $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
            );

            return redirect()->route('member.index')->with($notification);

        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('member.index')->with($notification);
        }

    }

}