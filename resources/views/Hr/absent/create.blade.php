@extends('inc.master')

@section('head')

<title>Add Absent</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
</style>
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pagebody">
        <div class="br-section-wrapper pt-4">
            <div class="row">
                <div class="col-md-8">

                        <h6 class="br-section-label text-center mb-1">Add Absent</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                            <form action="{{route('storeAbsent')}}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">First Absent Amount (%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("first") }}" placeholder="First Absent Amount (%)" name="first" id="first" class="form-control @error('first') is-invalid @enderror" >
                                        @error('first')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Other Absent Amount (%): <span class="tx-danger">*</span></label>
                                        <input type="number" value="{{ old("other") }}" placeholder="Other Absent Amount (%)" name="other" id="other" class="form-control @error('other') is-invalid @enderror" >
                                        @error('other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                    {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
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
@stop

