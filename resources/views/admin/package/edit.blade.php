@extends('admin.inc.master')

@section('head')

<title>Package Edit</title>
<style>

</style>
@endsection

@section('content')

<Form action="{{ route('admin.package.edit',$package->id) }}" method="POST" class="form form-horizontal custom-form-horizontal" enctype="multipart/form-data">
    @csrf
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Edit Package</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
        
        <div class="row mt-2">
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label"> Name *</Label>
                    <input type="text" name="name" value="{{ old('name',$package->name) }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name">

                    @if ($errors->has('name'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            
           <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Amount *</Label>
                    
                    <input type="text" name="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" placeholder="Amount" value="{{ old('amount') ? old('amount') : $package->amount }}">
                    @if ($errors->has('amount'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                    @endif

                   
                    
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Package Type*</Label>
                    <select id="pack_type" name="pack_type" class="form-control">
                        <option @if(old('pack_type',$package->pack_type) == 'month') selected @endif value="month">Monthly</option>
                        <option @if(old('pack_type',$package->pack_type) == 'year') selected @endif value="year">Yearly</option>
                    </select>
                    @if ($errors->has('pack_type'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('pack_type') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Duration*</Label>
                    <input type="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="duration" value="{{ old('duration',$package->duration) }}" class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" placeholder="Duration">

                    @if ($errors->has('duration'))
                        <span class="invalid-feedback mb-0">
                        <strong>{{ $errors->first('duration') }}</strong>
                        </span>
                    @endif 
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group mt-2 mb-3">
                    <Label class="control-label">Option *</Label>
                    
                    <div class="form-check">
                        <input @if(array_search('inventory',old('option',json_decode($package->pack_option,true))) != false) checked @endif class="form-check-input" type="checkbox" value="inventory" id="inventory" name="option[1]">
                        <label class="form-check-label" for="inventory">
                            Inventory
                        </label>
                    </div>
                    <div class="form-check">
                        <input @if(array_search('hr-payroll',old('option',json_decode($package->pack_option,true))) != false) checked @endif class="form-check-input" type="checkbox" value="hr-payroll" id="hr-payroll" name="option[2]">
                        <label class="form-check-label" for="hr-payroll">
                            HR & Payroll
                        </label>
                    </div>
                    <div class="form-check">
                        <input @if(array_search('accounts',old('option',json_decode($package->pack_option,true))) != false) checked @endif class="form-check-input" type="checkbox" value="accounts" id="accounts" name="option[3]">
                        <label class="form-check-label" for="accounts">
                            Accounts
                        </label>
                    </div>
                    <div class="form-check">
                        <input @if(array_search('crm',old('option',json_decode($package->pack_option,true))) != false) checked @endif class="form-check-input" type="checkbox" value="crm" id="crm" name="option[4]">
                        <label class="form-check-label" for="crm">
                            CRM
                        </label>
                    </div>
                   @if ($errors->has('option'))
                    <span class="invalid-feedback mb-0" style="display:block;">
                    <strong>{{ $errors->first('option') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            
            
             <div class="col-8">
                <div class="form-group">
                    <Label class="control-label">Description</Label>
                    <textarea class="form-control" name="description">{{ old('description',$package->description) }}</textarea>
        
                </div>
            </div><!--/.col-12-->
        </div>
        <div class="card-btns mt-4 mb-2 me-2" style="text-align: right;">
            <button type="submit" class="btn btn-primary" >Update</button>
        </div>
    </div><!--/.card-body-->
  </div><!--/.card-content-->
</div><!--/.card-->
</Form>
@endsection

@section('script')


<script type="text/javascript">
    $(document).on('change','.upload-img-add',function(){
        var files = $(this).get(0).files;
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        var arg=this;
        reader.addEventListener("load", function(e) {
            var image = e.target.result;
            $(arg).parent().find('.display-upload-img-add').attr('src', image);
        });
    });
  $('.select2').select2();
</script>
@endsection
