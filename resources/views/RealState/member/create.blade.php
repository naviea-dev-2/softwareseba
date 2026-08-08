@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Add Member</title>
<style>
    label{
        font-size: 1rem;
        color:#343a40;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    .error-show .select2-container--bootstrap-5 .select2-selection{
        border: 1px solid red;
    }
    .form-group {
        margin-bottom: 15px;
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
        padding: 25px 25px!important;
    }
    .dropzone {
        min-height: auto;
    }

    .dropzone {
    padding: 20px;
    text-align: center;
    cursor: pointer;
    border: 2px dashed #eeeeee;
    border-radius: 8px;
    }
    .dropzone .dropzone-msg-title {
    color: #212529;
    margin: 0 0 5px;
    padding: 0;
    font-weight: 500;
    font-size: 1.2rem;
    }
    .dropzone .dropzone-msg-desc {
    color: #212529;
    font-weight: 400;
    font-size: 1rem;
    }
    .dropzone .dz-preview .dz-image {
    border-radius: 8px;
    }
    .dropzone.dropzone-primary {
    border-color: #2689e2;
    }
    .dropzone.dropzone-secondary {
    border-color: #6610f2;
    }
    .dropzone.dropzone-success {
    border-color: #00c853;
    }
    .dropzone.dropzone-info {
    border-color: #3ec9d6;
    }
    .dropzone.dropzone-warning {
    border-color: #ffc107;
    }
    .dropzone.dropzone-danger {
    border-color: #f44336;
    }
    .dropzone.dropzone-light {
    border-color: #f8f9fa;
    }
    .dropzone.dropzone-dark {
    border-color: #111936;
    }

    .dz-started .dropzone-msg {
    display: none;
    }

    .dropzone-multi {
    border: 0;
    padding: 0;
    }
    .dropzone-multi .dz-message {
    display: none;
    }
    .dropzone-multi .dropzone-panel .dropzone-remove-all,
    .dropzone-multi .dropzone-panel .dropzone-upload {
    display: none;
    }
    .dropzone-multi .dropzone-item {
    background: #eceff1;
    border-radius: 8px;
    margin: 8px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 14px;
    }
    .dropzone-multi .dropzone-item .dropzone-progress {
    width: 20%;
    }
    .dropzone-multi .dropzone-item .dropzone-progress .progress {
    height: 0.5rem;
    transition: all 0.2s ease-in-out;
    }
    .dropzone-multi .dropzone-item .dropzone-file .dropzone-filename {
    font-size: 0.9rem;
    font-weight: 500;
    color: #212529;
    text-overflow: ellipsis;
    margin-right: 0.5rem;
    }
    .dropzone-multi .dropzone-item .dropzone-file .dropzone-filename b {
    font-size: 0.9rem;
    font-weight: 500;
    color: #212529;
    }
    .dropzone-multi .dropzone-item .dropzone-file .dropzone-error {
    margin-top: 0.25rem;
    font-size: 0.9rem;
    font-weight: 400;
    color: #f44336;
    text-overflow: ellipsis;
    }
    .dropzone-multi .dropzone-item .dropzone-toolbar {
    margin-left: 1rem;
    display: flex;
    flex-wrap: nowrap;
    }
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-cancel,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-delete,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-start {
    height: 25px;
    width: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    }
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-cancel i,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-delete i,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-start i {
    font-size: 0.8rem;
    color: #212529;
    }
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-cancel:hover i,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-delete:hover i,
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-start:hover i {
    color: #2689e2;
    }
    .dropzone-multi .dropzone-item .dropzone-toolbar .dropzone-start {
    transition: all 0.2s ease-in-out;
    }
    .form-check-input{
        width: 1.25em!important;
        height: 1.25em!important;
        border: 1px solid rgba(0, 0, 0, 0.25)!important;
    }
