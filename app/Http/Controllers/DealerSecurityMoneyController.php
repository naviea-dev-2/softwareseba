<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\DealerSecurityMoney;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealerSecurityMoneyController extends Controller
{
    public function index(Dealer $dealer)
    {
        $transactions = $dealer->securityMoney()
            ->with('createdBy')
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(20);

        $balance = $this->getBalance($dealer);

        return view('distribution.security-money.index', compact(
            'dealer',
            'transactions',
            'balance'
        ));
    }


    public function store(Request $request, Dealer $dealer)
    {
        $validated = $request->validate([
            'transaction_type' => [
                'required',
                'in:deposit,refund,adjustment'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'payment_method' => [
                'nullable',
                'in:cash,bank,cheque,mobile_banking,other'
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'transaction_date' => [
                'required',
                'date'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $dealer,
            $request
        ) {

            /*
             * Refund cannot be greater than current balance.
             */
            if ($validated['transaction_type'] === 'refund') {

                $balance = $this->getBalance($dealer);

                if ($validated['amount'] > $balance) {

                    abort(
                        422,
                        'Refund amount cannot be greater than security money balance.'
                    );
                }
            }


            DealerSecurityMoney::create([

                'dealer_id' =>
                    $dealer->id,

                'transaction_no' =>
                    $this->generateTransactionNo(),

                'transaction_type' =>
                    $validated['transaction_type'],

                'amount' =>
                    $validated['amount'],

                'payment_method' =>
                    $validated['payment_method'] ?? null,

                'reference_no' =>
                    $validated['reference_no'] ?? null,

                'transaction_date' =>
                    $validated['transaction_date'],

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    $request->user()?->id,
            ]);
        });


        return redirect()
            ->route('dealers.security-money.index', $dealer)
            ->with('success', 'Security money transaction created successfully.');
    }


    private function getBalance(Dealer $dealer): float
    {
        return (float) $dealer->securityMoney()
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN transaction_type = 'deposit'
                                THEN amount

                            WHEN transaction_type = 'refund'
                                THEN -amount

                            WHEN transaction_type = 'adjustment'
                                THEN amount

                            ELSE 0
                        END
                    ),
                    0
                ) AS balance
            ")
            ->value('balance');
    }


    private function generateTransactionNo(): string
    {
        do {

            $number =
                'SM-' .
                now()->format('YmdHis') .
                '-' .
                random_int(100, 999);

        } while (
            DealerSecurityMoney::where(
                'transaction_no',
                $number
            )->exists()
        );


        return $number;
    }
}