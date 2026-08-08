@foreach($balances as $item)
<option value="{{$item->id}}"> {{$item->balance}} </option>
@endforeach
