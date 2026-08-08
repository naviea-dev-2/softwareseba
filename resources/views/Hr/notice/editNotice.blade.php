@extends('inc.master')

@section('head')

<title>Edit Notice</title>
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

                    <h6 class="br-section-label text-center mb-1">Edit Notice</h6>
                    <div id="create_errors"></div>

                    <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                        <form id="data-form-create" action="{{route('updateNotice',$notice->id)}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                               
                                <div class="col-sm-6 mt-2">
                                    <label class="form-control-label">Notice Type: <span class="tx-danger">*</span></label>
                                    <select name="notice" class="form-control @error('notice') is-invalid @enderror" id="notice">
                                        <option value=""> Select Notice Type</option>
                                        <option @if(old('notice',$notice->notice_type) == 'Daily Notice') selected @endif value="Daily Notice">Daily Notice</option>
                                        <option @if(old('notice',$notice->notice_type) == 'Monthly Notice') selected @endif value="Monthly Notice">Monthly Notice</option>
                                        <option @if(old('notice',$notice->notice_type) == 'Yearly Notice') selected @endif value="Yearly Notice">Yearly Notice</option>
                                        <option @if(old('notice',$notice->notice_type) == 'Instant Notice') selected @endif value="Instant Notice">Instant Notice</option>
                                    </select>
                                    @error('notice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                               
                                <div class="col-sm-6 mt-2">
                                    <label class="form-control-label">Notice Name</label>
                                    <input type="text" value="{{ old("name",$notice->notice_name) }}" placeholder="Enter Name" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6 mt-2">
                                    <label class="form-control-label">Status: <span class="tx-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option @if(old('status',$notice->notice_status) == "active") selected @endif value="active">Active</option>
                                        <option @if(old('status',$notice->notice_status) == "deactive") selected @endif value="deactive">Deactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                               
                                <div class="col-sm-12 mt-2">
                                    <label class="form-control-label">Description:</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" rows="2" name="description" id="description">{{ old("description",$notice->notice_description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>



                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                <button type="submit" class="btn btn-info" id="cus-submit-btn">Update</button>
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
