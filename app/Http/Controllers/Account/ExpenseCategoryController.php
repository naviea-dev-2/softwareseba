<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Account\AccountHead;
use App\Models\Account\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    function index(){
        if(can_p('expense_category.index') == false){
            return redirect()->route('dashboard');
        }
         $data['categories']=ExpenseCategory::orderBy('id','DESC')->get();
        return view ('Accounts.expense_category.index',$data);
    }
    function init_account(){
        $d_Ex = AccountHead::where("code",'6000')->first();
        if($d_Ex == null){
            $d_Ex = new AccountHead;
            $d_Ex->title = "Direct Expense";
            $d_Ex->code = '6000';
            $d_Ex->sys = 0;
            $d_Ex->ac_type = 6;
            $d_Ex->note = '';
            $d_Ex->status = 1;
            $d_Ex->save();
        }
        $d_Ex = AccountHead::where("code",'7000')->first();
        if($d_Ex == null){
            $d_Ex = new AccountHead;
            $d_Ex->title = "Indirect Expense";
            $d_Ex->code = '7000';
            $d_Ex->sys = 0;
            $d_Ex->ac_type =7;
            $d_Ex->note = '';
            $d_Ex->status = 1;
            $d_Ex->save();
        }
    }

    function store(Request $request){
        if($request->id == 0){
            if(can_p('expense_category.add') == false){
                return response([
                    'status' => 0,
                    'error' => 'Add permission is not allowed',
                ]);
            }
        }else{
            if(can_p('expense_category.edit') == false){
                return response([
                    'status' => 0,
                    'error' => 'Edit permission is not allowed',
                ]);
            }
        }
        if($request->id==0){
            
            $validator = Validator::make($request->all(),[
                'expense_type'=>'required',
                'name'=>[
                    'required',
                    Rule::unique('expense_categories')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business->id);
                    }),
                ],
            ]);
        }else{
            $id = $request->id;
            $validator = Validator::make($request->all(),[
                'expense_type'=>'required',
                'name'=>[
                    'required',
                    Rule::unique('expense_categories')->where(function ($query) use ($id) {
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
                $data=new ExpenseCategory();
            }
            else{
                $data=ExpenseCategory::find($request->id);
            }
            $data->name=$request->name;
            $data->type=$request->expense_type;
            $data->save();

            $cap_head =  AccountHead::where('expense_id',$data->id)->first();
            if($cap_head == null){
                $cap_head = new AccountHead;
            }
            $cap_head->code = '';
            $cap_head->title = $request->name;
            $cap_head->ac_type = $request->expense_type == 1 ? 6 : 7;
            $cap_head->note = '';
            $cap_head->sys = 0;
            $cap_head->parent = 0;
            $cap_head->expense_id = $data->id;
            $cap_head->status = 1;
            $cap_head->save();
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
    public function edit(Request $request)
    {
        if(can_p('expense_category.edit') == false){
            return response([
                'status' => 0,
                'msg' => 'Edit permission is not allowed',
            ]);
        }
        if (!$request->id) {
            return response([
                'status' => 0,
                'msg' => 'Not Found',
            ]);
        } else {

           $data=ExpenseCategory::find($request->id);

          $html='';

        }

        return response()->json(['status'=>1,'html' => $html,'id'=>$data->id,'name'=>$data->name,'code'=>$data->code,'type'=>$data->type]);
    }
    public function delete(Request $request,$id)
    {
        if(can_p('expense_category.delete') == false){
            return redirect()->route('dashboard');
        }
        try{
            DB::beginTransaction();
            $data=ExpenseCategory::find($id);
            $cap_head =  AccountHead::where('expense_id',$data->id)->first();
            $cap_head->delete();
            $data->delete();
            DB::commit();
            $notification=array(
            'message'=>"Delete successfull",
            'alert-type'=>'success'
            );

            return redirect()->route('expense_category.index')->with($notification);
         }catch(\Exception $e){
            DB::rollBack();
            // dd($e->getMessage());
            $notification=array(
            'message'=>"Can not Delete This",
            'alert-type'=>'error'
            );
             return redirect()->route('expense_category.index')->with($notification);

        }
    }
}