</style>
@endsection
@section('content')
<div class="content-area" >
    <div class="container-fluid" style="background:#ffffff;display: flex;align-items: center;min-height: 55px;padding: 13px 25px;">
        <div class="row row-card-one">
            <div class="col-sm-12 ">
                <div class="row report-title">
                    <h5 style="font-size: 0.875rem; margin:0;">Add Member</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="padding-top: 24px;">
        <div class="col-sm-12">
            <!-- start form here -->
            <form method="POST" action="{{route('member.store')}}" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="member_type" class="form-label">Member Type</label>
                                    <input type="hidden" name="member_type_h" id="member_type_h" value="{{ old("member_type_h") }}"/>
                                    <select class="form-control @error('member_type') is-invalid @enderror" name="member_type" id="member_type" >
                                        <option value="">Select Member Type</option>
                                        @if(old("member_type"))
                                            <option value="{{ old("member_type") }}" selected>{{ old("member_type_h") }}</option>
                                        @endif
                                    </select>
                                    @error('member_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name" name="name" type="text" value="{{ old("name") }}" id="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" name="email" type="text" value="{{ old("email") }}" id="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input class="form-control  @error('mobile') is-invalid @enderror" placeholder="Enter Mobile" name="mobile" type="text" value="{{ old("mobile") }}" id="mobile">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="image" class="form-label">Thumbnail Image</label>
                                    <input class="form-control" name="image" type="file" id="image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="hidden" name="country_h" id="country_h" value="{{ old("country_h") }}"/>
                                    <select class="form-control" name="country" id="country" >
                                        <option value="">Select Country</option>
                                        @if(old("country"))
                                            <option value="{{ old("country") }}" selected>{{ old("country_h") }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="state" class="form-label">State</label>
                                    <input type="hidden" name="state_h" id="state_h" value="{{ old("state_h") }}"/>
                                    <select class="form-control" name="state" id="state" >
                                        <option value="">Select State</option>
                                        @if(old("state"))
                                            <option value="{{ old("state") }}" selected>{{ old("state_h") }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <input type="hidden" name="city_h" id="city_h" value="{{ old("city_h") }}"/>
                                    <select class="form-control" name="city" id="city" >
                                        <option value="">Select City</option>
                                        @if(old("city"))
                                            <option value="{{ old("city") }}" selected>{{ old("city_h") }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input class="form-control" placeholder="Enter Zip Code" name="zip_code" type="text" value="{{ old("zip_code") }}" id="zip_code">
                                </div>
                                <div class="form-group ">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" rows="1" placeholder="Enter Address" name="address" cols="50" id="address">{{ old("address") }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   
                    <div class="col-lg-12 text-end">
                        <button class="btn btn-sm btn-primary  ">
                            <i class="fa fa-save pr-2"></i>Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    








</div>
@endsection
@section('script')
<script src="{{ asset("public/assets/js/dropzone.js") }}"></script>
<script>
    function select2Init(source,url,place_holder,country=null,state=null){
        $(source).select2({
            theme: "bootstrap-5",
            placeholder:place_holder ,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            // containerCssClass: 'select-sm',
            // dropdownParent:modal ,
            ajax: {
                url:url ,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    country_id:$("#"+country).val(),
                    state_id:$("#"+state).val(),
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
         }).on('select2:select', function (e) {
            var data = e.params.data;
            console.log(data);
            $(source+"_h").val(data.text);
        })
        .on('select2:clear', function (e) {
            var data = e.params.data;
            console.log(data);
            $(source+"_h").val("").trigger('change');
        });
    }
    select2Init('#member_type','{{route('select2.member_type')}}','Select Member Type');
    select2Init('#country','{{route('select2.countries')}}','Select Country');
    select2Init('#state','{{route('select2.states.bycountry')}}','Select State',"country");
    select2Init('#city','{{route('select2.cities.byState')}}','Select City',"country","state");
    $(document).on("change","#country",function(){
        $("#state").val("").trigger('change');
        $("#state_h").val("");
        $("#city").val("").trigger('change');
        $("#city_h").val(0);
    });
    $(document).on("change","#state",function(){
        $("#city").val("").trigger('change');
        $("#city_h").val("");
    });
   
</script>
    
@endsection
