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
                <th style="width:2%;vertical-align: top;padding:.625em .875em;"><input type="checkbox"  @if( isset($select_student['all'.$employees->currentPage()])) checked @endif name="all_select" id="all_select"/></th>
                <th style="width:5%;padding: .625em .875em;">HR ID
                    {{-- <input style="padding: 0 10px;height: 30px;margin-top: .25em;" class="form-control" value="{{ $search_s_code }}" type="text" id="search_s_code"/> --}}
                </th>
                <th style="width:10%;padding: .625em .875em;">Name
                    {{-- <input style="padding: 0 10px;height: 30px;margin-top: .25em;" class="form-control" value="{{ $search_name }}" type="text" id="search_name"/> --}}
                </th>
                <th style="width:5%;padding: .625em .875em;">Designation
                    {{-- <input style="padding: 0 10px;height: 30px;margin-top:.25em;" class="form-control" value="{{ $search_designation }}" type="text" id="search_designation"/> --}}
                </th>
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">Basic Salary</th>
                @foreach ($head_list as $head)
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">{{ $head?->name }}
                    <input type="hidden" data-id="{{ $head->id }}" class="stype_salary" value="{{ $head->type }}">
                    @if($head->type == "Addition")

                        (+)
                    @else
                        (-)
                    @endif
                </th>
                @endforeach
                <th style="width:5%;vertical-align:middle;padding:.625em .875em;">Net Salary</th>
            </tr>
        </thead>
        <tbody id="ajax_search_body">
@endif
            @php
                $total = 0;
                $is_check = isset($select_student[-1]) == 1 ? true : false;
            @endphp
            @foreach($employees as $key=>$employee)

                <tr>

                    <td>
                        @if($is_check)
                        <input type="checkbox" name="teachers[]"  value="{{ $employee->id }}" class="student_select"/>
                        @else
                        <input type="checkbox" @if( isset($select_student[$employee->id])) checked @endif name="teachers[]"  value="{{ $employee->id }}" class="student_select"/>
                        @endif

                    </td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{$employee->employee_name}}</td>
                    <td>{{$employee->designation?->name}}</td>
                    <td id="em_basic_salary_{{$employee->id}}">{{$employee->salary}}</td>
                    @php
                        $total_salary = $employee->salary;
                    @endphp
                    @if( isset($select_student[$employee->id]))
                        @foreach ($head_list as $head)
                            @php
                                $total_salary += $head_arr[$employee->id][$head->id] ?? 0;
                            @endphp
                            <td style="padding: 5px;vertical-align: middle;">
                                <input s_type ="{{ $head->type }}"  style="border-radius: 3px 3px 3px 3px !important;height: 32px !important;" class="p-0 px-1 s_head_{{  $employee->id }} form-control change_employee_id" head-id="{{ $head->id }}" employee-id="{{ $employee->id }}" type="text" name="stu_id[{{ $employee->id }}][{{ $head->id }}]" id="select_teacher_id_{{ $employee->id }}-{{ $head->id }}" value="{{ $head_arr[$employee->id][$head->id] }}"/>
                            </td>
                        @endforeach
                    @else
                        @foreach ($head_list as $head)
                            @php
                                $ch_payroll = $payroll_list->where('employee_id',$employee->id)->where('head_id',$head->id)->first();
                                $ch_amount = 0;
                                if($ch_payroll){
                                    $ch_amount = $ch_payroll->head_amount;
                                }
                                $total_salary += $ch_amount;
                            @endphp
                            <td style="padding: 5px;vertical-align: middle;">
                                <input s_type ="{{ $head->type }}" style="border-radius: 3px 3px 3px 3px !important;height: 32px !important;" class="p-0 px-1 s_head_{{  $employee->id }} form-control change_employee_id" head-id="{{ $head->id }}" employee-id="{{ $employee->id }}" type="text" name="stu_id[{{ $employee->id }}][{{ $head->id }}]" id="select_teacher_id_{{ $employee->id }}-{{ $head->id }}" value="{{ $ch_amount }}"/>
                            </td>
                        @endforeach
                    @endif
                    <td class="em_net_salary_{{$employee->id}}">{{$total_salary}}</td>
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

                        <option @if($per_page == 10) selected @endif value="10">10</option>
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
