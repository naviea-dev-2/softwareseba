
@extends('inc.master')
@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Payroll Assign</title>
<style>
    .select2-container .select2-selection--single{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 40px!important;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear{
        height: 40px!important;
    }
</style>
@endsection
@section('content')

<div class="card">
    <div class="card-body">
        <div class="br-section-wrapper data-list px-1 pt-3">

            <h6 class="br-section-label text-center mb-2">Payroll Assign HR List</h6>
            {{-- validate start  --}}
            <div id="create_errors"></div>
            {{-- validate End  --}}
            <div class="mx-auto">
                <div class="form-layout form-layout-4 px-3 pb-2 pt-2">
                    <form id="data-form-subc-create" action="{{ route('school.payroll_assign.search') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-3  mt-2">
                                <label class=" form-control-label"><b>Department:</b></label>
                                <select class="form-control" id="f_department">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3  mt-2">
                                <label class=" form-control-label"><b>Designation:</b></label>
                                <select class="form-control" id="f_designation">
                                    <option value="">Select Designation</option>
                                </select>
                            </div>
                            <div class="col-sm-3 mt-2">
                                <label class="form-control-label">Gender: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <select  class="form-control select2" name="gender" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3  mt-2">
                                <label class="form-control-label">Religion: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <select  class="form-control select2" name="religion" id="religion">
                                        <option value="">Select Religion</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddhism">Buddhism</option>
                                        <option value="Christian">Christian</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label class="form-control-label">Search Name:</label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="search_f_name" id="search_f_name" class="form-control" placeholder="Teacher Name" />
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label class="form-control-label">Search ID:</label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="search_f_code" id="search_f_code" class="form-control" placeholder="Teacher ID" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right">
                            <a href="javascript:void(0);" type="button" id="clear_btn" class="btn btn-secondary text-white mr-2 btn-s-cancel" >Clear</a>
                            <button type="submit" class="btn btn-info search-btn">Search</button>
                            </div>
                        </div>
                    </form>
                    <div id="input-student-res"></div>
                    <div class="search-res mt-3"></div>
                </div>
            </div>

        </div><!-- br-section-wrapper -->

    </div>
</div>
        
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $('#f_department').select2({
            placeholder: "select department",
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: "{{ route('select2.department') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    value: $.trim(params.term),
                };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $('#f_designation').select2({
            placeholder: "select designation",
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: "{{ route('select2.designation') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    dept_id: $('#f_department').val(),
                    value: $.trim(params.term),
                };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        var cur_page = 1;
        var cur_per_page = 10;
        var cur_total_item=10;
        var acitve_se ;

        $(document).on('click','.search-btn',function(event){
            event.preventDefault();

            cur_page = 1;
            cur_per_page = 10;
            ajaxMigrateStudent(cur_page,cur_per_page);
        });

        function ajaxMigrateStudent(page,per_page,is_p=0){

            var data = new FormData();
            data.append( '_token',"{{ csrf_token() }}");
            data.append( 'page',page);
            data.append( 'per_page',per_page);
            data.append( 'is_p',is_p);
           
            data.append( 'department',$("#f_department").val());
           
            data.append( 'designation',$("#f_designation").val());
            data.append( 'gender',$("#gender").val());
            data.append( 'religion',$("#religion").val());
            data.append( 'search_f_name',$("#search_f_name").val());
            data.append( 'search_f_code',$("#search_f_code").val());

           
            if($("#search_s_code").length > 0){
                data.append( 'search_s_code',$("#search_s_code").val());
                data.append( 'search_name',$("#search_name").val());
                data.append( 'search_designation',$("#search_designation").val());
            }
            console.log($('.ajax-select-student'));
            if($('.ajax-select-student').length == 0){
                data.append('select_student[-1]' ,1);
            }else{
                $('.ajax-select-student').each(function(){
                    var s_value = $(this).val();
                    data.append($(this).attr('name') ,1);
                    var h_arr = JSON.parse($('#select_student_'+s_value).attr('head_arr'));
                    console.log(h_arr);
                    for (const x in h_arr) {
                        data.append('head_arr['+s_value+']['+x+']' ,h_arr[x]);
                        // console.log(x);
                        // console.log(h_arr[x]);
                    }

                    // data.append('head_arr['+s_value+']' ,JSON.parse(h_arr));
                });
            }

            $.ajax({
                url: "{{route('school.payroll_assign.search') }}",
                processData: false,
                contentType: false,
                method: 'POST',
                data:data,
                success: function(res) {
                    console.log(res);
                    if(res.status == "error"){
                        console.log(res);
                        // var e_option = "";
                        // res.errors.forEach(function(v){
                        //     e_option += '<div class="alert alert-danger">'+v+'</div>';
                        // });
                        // $('#create_errors').html(e_option);
                    }
                    else if(res.status == "yes"){
                        if(is_p == 0){
                            cur_total_item = parseInt(res.t_count);
                            $('.search-res').html(res.html);
                            $('.fee_head_res').html(res.html2);

                            checkAllCheckBox();

                            if(acitve_se){
                                var temp = $('#'+acitve_se).focus().val();
                                $('#'+acitve_se).val('').val(temp);
                            }
                        }else{
                            cur_total_item = parseInt(res.t_count);
                            $('#ajax_search_body').html(res.html);
                            $('#ajax_search_paginate').html(res.paginate);

                            checkAllCheckBox();

                            if(acitve_se){
                                var temp = $('#'+acitve_se).focus().val();
                                $('#'+acitve_se).val('').val(temp);
                            }
                        }

                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }

        $(document).on('click',"#clear_btn",function(){
            event.preventDefault();
            
            $("#f_department").val('');
            $('#f_designation').val('').trigger('change');
            $("#gender").val('');
            $("#religion").val('');
            $("#search_f_name").val('');
            $("#search_f_code").val('')
            cur_page = 1;
            cur_per_page = 10;
            ajaxMigrateStudent(cur_page,cur_per_page);     
        });
        ajaxMigrateStudent(cur_page,cur_per_page);
        function checkAllCheckBox(){
            $('.student_select').each(function(){

                if($(this).is(':checked')){
                    if($('#select_student_'+$(this).val()).length == 0){

                        var s_value= $("input[name='attendance_status["+$(this).val()+"]']").val();
                        $('#input-student-res').append('<input type="hidden" data-id="'+$(this).val()+'" late_m="'+$('#select_late_minute_'+$(this).val()).val()+'" value="'+s_value+'" class="ajax-select-student" name="select_student['+$(this).val()+']" id="select_student_'+$(this).val()+'" />');
                    }
                }
            });
            if($('.ajax-select-student').length > 0 && $('.ajax-select-student').length == cur_total_item){
                $('#all_select').prop('checked',true);
                if($('#select_student_all'+cur_page).length == 0){
                    $('#input-student-res').append('<input type="hidden" value="-1" class="ajax-select-student" name="select_student[all'+cur_page+']" id="select_student_all'+cur_page+'" />');
                }
            }
        }
        $(document).on('change','#input_per_page',function(){
            cur_page = 1;
            cur_per_page = $(this).val();
            ajaxMigrateStudent(cur_page,cur_per_page);
        });
        $(document).on('click','.page-link',function(event){
            event.preventDefault();
            // console.log($(this).attr('href'));
            const parsedUrl = new URL($(this).attr('href'));
            var last_page = $(this).attr('last_page');

            if($(this).attr('aria-label') == "Previous"){
                if(cur_page == 1){
                    cur_page=last_page;
                }else{
                    cur_page=parseInt(cur_page)-1;
                }

            }else if($(this).attr('aria-label') == "Next"){
                if(cur_page == last_page){
                    cur_page=1;
                }else{
                    cur_page=parseInt(cur_page)+1;
                }

            }else{
                cur_page=parsedUrl.searchParams.get('page');
            }
            ajaxMigrateStudent(cur_page,cur_per_page);
            $(document).find('.page-link').removeClass('active');
            $(document).find('.select_page_'+cur_page).addClass('active');
        });

        $(document).on('keyup','#search_s_code,#search_roll,#search_name,#search_father',function(){
            acitve_se=this.id;
            cur_page = 1;
            cur_per_page = $('#input_per_page').val() ?? 10;
            ajaxMigrateStudent(cur_page,cur_per_page,1);
        });
        $(document).on("click",'.student_select',function(){
            if($(this).is(':checked')){
                if($('#select_student_'+$(this).val()).length == 0){
                    var s_value = $(this).val();
                    var head_arr={};
                    $('.s_head_'+s_value).each(function(){
                        var head_id = $(this).attr('head-id');
                        head_arr[head_id]=$(this).val();
                    });
                    head_arr = JSON.stringify(head_arr);
                    $('#input-student-res').append('<input head_arr='+head_arr+' type="hidden" data-id="'+s_value+'" value="'+s_value+'" class="ajax-select-student" name="select_student['+s_value+']" id="select_student_'+s_value+'" />');
                }
            }else{
                if($('#select_student_'+$(this).val()).length == 1){
                    $('#select_student_'+$(this).val()).remove();
                }
                if ($('.student_select:checked').length == 0) {
                    $("#all_select").prop('checked',false);
                }
            }
        });
        $(document).on('click','#all_select',function(){
            if($(this).is(':checked')){
                if($('#select_student_all'+cur_page).length == 0){
                    $('#input-student-res').append('<input type="hidden" value="-1" class="ajax-select-student" name="select_student[all'+cur_page+']" id="select_student_all'+cur_page+'" />');
                }
                $('.student_select').each(function(){
                    $(this).prop('checked',true);
                    // if($('#select_student_'+$(this).val()).legnth)

                    if($('#select_student_'+$(this).val()).length == 0){
                        var s_value = $(this).val();
                        var head_arr={};
                        $('.s_head_'+s_value).each(function(){
                            var head_id = $(this).attr('head-id');
                            head_arr[head_id]=$(this).val();
                        });
                        head_arr = JSON.stringify(head_arr);

                        $('#input-student-res').append('<input type="hidden" head_arr='+head_arr+' data-id="'+s_value+'" value="'+s_value+'" class="ajax-select-student" name="select_student['+s_value+']" id="select_student_'+s_value+'" />');
                    }

                });
            }else{
                if($('#select_student_all'+cur_page).length == 1){
                    $('#select_student_all'+cur_page).remove();
                }
                $('.student_select').each(function(){
                    $(this).prop('checked',false);

                    if($('#select_student_'+$(this).val()).length == 1){
                        $('#select_student_'+$(this).val()).remove();
                    }
                });

            }

        });
        $(document).on('keyup','.change_employee_id',function(){
         
            var s_value = $(this).attr('employee-id');
            if($('#select_student_'+s_value).length > 0){
                var head_arr={};
                $('.s_head_'+s_value).each(function(){
                    var head_id = $(this).attr('head-id');
                    head_arr[head_id]=$(this).val();
                });
                head_arr = JSON.stringify(head_arr);
                $('#select_student_'+s_value).attr('head_arr',head_arr);
            }
            var basic_amount = parseFloat($('#em_basic_salary_'+s_value).text().trim());
            var total_amount = basic_amount;
            $('.s_head_'+s_value).each(function(){
                total_amount += parseFloat($(this).val());
            });
            $('.em_net_salary_'+s_value).text(total_amount);
        });
        $(document).on('click','#submit-btn',function(event){
            event.preventDefault();

            if($('.ajax-select-student').length == 0){
                alert('please select teacher');
                return;
            }
            var data = new FormData();
            data.append( '_token',"{{ csrf_token() }}");
            if($('.ajax-select-student').length > 0){
                $('.ajax-select-student').each(function(){
                    var s_value = $(this).val();
                    // console.log(s_value);
                    // console.log($('#select_student_'+s_value));
                    if(s_value != -1){
                        data.append('employees[]' ,s_value);
                        var h_arr = JSON.parse($('#select_student_'+s_value).attr('head_arr'));
                        for (const x in h_arr) {
                            data.append('head_arr['+s_value+']['+x+']' ,h_arr[x]);
                        }
                    }
                    
                });
            }
            $('.stype_salary').each(function(){
                var s_value = $(this).val();
                var s_id = $(this).attr('data-id');
                data.append('type_arr['+s_id+']' ,s_value);
            });

            var arg = this;
            $.ajax({
                url: "{{route('school.payroll_assign.store') }}",
                processData: false,
                contentType: false,
                method: 'POST',
                data:data,
                beforeSend: function () {
                    $(arg).html("<i class='fas fa-spinner fa-spin'></i> Loading");
                },
                success: function(res) {
                    console.log(res);
                    if(res.status == "error"){
                        console.log(res);
                        // var e_option = "";
                        // res.errors.forEach(function(v){
                        //     e_option += '<div class="alert alert-danger">'+v+'</div>';
                        // });
                        // $('#create_errors').html(e_option);
                    }
                    else if(res.status == "yes"){
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#input-student-res').html('');
                        cur_page=1;
                        cur_per_page=10;
                        ajaxMigrateStudent(cur_page,cur_per_page);


                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: res.msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(e){
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: e,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                complete: function () {
                    setTimeout(function () {
                        $(arg).html("Submit");
                    }, 1000);
                },
            });
        });

    });
</script>
@endsection
