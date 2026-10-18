@extends('inc.master')
@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Create Salary Slip</title>
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

            <h6 class="br-section-label text-center mb-2">Create Salary Slip</h6>
            {{-- validate start  --}}
            <div id="create_errors"></div>
            {{-- validate End  --}}
            <div class="col-xl-5 mx-auto">
                <div class="form-layout form-layout-4 px-3 pb-2 pt-2">
                    <form id="data-form-subc-create" action="{{ route('school.payroll_assign.search') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Salary Month: <span class="tx-danger">*</span></label>
                                <input type="text" value="{{ old("monthDate") }}" placeholder="Salary Month" name="monthDate" id="monthDate" class="form-control datetimepicker @error('payment_date') is-invalid @enderror"  required>
                                @error('monthDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Search Name:</label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="search_name" id="search_name" class="form-control" placeholder="Teacher Name" />
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Search ID:</label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="search_s_code" id="search_s_code" class="form-control" placeholder="Teacher ID" />
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-end">
                                <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-s-cancel" >Cancel</a>
                                <button type="submit" class="btn btn-info search-btn">Search</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="mt-2 p-2">
                <div id="input-student-res"></div>
                <div class="search-res mt-3"></div>
            </div>

        </div>
    </div>
</div>
        
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        var variableName = "{{ date('Y-m') }}";
        const myInput = document.querySelector(".datetimepicker");
        flatpickr(document.querySelector(".datetimepicker"), {
            altInput:true,
            defaultDate: new Date(variableName),
            plugins: [
                new monthSelectPlugin({
                shorthand: true, //defaults to false
                dateFormat: "Y-m", //defaults to "F Y"
                altFormat: "F Y", //defaults to "F Y"
                theme: "dark" // defaults to "light"
                })
            ]
        });

        var cur_page = 1;
        var cur_per_page = 10;
        var cur_total_item=10;
        var acitve_se ;

        $(document).on('click','.search-btn',function(event){
            event.preventDefault();
            if($('#monthDate').val() == ""){
                alert('please select month');
                return;
            }

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
            data.append( 'monthDate',$("#monthDate").val());
            data.append( 'chk_advance',$('#chk_advance').is(':checked') ? 1 : 0);
            data.append( 'chk_due',$('#chk_due').is(':checked') ? 1 : 0);

            if($("#search_s_code").length > 0){
                data.append( 'search_s_code',$("#search_s_code").val());
                data.append( 'search_name',$("#search_name").val());
            }

            if($('.ajax-select-student').length == 0){
                data.append('select_student[-1]' ,1);
            }else{
                $('.ajax-select-student').each(function(){
                    var s_value = $(this).val();
                    data.append($(this).attr('name') ,1);
                });
            }

            $.ajax({
                url: "{{route('school.salary_slip.search') }}",
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

        function checkAllCheckBox(){
            $('.student_select').each(function(){

                if($(this).is(':checked')){
                    if($('#select_student_'+$(this).val()).length == 0){
                        var pay_due= $(this).attr('data-pay-due');
                        var pay_advance= $(this).attr('data-pay-advance');
                        var des_id= $(this).attr('data-des-id');
                        $('#input-student-res').append('<input type="hidden" data-id="'+$(this).val()+'" des_id="'+des_id+'" pay_due="'+pay_due+'" pay_advance="'+pay_advance+'" value="'+$(this).val()+'" class="ajax-select-student" name="select_student['+$(this).val()+']" id="select_student_'+$(this).val()+'" />');
                    }
                }
            });
            if($('.ajax-select-student').length == cur_total_item){
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

            console.log(parsedUrl.searchParams.get('page'));
        });

        $(document).on('keyup','#search_s_code,#search_roll,#search_name,#search_father',function(){
            acitve_se=this.id;
            cur_page = 1;
            cur_per_page = $('#input_per_page').val() ?? 10;
            ajaxMigrateStudent(cur_page,cur_per_page,1);
        });
        $(document).on('change','#chk_advance,#chk_due',function(){
            cur_page = 1;
            cur_per_page = $('#input_per_page').val() ?? 10;
            ajaxMigrateStudent(cur_page,cur_per_page,1);
        })
        $(document).on("click",'.student_select',function(){
            if($(this).is(':checked')){
                if($('#select_student_'+$(this).val()).length == 0){
                    var s_value = $(this).val();
                    var pay_due= $(this).attr('data-pay-due');
                    var pay_advance= $(this).attr('data-pay-advance');
                    var des_id= $(this).attr('data-des-id');
                    $('#input-student-res').append('<input type="hidden" des_id="'+des_id+'" pay_due="'+pay_due+'" pay_advance="'+pay_advance+'" data-id="'+s_value+'" value="'+s_value+'" class="ajax-select-student" name="select_student['+s_value+']" id="select_student_'+s_value+'" />');
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
                    if($('#select_student_'+$(this).val()).length == 0){
                        var s_value = $(this).val();
                        var pay_due= $(this).attr('data-pay-due');
                        var pay_advance= $(this).attr('data-pay-advance');
                        var des_id= $(this).attr('data-des-id');
                        $('#input-student-res').append('<input type="hidden" des_id="'+des_id+'" pay_due="'+pay_due+'" pay_advance="'+pay_advance+'" data-id="'+s_value+'" value="'+s_value+'" class="ajax-select-student" name="select_student['+s_value+']" id="select_student_'+s_value+'" />');
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

        $(document).on('click','#submit-btn',function(event){
            event.preventDefault();

            if($('.ajax-select-student').length == 0){
                alert('please select teacher');
                return;
            }
            var data = new FormData();
            data.append( '_token',"{{ csrf_token() }}");
            data.append( 'year',$("#ac_year").val());
            data.append( 'month',$("#month").val());
            data.append( 'month',$("#month").val());
            data.append( 'chk_advance',$('#chk_advance').is(':checked') ? 1 : 0);
            data.append( 'chk_due',$('#chk_due').is(':checked') ? 1 : 0);

            if($('.ajax-select-student').length > 0){
                $('.ajax-select-student').each(function(){
                    var s_value = $(this).val();
                    data.append('teachers[]' ,s_value);
                    data.append('pay_dues['+s_value+']' ,$(this).attr('pay_due'));
                    data.append('pay_advances['+s_value+']' ,$(this).attr('pay_advance'));
                    data.append('des_ids['+s_value+']' ,$(this).attr('des_id'));
                });
            }
            $('.stype_salary').each(function(){
                var s_value = $(this).val();
                var s_id = $(this).attr('data-id');
                data.append('type_arr['+s_id+']' ,s_value);
            });
            var arg = this;
            $.ajax({
                url: "{{route('school.salary_slip.store') }}",
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
