@extends('inc.master')

@section('head')

<title>Add Salary</title>
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

                    <h6 class="br-section-label text-center mb-1">Add Salary</h6>
                    <div id="create_errors"></div>

                    <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                        <form id="data-form-create" action="{{ route('storeSalary') }}" method="post" enctype="multipart/form-data">
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
                                    <label class="form-control-label">Employee: <span class="tx-danger">*</span></label>
                                    <input type="hidden" name="empID_h" id="empID_h" value="{{ old("empID_h") }}"/>
                                    <select name="empID" class="form-control @error('empID') is-invalid @enderror" id="empID">
                                        <option value=""> Select Land Plot</option>
                                        @if(old("empID"))
                                            <option value="{{ old("empID") }}" selected>{{ old("empID_h") }}</option>
                                        @endif
                                    </select>
                                    @error('land_plot')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                               
                                
                            </div>



                            <div class="row mt-3 mb-3">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                                {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                                <button type="btn" class="btn btn-info" id="cus-submit-btn">Add Salary</button>
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
var variableName = "{{ date('Y-m') }}";
    const myInput = document.querySelector(".datetimepicker");
flatpickr(document.querySelector(".datetimepicker"), {
    altInput:true,
    defaultDate: new Date(variableName),
    plugins: [
        new monthSelectPlugin({
        shorthand: true, //defaults to false
        dateFormat: "Y-m", //defaults to "F Y"
        altFormat: "Y-m", //defaults to "F Y"
        theme: "dark" // defaults to "light"
        })
    ]
});
        $("#empID").select2({
            theme: "bootstrap-5",
            placeholder:"Select Employee" ,
            allowClear: true,
            width:'100%',
            dropdownAutoWidth : true,
            ajax: {
                url: "{{ route('select2.employee') }}",
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
        }).on('select2:select', function (e) {
            var data = e.params.data;
            $(id+"_h").val(data.text);
        });
</script>
@endsection
