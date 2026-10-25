@if($is_p == 0)
<div style="background: #f3f3f3 !important;color:#1a6dc8;display:flex;padding: .625em 1em;align-items: center;font-size: 13px !important;    justify-content: space-between;">
    <div style="display:flex;align-items: center;">
        <span style="font-weight: 700;">HR List</span>
        <div style="display:flex;align-items: center;">
            <div>( Check the left box</div>
            <div style="height: 12px;width: 12px;margin: 0 5px;background: #f2f4ff;border: 1px solid #5a5eeb;border-radius: 3px;"></div>
            <div>to edit data fields )</div>
        </div>
    </div>
    <div style="font-weight: 700;">
        Total Found : {{ $employees->total() }}
    </div>
</div>

<div class="responsive-table-div">
    <table id="sear_dataTable" class="table display responsive nowrap table-bordered" style="width:100%;border: 1px solid #dee2e6;">
        <thead>
            <tr>
                <th style="width:0%;vertical-align: top;padding:.625em .875em;">
                    <input type="checkbox"  @if( isset($select_student['all'.$employees->currentPage()])) checked @endif name="all_select" id="all_select"/>
                </th>
                <th style="width:0%;padding: .625em .875em;">HR ID
                    <input style="padding: 0 10px;height: 30px;margin-top: .25em;" class="form-control" value="{{ $search_s_code }}" type="text" id="search_s_code"/>
                </th>
                <th style="width:10%;padding: .625em .875em;">Name
                    <input style="padding: 0 10px;height: 30px;margin-top: .25em;" class="form-control" value="{{ $search_name }}" type="text" id="search_name"/>
                </th>
                <th style="width:10%;padding: .625em .875em;">
                    Basic Salary
                </th>
                {{-- <th style="width:5%;padding: .625em .875em;">Designation
                    <input style="padding: 0 10px;height: 30px;margin-top:.25em;" class="form-control" value="{{ $search_designation }}" type="text" id="search_designation"/>
                </th> --}}

                @foreach ($head_list as $head)
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">{{ $head?->name }} {{$head->id}}
                    <input type="hidden" data-id="{{ $head->id }}" class="stype_salary" value="{{ $head->type }}">
                    @if($head->type == "Addition")

                        (+)
                    @else
                        (-)
                    @endif
                </th>
                @endforeach
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">
                    <input type="checkbox" @if($chk_advance == 1) checked @endif  name="chk_advance" id="chk_advance"/>
                    Advance
                </th>
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">
                    <input type="checkbox" @if($chk_due == 1) checked @endif  name="chk_due" id="chk_due"/>
                    Due
                </th>
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">Net Salary</th>
            </tr>
        </thead>
        <tbody id="ajax_search_body">
@endif
            @php
                $total = 0;
                $is_check = isset($select_student[-1]) == 1 ? true : false;
            @endphp
            @foreach($employees as $key=>$teacher)
                <tr>
                    <td>
                        @if($is_check)
                        <input type="checkbox" data-des-id="{{$teacher->designation_id}}" data-pay-due="{{$teacher->total_due}}" data-pay-advance="{{$teacher->total_advance}}" name="teachers[]"  value="{{ $teacher->id }}" class="student_select"/>
                        @else
                        <input type="checkbox" data-des-id="{{$teacher->designation_id}}" data-pay-due="{{$teacher->total_due}}" data-pay-advance="{{$teacher->total_advance}}" @if( isset($select_student[$teacher->id])) checked @endif name="teachers[]"  value="{{ $teacher->id }}" class="student_select"/>
                        @endif
                    </td>
                    <td>{{ $teacher->employee_id }}</td>
                    <td>{{ $teacher->employee_name }}</td>
                    <td>{{ $teacher->salary }}</td>
                    @foreach ($head_list as $head)
                        @php
                            $ch_payroll = $payroll_list->where('employee_id',$teacher->id)->where('head_id',$head->id)->first();
                            $ch_amount = 0;
                            if($ch_payroll){
                                $ch_amount = $ch_payroll->head_amount;
                            }
                        @endphp
                        <td>{{$ch_amount}}</td>
                    @endforeach
                    <td>{{$teacher->total_advance}}</td>    
                    <td>{{$teacher->total_due}}</td>    
                    <td>
                        @php
                            $sub_total = $teacher->net_salary + $teacher->salary;
                            if($chk_advance == 1){
                                $sub_total -= $teacher->total_advance;
                            }
                            if($chk_due == 1){
                                $sub_total += $teacher->total_due;
                            }
                        @endphp
                        {{ $sub_total }}
                    </td>

                </tr>
            @endforeach
@if($is_p == 0)
        </tbody>
    </table>
</div>
@if($employees->count() > 0)
    <div class="dtable-footer my-4">
        <div class="d-flex justify-content-between ">
            <div class="form-group d-flex align-items-center  display-per-page">
                <label>Per Page </label>
                <div>
                    <select name="perPage" id="input_per_page" class="form-control ms-1">

                        <option @if($per_page == 1) selected @endif value="1">1</option>
                        <option @if($per_page == 50) selected @endif value="50">50</option>
                        <option @if($per_page == 100) selected @endif value="100">100</option>
                        <option @if($per_page == 200) selected @endif value="200">200</option>
                        <option @if($per_page == 500) selected @endif value="500">500</option>
                    </select>
                </div>
            </div>
            <div>
                <button type="button" id="submit-btn" class="btn btn-info ">Submit</button>
            </div>
        </div>
        <div id="ajax_search_paginate">
        @if( $employees->lastPage() > 1)
        <!-- pagination-start -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    @php
                        $pre_no =$employees->currentPage();
                        $last_no =$employees->lastPage();
                        if($pre_no != 1){
                            $pre_no = $pre_no-1;
                        }
                        if(empty($_GET)){
                            $url = $employees->url($pre_no).'&per_page='.$per_page;
                        }else{
                            $url =request()->fullUrl().'&page='.$pre_no;
                        }

                    @endphp
                    <a class="page-link" last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Previous">	<span aria-hidden="true">«</span>
                    </a>
                </li>
                @for($i=1;$i <= $employees->lastPage();$i++)
                <li class="page-item"><a class="page-link select_page_{{ $i }} {{ $i == $employees->currentPage() ? 'active' : 0 }}" href="{{  empty($_GET) ?  ($employees->url($i).'&per_page='.$per_page) : (request()->fullUrl().'&page='.$i) }}">{{ $i }} </a>
                </li>
                @endfor

                <li class="page-item">
                    @php
                        $next_no =$employees->currentPage();

                        if($next_no != $last_no){
                            $next_no = $next_no+1;
                        }
                        if(empty($_GET)){
                            $url = $employees->url($next_no).'&per_page='.$per_page;
                        }else{
                            $url =request()->fullUrl().'&page='.$next_no;
                        }

                    @endphp
                    <a class="page-link" last_page="{{  $last_no }}" href="{{ $url }}" aria-label="Next">	<span aria-hidden="true">»</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- pagination-end -->
        @endif
        </div>
    </div>

@endif
@endif
