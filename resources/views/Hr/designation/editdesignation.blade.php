@extends('inc.master')

@section('head')

<title>Edit Designaton</title>
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
                <div class="col-md-4">

                        <h6 class="br-section-label text-center mb-1">Edit Designation</h6>
                        <div id="create_errors"></div>

                        <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                          <form action="{{ route('updateDesignation',$designation->id) }}" method="post">
                            @csrf
                            <div class="row">
                              <div class="col-sm-12 mt-2">
                                <label class="form-control-label">Department: <span class="tx-danger">*</span></label>
                                <select name="department_id" id="" class="form-control {{ $errors->has('department_id') ? 'is-invalid' : '' }}">
                                  <option value="">Select Department</option>
                                  @foreach ($departments as $cate )
                                    <option @if(old('department_id',$designation->department_id) == $cate->id) selected @endif value="{{ $cate->id }}">{{ $cate->name }}</option>
                                  @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                              </div>
                              <div class="col-sm-12 mt-2">
                                <label class="form-control-label">Designation Name: <span class="tx-danger">*</span></label>
                                <input type="text" value="{{ old("name",$designation->name) }}" placeholder="Enter Name" name="name" id="name" class="form-control fl-datepicker @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                              </div>
                              @if(auth()->user()->business->business_type_id == 15)
                                <div class="col-sm-12 mt-2">
                                  <label class="form-control-label">Type: <span class="tx-danger">*</span></label>
                                  <select name="type" id="" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                    <option  @if(old('type',$designation->type) == 0) selected @endif value="0">None</option>
                                    <option  @if(old('type',$designation->type) == 1) selected @endif value="1">DSR</option>
                                  </select>
                                  @error('type')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                </div>
                              @endif
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
