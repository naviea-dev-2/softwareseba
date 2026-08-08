@extends('inc.master')

@section('head')


<title>Manage Deposit Payment</title>
<style>
    label{
        font-size: 1.2rem;
    }
    .card {
        box-shadow: none!important;
        margin-bottom: 24px!important;
        transition: box-shadow 0.2s ease-in-out!important;
    }
    .card-header{
        border-bottom: 1px solid #eeeeee!important;
        padding:25px 25px!important;
    }
    .card-body {
        padding: 0px 25px 25px!important;
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
                
                <div class="d-flex justify-content-between align-items-center">
                    <h5 style="font-size: 0.875rem; margin:0;">Deposit Payment</h5>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user_deposit.create') }}" class="btn btn-primary" ><i class="bx bx-plus"></i> Add Deposit</a>
                    </div>
                </div>
                   
            </div>

           
            <div class="row" style="padding-top: 24px;">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-layout form-layout-4 p-0">
                                <form action="#" class="myform" id="learner_myform" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                    @csrf
                                    <div class="row mt-0">
                                    
                                        <div class="col-sm-2 mt-3 show_date_wise">
                                            <label class="form-control-label">From Date: <span class="tx-danger">*</span></label>
                                            <div class="mg-t-10 mg-sm-t-0">
                                                <input type="text" name="from_date" id="from_date" class="form-control fl-datepicker" value="{{old('from_date')}}"/>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 mt-3 show_date_wise">
                                            <label class="form-control-label">To Date: <span class="tx-danger">*</span></label>
                                            <div class="mg-t-10 mg-sm-t-0">
                                                <input type="text" name="to_date" id="to_date" class="form-control fl-datepicker" value="{{old('to_date')}}"/>

                                            </div>
                                        </div>
                                       
                                       
                                        <div class="col-sm-3 mt-3">
                                            <label class="form-control-label">Land: <span class="tx-danger">*</span></label>
                                            <div class="mg-t-10 mg-sm-t-0">
                                                <select  class="form-control" name="land" id="f_land">
                                                    <option value="">Select Shift</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mt-3">
                                            <label class="form-control-label">Payment Method: <span class="tx-danger">*</span></label>
                                            <div class="mg-t-10 mg-sm-t-0">
                                                <input type="hidden" name="h_class" id="h_class" value="{{old('h_class')}}"/>
                                                <select  class="form-control" name="payment_method" id="f_payment_method">
                                                    <option value="">Select Class</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 text-right mt-3" style="display: flex;flex-direction: column;justify-content: end;">
                                            <button type="button" id="search-btn" class="btn btn-info ">search</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- form-layout -->
                            <div id="ajax-students-list" class="mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>

        <!-- Main Content Area End -->
    </div>
</div>
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>

<script>
    $(".fl-datepicker").flatpickr({
        defaultDate: new Date("{{ date('Y-m-d') }}"),
    });
    var cur_page = 1;
    var cur_per_page = 10;
    $('#search-btn').on('click',function(){
        console.log("test");
        cur_page = 1;
        cur_per_page = 10;
        ajaxMigrateStudent(cur_page,cur_per_page)

    });
    $(document).on('click','.page-link',function(){
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
    function ajaxMigrateStudent(page,per_page){
        console.log($("#form_date").val());
        // console.log(document.querySelector("#form_date")._flatpickr.altInput.value);
        var data = new FormData();
        data.append( '_token',"{{ csrf_token() }}");
        data.append( 'page',page);
        data.append( 'per_page',per_page);
        data.append( 'land',$("#f_land").val());
        data.append( 'payment_method',$("#f_payment_method").val());
        data.append( 'from_date',$("#from_date").val());
        data.append( 'to_date',$("#to_date").val());
        
        data.append( 'land_text',$("#f_land option:selected").text());
        data.append( 'payment_method_text',$("#f_payment_method option:selected").text());



        $.ajax({
            url: "{{route('user_deposit.ajax') }}",
            processData: false,
            contentType: false,
            method: 'POST',
            data:data,
            success: function(data) {
                console.log(data);
                if(data.status == "yes"){
                    $('#ajax-students-list').html(data.html);
                }
            }
        });
    }
    $(document).on('change','#input_per_page',function(){
        cur_page = 1;
        cur_per_page = $(this).val();
        console.log(cur_per_page);
        ajaxMigrateStudent(cur_page,cur_per_page);
    });
    ajaxMigrateStudent(1,10);

    function select2Deposit(id,url,placeholder="",id1="id"){
        $(id).select2({
            theme: "bootstrap-5",
            placeholder:placeholder ,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    value: $.trim(params.term),
                    method_id:$('#'+id1).val(),
                };
                },
                processResults: function (response) {

                return {
                    results: response
                };
                },
                cache: true
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            $(id+"_h").val(data.text);
        });
    }
    select2Deposit('#f_land','{{route('select2.property')}}','Select Land Plot');
    select2Deposit('#f_payment_method','{{route('select2.payment_methods')}}','Select Method');
    $(document).on('click','.property_del_data',function(){
        let id = $(this).attr('data-id');
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ms-2',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = "{{ url('user-deposit-delete') }}/"+id;
            }
        });
    });

</script>
@endsection
