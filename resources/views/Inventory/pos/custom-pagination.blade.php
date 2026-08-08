@if( $products->lastPage() > 1)
<!-- pagination-start -->

    <ul class="pagination">
        <li class="page-item">
            <a class="page-link prev" href="#" page="1" aria-label="Previous">	<span aria-hidden="true">«</span>
            </a>
        </li>
        @for($i=1;$i <= $products->lastPage();$i++)
        <li class="page-item">
            <a class="page-link page-link-{{$i}} {{ $i == $products->currentPage() ? 'active' : 0 }}" href="#" page="{{$i}}">{{ $i }} </a>
        </li>
        @endfor

        <li class="page-item">
            <a class="page-link next" last_page="{{$products->lastPage()}}" page="2" href="#" aria-label="Next">	<span aria-hidden="true">»</span>
            </a>
        </li>
    </ul>

<!-- pagination-end -->
@endif