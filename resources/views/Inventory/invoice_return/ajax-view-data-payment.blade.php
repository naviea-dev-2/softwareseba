
<div class="container pt-0">
    <table class="table table-bordered">
        <thead>
            <th>#</th>
            <th>Date</th>
            <th>Payment Method</th>
            <th>Account</th>
            <th>Paid Amount</th>
            <th>Note</th>
            {{-- <th>Status</th> --}}
        </thead>
        {{-- {{ $purchase->items }} --}}
        <tbody>
            @foreach ($payments as $k=>$payment)
            <tr>
                <td>{{ $k+1 }}</td>
                 <td>{{ date('Y-m-d',strtotime($payment->date)) }}</td>
                <td>{{ $payment->method?->name }}</td>
                <td>{{ $payment->account?->account_name }}</td>
                <td>{{ auth()->user()->currency_symbol }}{{  round($payment->amount,2) }}</td>
                @php
                    $d_p['d']=date('Y-m-d',strtotime($payment->date));
                    $d_p['m']=$payment->method?->name;
                    $d_p['note']=$payment->note;
                    $d_p['a']=$payment->account?->account_name;
                    $d_p['code']=$payment->invoice_return?->reference_no;
                    $d_p['c_name']=$invoice_return->customer?->name;
                    $d_p['c_mobile']=$invoice_return->customer?->mobile;
                    $d_p['c_email']=$invoice_return->customer?->email;
                    $d_p['c_address']=$invoice_return->customer?->address;
                    $d_p['b']=auth()->user()->currency_symbol . round($payment->amount,2);
                @endphp
                <td><button data-id="{{$payment->id}}" data-payment="{{ json_encode($d_p)}}" type="button" class="print-btn-payment btn btn-default btn-sm d-print-none"><i class="bx bx-printer"></i> Print </button></td>
                {{-- <td>{{ $payment->note }}</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

