 <div class="auto-search auto-search-{{ $field }}">
    <div class="auto-search-container">
        {{-- <h5>searching.....</h5> --}}
        <ul>
            @if($customers->isEmpty())
             <li class="auto-search-res-empty" data-id="0" data="">Search is Empty</li>
            @endif
            @foreach ($customers as $k=>$customer)
                @php
                $data=array();
                    $data['name']=$customer->name;
                    $data['email']=$customer->email;
                    $data['mobile']=$customer->mobile;
                    $data['address']=$customer->address;
                @endphp
                @if($k == 0)
                <li style="background-color: #e9ecef;" class="auto-search-res" data-id="{{ $customer->id }}" data="{{ json_encode($data) }}">{{ $customer->$field }}</li>
                @else
                <li class="auto-search-res" data-id="{{ $customer->id }}" data="{{ json_encode($data) }}">{{ $customer->$field }}</li>
                @endif
            @endforeach


        </ul>
    </div>
</div>
