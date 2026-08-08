
@extends('inc.master')
@section('head')
<title>SSL Payment Setting</title>
<style>
  .none{
    display:none;
  }
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-8">

                        <h6 class="br-section-label text-center mb-1">SSL Payment Setting</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form id="data-form-create" action="{{ route("online_payemnt.store") }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-label">Status: <span class="tx-danger">*</span></label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" id="status">
                                            <option @if(old("status",$payment_setting->status) == 0) selected @endif value="0">Disabled</option>
                                            <option @if(old("status",$payment_setting->status) == 1) selected @endif value="1">Active</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2 @if(old("status",$payment_setting->status) == 0) none @endif status-select">
                                        <label class="form-label">SSL Mode: <span class="tx-danger">*</span></label>
                                        <select name="ssl_mode" class="form-control @error('ssl_mode') is-invalid @enderror" id="ssl_mode">
                                            <option @if(old("ssl_mode",$payment_setting->mode) == 0) selected @endif value="0">Sandbox</option>
                                            <option @if(old("ssl_mode",$payment_setting->mode) == 1) selected @endif value="1">Live</option>
                                        </select>
                                        @error('ssl_mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                   
                                    <div class="col-sm-6 mt-2 @if(old("status",$payment_setting->status) == 0) none @endif status-select">
                                        <label class="form-label">SSL STORE ID</label>
                                        
                                        <input value="{{ old("store_id",$payment_setting->store_id) }}" type="text" placeholder="Enter SSL STORE ID" name="store_id" id="store_id" class="form-control @error('store_id') is-invalid @enderror">
                                        
                                        @error('store_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2 @if(old("status",$payment_setting->status) == 0) none @endif status-select">
                                        <label class="form-label">SSL STORE PASSWORD</label>
                                        
                                        <input value="{{ old("store_pass",$payment_setting->store_password) }}" type="text" placeholder="Enter SSL STORE PASSWORD" name="store_pass" id="store_pass" class="form-control @error('store_pass') is-invalid @enderror">
                                        
                                        @error('store_pass')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                        <button class="btn btn-info" id="cus-submit-btn">Save</button>
                                    </div>
                                </div>
                            </form>

                        </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            
           $("#status").on("change",function(){
                console.log($('.status-select'));
                if($(this).val() == 0){
                    $('.status-select').addClass("none");
                }else{
                    $('.status-select').removeClass("none");
                }
           });
           
        });

    </script>

@endsection
