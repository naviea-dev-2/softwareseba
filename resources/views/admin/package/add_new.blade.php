@extends('admin.inc.master')

@section('head')

<title>Add Package to Bussiness</title>
<style>

</style>
@endsection

@section('content')

<Form action="{{ route('admin.package.order',$business->id) }}" method="POST" class="form form-horizontal custom-form-horizontal" enctype="multipart/form-data">
    @csrf
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Add Package to Bussiness</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
        @if($order)
            <h5>Active Package</h5>
            <div class="row mt-2">
                <div class="col-4">
                    <div class="form-group">
                        <Label class="control-label">Package Name :</Label>
                        <Label class="control-label">{{ $order->package?->name }}</Label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <Label class="control-label">Package End Date :</Label>
                        <Label class="control-label">{{ date('Y-m-d', strtotime($order->end_date)) }}</Label>
                    </div>
                </div>
                @if(\Carbon\Carbon::now()->lte($order->end_date) == false)
                 <div class="col-4">
                    <div class="form-group">
                        <Label class="control-label">Status :</Label>
                        <Label class="control-label">Expired</Label>
                    </div>
                </div>
                @endif
            </div>
            <hr/>
        @endif
        <div class="row mt-2">
            <div class="col-6" >
                <div class="form-group row mt-2">
                    <Label class="control-label col-md-4">Package</Label>
                    <input type="hidden" id="h_package" name="h_package" value="{{old('h_package')}}"/>
                    <div class="col-md-8">
                        <Select id="package" name="package" class="form-control">
                            
                        </Select>
                    </div>
                </div>
            </div>
            <div class="col-6 ">
                <div class="form-group row mt-2">
                    <Label class="control-label col-md-4">End Date</Label>
                    <div class="col-md-8">
                        <input class="form-control" id="end_date" name="end_date" type="date" value="{{old('start_date',\Carbon\Carbon::parse($business->start_date)->format('Y-m-d'))}}">
                    </div>
                </div>
            </div><!--/.col-6-->
        </div>
       
        <div class="card-btns mt-4 mb-2 me-2" style="text-align: right;">
            <button type="submit" class="btn btn-primary" >Save</button>
        </div>
    </div><!--/.card-body-->
  </div><!--/.card-content-->
</div><!--/.card-->
</Form>
@endsection

@section('script')


<script type="text/javascript">
    $('#package').on('change',function(){
        $.ajax({
            url: "{{url('admin/package/get_end_date') }}/"+$(this).val().trim(),
            method: 'GET',
            data:{
                pack_id:$(this).val().trim(),
            },
            success: function(data) {
                console.log(data);
                $('#end_date').val(data);
            }
        });
    });
   
    $('#package').select2({
        theme: "bootstrap-5",
        placeholder: 'Select Package',
        allowClear: true,
        width:'100%',
        dropdownAutoWidth : true,
        containerCssClass: 'select-sm',
        ajax: {
            url: '{{route('select2.package')}}',
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
        $('#h_package').val(data.text);

    });
    @if(old('package'))
        var package_option = new Option("{!! old('h_package') !!}","{{  old('package') }}", true, true);
        $('#package').append(package_option).trigger('change');
    @endif
</script>
@endsection
