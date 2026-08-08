<option value="" selected="">Select</option>
@foreach($accounts as $account)
    <option value="{{$account->id}}">
        {{$account->bank_name ?? $account->account_name}} - 

        @if($account->branch_name)
        ( {{$account->branch_name}} ) - 
        @endif

        {{$account->account_number}}
    </option>
@endforeach
