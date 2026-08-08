<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(can_p('branch.index') == false){
            return redirect()->route('dashboard');
        }
        $data['branches']=Branch::orderBy('id','DESC')->get();
        return view ('Inventory.branch.manage',$data);
    }
    function select2BranchList(Request $request){
        $branches = Branch::select('id', 'name')->where("name", "LIKE", "%$request->value%")->get();
        foreach ($branches as $branch) {
            $data[] = ['id' => $branch->id, 'text' => $branch->name];
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
        if(can_p('branch.add') == false){
            return redirect()->route('dashboard');
        }
       if($request->id==0){
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('branches')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'name'=>[
                    'required',
                    Rule::unique('branches')->where(function ($query) use ($id) {
                        return $query->where('id', '!=', $id)
                            ->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }
        try{
            DB::beginTransaction();
            if($request->id==0){
                $data=new Branch();
            }
            else{
                $data=Branch::find($request->id);
            }
            $data->name=$request->name;
            $data->email=$request->email;
            $data->mobile=$request->mobile;
            $data->address=$request->address;
            $data->is_primary=$request->type;
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
        if(can_p('branch.edit') == false){
            return redirect()->route('dashboard');
        }
        if (!$request->id) {
           $html ='Sorry';
        } else {

           $data=Branch::find($request->id);

          $html='';

        }

        return response()->json(['html' => $html,'id'=>$data->id,'type'=>$data->is_primary,'name'=>$data->name,'email'=>$data->email,'mobile'=>$data->mobile,'address'=>$data->address]);
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
        if(can_p('branch.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=Branch::find($id);
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('branch.index')->with($notification);
        }catch(\Exception $e){
            DB::rollBack();
            $notification=array(
                'message'=>"Something went wrong!",
                'alert-type'=>'error'
            );

            return redirect()->route('branch.index')->with($notification);
        }

    }
}
