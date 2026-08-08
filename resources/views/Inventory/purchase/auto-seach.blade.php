 <div class="auto-search auto-search-{{ $field }}">
    <div class="auto-search-container">
        {{-- <h5>searching.....</h5> --}}
        <ul>
            @if($vendors->isEmpty())
             <li class="auto-search-res-empty" data-id="0" data="">Search is Empty</li>
            @endif
            @foreach ($vendors as $k=>$vendor)
            @php
            $data=array();
                $data['name']=$vendor->name;
                $data['email']=$vendor->email;
                $data['mobile']=$vendor->mobile;
                $data['address']=$vendor->address;
            @endphp
            @if($k == 0)
            <li style="background-color: #e9ecef;" class="auto-search-res" data-id="{{ $vendor->id }}" data="{{ json_encode($data) }}">{{ $vendor->$field }}</li>
            @else
            <li class="auto-search-res" data-id="{{ $vendor->id }}" data="{{ json_encode($data) }}">{{ $vendor->$field }}</li>
            @endif
            @endforeach


        </ul>
    </div>
</div>
