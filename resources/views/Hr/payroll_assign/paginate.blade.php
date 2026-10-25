@if( $data_list->lastPage() > 1)
    <!-- pagination-start -->
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                @php
                    $pre_no =$data_list->currentPage();
                    $last_no =$data_list->lastPage();
                    if($pre_no != 1){
                        $pre_no = $pre_no-1;
                    }
                    if(empty($_GET)){
                        $url = $data_list->url($pre_no).'&per_page='.$per_page;
                    }else{
                        $url =request()->fullUrl().'&page='.$pre_no;
                    }

                @endphp
                <a class="page-link page-link-enable" last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Previous">	<span aria-hidden="true">«</span>
                </a>
            </li>
            @for($i=1;$i <= $data_list->lastPage();$i++)
            <li class="page-item"><a class="page-link page-link-enable select_page_{{ $i }} {{ $i == $data_list->currentPage() ? 'active' : 0 }}" href="{{  empty($_GET) ?  ($data_list->url($i).'&per_page='.$per_page) : (request()->fullUrl().'&page='.$i) }}">{{ $i }} </a>
            </li>
            @endfor

            <li class="page-item">
                @php
                    $next_no =$data_list->currentPage();

                    if($next_no != $last_no){
                        $next_no = $next_no+1;
                    }
                    if(empty($_GET)){
                        $url = $data_list->url($next_no).'&per_page='.$per_page;
                    }else{
                        $url =request()->fullUrl().'&page='.$next_no;
                    }

                @endphp
                <a class="page-link page-link-enable"  last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Next">	<span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- pagination-end -->
    @endif
