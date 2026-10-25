<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\SalaryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalaryHeadController extends Controller
{
    public function index()
    {
        return view("Hr.salary_head.manage");
    }

    function ajaxSalaryHead(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'type',
            3 => 'position',
            4 => 'status',
        );
        $totalData = SalaryHead::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        //dd($request->input('order.0.column'));
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $datalist = SalaryHead::query();
        if(!empty($search)){

            $datalist =$datalist->where("name","LIKE","%{$search}%")
            ->orwhere("type","LIKE","%{$search}%");

        }

        $totalFiltered = $datalist->count();
         $datalist = $datalist->offset($start)->limit($limit)->orderBy($order,$dir)->orderBy('position','asc')->get();


        $data = array();
        if(!empty($datalist))
        {
             $i = $start == 0 ? 1 : $start+1;
            foreach($datalist as $data_v)
            {
                $nestedData['id'] = $i++;
                $nestedData['name'] = $data_v->name;
                $nestedData['type'] = $data_v->type;
                $nestedData['position'] = $data_v->position;


                $nestedData['status'] = '';
                if ($data_v->status == 0) {
                    $nestedData['status'] .= '<a href="'.route('school.salary_head.status', $data_v->id).'" class="data_status btn btn-sm btn-warning">Inactive</a>';
                } elseif ($data_v->status == 1) {
                    $nestedData['status'] .= '<a href="'.route('school.salary_head.status', $data_v->id).'" class="data_status btn btn-sm btn-success">Active</a>';
                }
                 

                $nestedData['options'] = '<a class="p-1 px-2 btn btn-primary custom_data_edit" href="'.route('school.salary_head.edit', $data_v->id).'"><i class="bx bx-edit"></i></a>';

                $nestedData['options'] .= '<button class="p-1 px-2 btn btn-danger ms-2 dataDeleteModal"  value="'.$data_v->id.'"><i class="bx bx-trash"></i></button>';

                $data[] = $nestedData;

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
    // public function create()
    // {
    //     return view("school.designation.create");
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sort' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        $duplicate = SalaryHead::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->name))])->first();
        if($duplicate){
            return response()->json([
                'status'=>'no',
                'msg'=>'This salary head already exists'
            ]);
        }
        try{
            DB::beginTransaction();
            $salary_head = New SalaryHead;
            $salary_head->name = $request->name;
            $salary_head->type = $request->type;
            $salary_head->position = $request->sort;
            $salary_head->is_basic = $request->is_basic ? 1 : 0;
            $salary_head->save();

            if($salary_head->is_basic){
                SalaryHead::where('id','!=',$salary_head->id)->update(['is_basic'=>0]);
            }

            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'Salary Head Add Successfully'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            // dd($e);
            return response()->json([
                'status'=>'no',
                'msg'=>"Something went wrong!"
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
    public function edit(string $id)
    {
        try{
            $data["salary_head"]= SalaryHead::find($id);
            return view("Hr.salary_head.update",$data);
        }catch(\Exception $e){
            return back()->with ('error_message', "Something went wrong!");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sort' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()->all()
            ]);
        }
        $duplicate = SalaryHead::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($request->name))])->where('id','!=',$id)->first();
        if($duplicate){
            return response()->json([
                'status'=>'no',
                'msg'=>'This salary head already exists'
            ]);
        }
        try{
            DB::beginTransaction();
            $salary_head = SalaryHead::find($id);
            $salary_head->name = $request->name;
            $salary_head->type = $request->type;
            $salary_head->position = $request->sort;
            $salary_head->is_basic = $request->is_basic ? 1 : 0;
            $salary_head->save();

            if($salary_head->is_basic){
                SalaryHead::where('id','!=',$salary_head->id)->update(['is_basic'=>0]);
            }

            DB::commit();
            return response()->json([
                'status'=>'yes',
                'msg'=>'Salary Head Update Successfully'
            ]);
        }catch(\Exception $e){
            DB::rollBack();
        // dd($e);
        return response()->json([
            'status'=>'no',
            'msg'=>"Something went wrong!"
        ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */


    public function delete(Request $request)
    {
        //dd($request);
        try{
            $salary_head =  SalaryHead::find($request->designation_id);
            $salary_head->delete();

            return response()->json([
                'status'=>'yes',
                'msg'=>'Salary Head Deleted Successfully'
            ]);
        }catch(\Exception $e){
            //DB::rollBack();
            return response()->json([
                'status'=>'no',
                'msg'=>"Something went wrong!"
            ]);
        }
    }


    public function updateStatus($id)
    {
        try{
            $salary_head = SalaryHead::find($id);
            if ($salary_head) {
                if ($salary_head->status == 0) {
                    $salary_head->status = 1;
                } elseif ($salary_head->status == 1) {
                    $salary_head->status = 0;
                }
                $salary_head->update();

                $statusMessage = $salary_head->status == 1 ? 'Activated Successfully' : 'Deactivated Successfully';

                return response()->json([
                    'status'=>'yes',
                    'msg'=>$statusMessage
                ]);
            }else{
                return response()->json([
                    'status'=>'no',
                    'msg'=>'Salary Head not found'
                ]);
            }
        }catch(\Exception $e){
            //DB::rollBack();
            return response()->json([
                'status'=>'no',
                'msg'=>"Something went wrong!"
            ]);
        }

        
    }
}
