<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\Transaction;
use App\Models\Account\PaymentMethod;
use App\Models\Account\BalanceAccount;
use App\Models\Account\AccountSubHead;
use App\Models\Account\AccountHead;
use App\Models\Account\AccountTransaction;
use App\Models\Account\TransactionList;
use App\Models\Account\Siteinfo;
use App\Models\Account\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = AccountTransaction::get();
        return view('Accounts.transaction.manage', compact('transactions'));
    }
    function expense(Request $request){
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $data['expense_list']= $expense_list=Expense::leftJoin('expense_categories','expense_categories.id','expenses.category_id')
        ->leftJoin('payment_methods','payment_methods.id','expenses.method_id')
        ->whereBetween('expenses.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->select('expense_categories.name','expenses.reason',"expenses.date","expenses.amount",'payment_methods.name as method_name')
        ->get();
        return view('Accounts.transaction.expense_report',$data);
    }
    function balanceSheet(Request $request){
        if(can_p('balance_sheet') == false){
            return redirect()->route('dashboard');
        }
         DB::statement("SET SQL_MODE=''");
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;

        $profit_trans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[4,5])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
       // ->groupBy('account_transactions.account_id')
        ->get();
        $indirect_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[7])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        // ->groupBy('account_transactions.account_id')
        ->get();
        $direct_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[6])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        // ->groupBy('account_transactions.account_id')
        ->get();

       $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[1,2,3])
        ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->orderBy("account_heads.ac_type",'desc')

        ->get();
        $data['profit_trans']=$profit_trans;
        $data['indirect_tans']=$indirect_tans;
        $data['direct_tans']=$direct_tans;
        $data['transactions']=$transactions;
        return view('Accounts.transaction.balance_sheet',$data);
    }
    function ledgerSummary(Request $request){
        if(can_p('ledger_summary') == false){
            return redirect()->route('dashboard');
        }
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        if($request->ledger_account_id){
            $account_id = $request->ledger_account_id;
        }else{
            $account_id =0;
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['account_id']=$account_id;
        $data['transactions'] = AccountTransaction::where('account_id',$request->ledger_account_id)->whereBetween('date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])->get();
        $data['account'] = AccountHead::find($request->ledger_account_id);
        return view('Accounts.transaction.ledger_summary',$data);
    }
    function trailBalance(Request $request){
        if(can_p('trail_balance') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereBetween('account_transactions.date',  [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        // dd($transactions);
        $data['transactions']=$transactions;
        return view('Accounts.transaction.trail_balance', $data);
    }
    function profitLoss(Request $request){
        if(can_p('profit_loss') == false){
            return redirect()->route('dashboard');
        }
        DB::statement("SET SQL_MODE=''");
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[4,5])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        $indirect_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[7])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        $direct_tans = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->select('account_heads.title as account_name','account_heads.code as account_code','account_heads.ac_type as type','account_transactions.date',DB::raw('sum(IF(account_transactions.type="credit",account_transactions.amount,-1*account_transactions.amount)) as b_acount'))
        ->whereIn('account_heads.ac_type',[6])
       ->whereBetween('account_transactions.date', [Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()])
        ->groupBy('account_transactions.account_id')
        ->get();
        //dd($transactions);
      //  dd(Carbon::parse($from_date)->startOfDay());
        $data['transactions']=$transactions;
        $data['indirect_tans']=$indirect_tans;
        $data['direct_tans']=$direct_tans;
        return view('Accounts.transaction.profit_loss', $data);
    }
    function transaction(Request $request){
        if(can_p('account.report.transaction') == false){
            return redirect()->route('dashboard');
        }
        if($request->from_date){
            $from_date = $request->from_date;
        }else{
            $from_date = date('Y-m-d');
        }
        if($request->to_date){
            $to_date = $request->to_date;
        }else{
            $to_date = date('Y-m-d');
        }
        $transactions = AccountTransaction::leftJoin('account_heads','account_heads.id','account_transactions.account_id')
        ->leftJoin('vouchers','vouchers.id','account_transactions.relation_id')
        ->where('account_transactions.relation_with','Voucher')
        ->whereBetween('vouchers.voucher_date',[Carbon::parse($from_date)->startOfDay(), Carbon::parse($to_date)->endOfDay()]);
        if($request->v_type){
            $transactions = $transactions->where('vouchers.v_type',$request->v_type);
        }
        // $total_ledgers = $reports->select(DB::raw('sum(ledger_transactions.debit_amount) as d_amount,sum(ledger_transactions.credit_amount) as c_amount'))->first();

        $transactions = $transactions->select('vouchers.*','account_heads.title as l_name','account_transactions.amount','account_transactions.type')
        ->paginate($request->per_page);
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['v_type']=$request->v_type;
        $data['transactions']=$transactions;
        return view('Accounts.transaction.transaction', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function readNumber($num, $depth=0)
    {
        $num = (int)$num;
        $retval ="";
        if ($num < 0) // if it's any other negative, just flip it and call again
            return "negative " + $this->readNumber(-$num, 0);
        if ($num > 99) // 100 and above
        {
            if ($num > 999) // 1000 and higher
                $retval .= $this->readNumber($num/1000, $depth+3);

            $num %= 1000; // now we just need the last three digits
            if ($num > 99) // as long as the first digit is not zero
                $retval .= $this->readNumber($num/100, 2)." Hundred\n";
            $retval .=$this->readNumber($num%100, 1); // our last two digits
        }

        else // from 0 to 99
        {
            $mod = floor($num / 10);
            if ($mod == 0) // ones place
            {
                if ($num == 1) $retval .= "One";
                else if ($num == 2) $retval .= "Two";
                else if ($num == 3) $retval .= "Three";
                else if ($num == 4) $retval .= "Four";
                else if ($num == 5) $retval .= "Five";
                else if ($num == 6) $retval .= "Six";
                else if ($num == 7) $retval .= "Seven";
                else if ($num == 8) $retval .= "Eight";
                else if ($num == 9) $retval .= "Nine";
            } else if ($mod == 1) // if there's a one in the ten's place
            {
                if ($num == 10) $retval .= "Ten";
                else if ($num == 11) $retval .= "Eleven";
                else if ($num == 12) $retval .= "Twelve";
                else if ($num == 13) $retval .= "Thirteen";
                else if ($num == 14) $retval .= "Fourteen";
                else if ($num == 15) $retval .= "Fifteen";
                else if ($num == 16) $retval .= "Sixteen";
                else if ($num == 17) $retval .= "Seventeen";
                else if ($num == 18) $retval .= "Eighteen";
                else if ($num == 19) $retval .= "Nineteen";
            } else // if there's a different number in the ten's place
            {
                if ($mod == 2) $retval .= "Twenty ";
                else if ($mod == 3) $retval .= "Thirty ";
                else if ($mod == 4) $retval .= "Forty ";
                else if ($mod == 5) $retval .= "Fifty ";
                else if ($mod == 6) $retval .= "Sixty ";
                else if ($mod == 7) $retval .= "Seventy ";
                else if ($mod == 8) $retval .= "Eighty ";
                else if ($mod == 9) $retval .= "Ninety ";
                if (($num % 10) != 0) {
                    $retval = rtrim($retval); //get rid of space at end
                    $retval .= "-";
                }
                $retval .= $this->readNumber($num % 10, 0);
            }
        }

        if ($num != 0) {
            if ($depth == 3)
                $retval .= " Thousand\n";
            else if ($depth == 6)
                $retval .= " Million\n";
            if ($depth == 9)
                $retval .= " Billion\n";
        }
        return $retval;
    }


}
