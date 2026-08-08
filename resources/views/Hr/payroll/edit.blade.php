@extends('inc.master')

@section('head')

<title>Edit Payroll</title>
<style>
    label{
        /* font-size: 1.2rem; */
    }
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-8">

                        <h6 class="br-section-label text-center mb-1">Edit Payroll</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form action="{{route('updatePayroll',$p->id)}}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">House Rent(%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("house_rent",$p->house_rent) }}" placeholder="House Rent" name="house_rent" id="house_rent" class="form-control @error('house_rent') is-invalid @enderror" >
                                        @error('house_rent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Medical Cost(%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("medical_cost",$p->medical_cost) }}" placeholder="Medical Cost" name="medical_cost" id="medical_cost" class="form-control @error('medical_cost') is-invalid @enderror" >
                                        @error('medical_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Transpot Cost(%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("transport_cost",$p->transport_cost) }}" placeholder="Transpot Cost" name="transport_cost" id="transport_cost" class="form-control @error('transport_cost') is-invalid @enderror" >
                                        @error('transport_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Tax(%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("tax",$p->tax) }}" placeholder="Transpot Cost" name="tax" id="tax" class="form-control @error('tax') is-invalid @enderror" >
                                        @error('tax')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Provident Fund(%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("provident_fund",$p->provident_fund) }}" placeholder="Provident Fund" name="provident_fund" id="provident_fund" class="form-control @error('provident_fund') is-invalid @enderror" >
                                        @error('provident_fund')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                  
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                    {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                    <button class="btn btn-info" id="cus-submit-btn">Update</button>
                                    </div>
                                </div>
                            </form>

                        </div>


                </div>
            </div>
        </div>
    </div>
</div>

@stop

