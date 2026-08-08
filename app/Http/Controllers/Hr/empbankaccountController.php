<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\NewEmployee;
use App\Models\Hr\Bank;
use App\Models\Hr\EmpBankAccount;
use App\Models\Hr\Employee;
use App\User;

use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class empbankaccountController extends Controller
{
    public function view(){
        $data['bankaccounts']=EmpBankAccount::orderBy('emp_bank_accounts.id','DESC')
                              ->get();
        $data['banks']=Bank::orderBy('id','DESC')->get();
        $data['departments']=Department::orderBy('id','DESC')->get();
        return view ('Hr.empbankAccount.manage',$data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'empID'=>'required',
            'bankID'=>'required',
            'acName'=>'required',
            'branchName'=>'required',
            'acType'=>'required',
            'acNumber'=>'required',
            'routingNumber'=>'required',
        ],[

            'empID.required'=> 'Employee is required',
            'acType.required'=> 'Account Type is required',
            'acNumber.required'=> 'Account Number is required',
            'routingNumber.required'=> 'Routing Number is required',
            'bankID.required'=> 'Bank is required',
            'acName.required'=> 'Account Name is required',
            'branchName.required'=> 'Branch Name is required',
        ]);
        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();

            if($request->id==0){
                $data=new EmpBankAccount();
            }
            else{
                $data=EmpBankAccount::find($request->id);
            }
            $data->bankID=$request->bankID;
            $data->acName=$request->acName;
            $data->branchName=$request->branchName;
            $data->acType=$request->acType;
            $data->acNumber=$request->acNumber;
            $data->routingNumber=$request->routingNumber;
            $data->empID=$request->empID;
            $data->save();

            DB::commit();
            if($request->id==0){
                return response([
                    'status' => 1,
                    'success' => 'Employee Account add successfully.',
                ]);
            }else{
                return response([
                    'status' => 1,
                    'success' => 'Employee Account update successfully.',
                ]);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'error' => 'Something went Wrong!',
            ]);
        }


        // $notification=array(
        //     'message'=>"Save Success",
        //     'alert-type'=>'success'
        // );

        // return redirect()->route('empbankaccount.view')->with($notification);
    }

    public function delete($id){

        $data=EmpBankAccount::find($id);
        $data->delete();

        $notification=array(
                'message'=>"Delete successfull",
                'alert-type'=>'success'
             );

            return redirect()->route('empbankaccount.view');

    }

    public function edit(Request $request){

        if (!$request->id) {
           $html ='Sorry';
        } else {

           $bankaccount=EmpBankAccount::find($request->id);

           $banks=Bank::orderBy('id','DESC')->get();


          $html='<option>-- Select One --</option>';

            foreach($banks as $bank){
                if($bank->id==$bankaccount->bankID){
                    $html.='<option value="'.$bank->id.'" selected>'.$bank->name.'</option>';
                }
                else{
                    $html.='<option value="'.$bank->id.'">'.$bank->name.'</option>';
                }


            }



           $this_emp=Employee::where('id',$bankaccount->empID)->first();
           $departments=Department::orderBy('id','DESC')->get();
           $designations=Designation::where('department_id',$this_emp->department_id)->get();


           //department select
          $deptIDbank='<option>-- Select One --</option>';

            foreach($departments as $department){
                if($department->id==$this_emp->department_id){
                    $deptIDbank.='<option value="'.$department->id.'" selected>'.$department->name.'</option>';
                }
                else{
                    $deptIDbank.='<option value="'.$department->id.'">'.$department->name.'</option>';
                }

            }

            //designation select
            $desigIDbank='<option>-- Select One --</option>';

            foreach($designations as $designation){
                if($designation->id==$this_emp->designation_id){
                    $desigIDbank.='<option value="'.$designation->id.'" selected>'.$designation->name.'</option>';
                }
                else{
                    $desigIDbank.='<option value="'.$designation->id.'">'.$designation->name.'</option>';
                }

            }

            $employees=Employee::where('designation_id',$this_emp->designation_id)->get();
           $empIDbank='<option>-- Select One --</option>';

            foreach($employees as $employee){

                if($employee->id==$this_emp->id){
                    $empIDbank.='<option value="'.$employee->id.'" selected>'.$employee->employee_id.'</option>';
                }else{
                    $empIDbank.='<option value="'.$employee->id.'">'.$employee->employee_id.'</option>';
                }


            }


        }

       return response()->json(['html' => $html,'empIDbank'=>$empIDbank,'desigIDbank'=>$desigIDbank,'deptIDbank'=>$deptIDbank,'bankaccountID'=>$bankaccount->id,'acName'=>$bankaccount->acName,'branchName'=>$bankaccount->branchName,'acType'=>$bankaccount->acType,'acNumber'=>$bankaccount->acNumber,'routingNumber'=>$bankaccount->routingNumber]);


    }
}
