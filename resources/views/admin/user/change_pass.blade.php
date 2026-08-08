@extends('admin.inc.master')

@section('head')

<title>Change Password</title>
<style>

</style>
@endsection

@section('content')

<Form action="{{ route('admin.user.change_password',$user->id) }}" method="POST" class="form form-horizontal custom-form-horizontal" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="is_profile" value="{{$is_profile}}"/>
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Change Password</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
        
        <div class="row mt-2">
            @if($is_profile == 1)
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label"> Old Password *</Label>
                    <input type="password" name="old_password" value="{{ old('old_password') }}" class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" placeholder="Old Password">

                    @if ($errors->has('old_password'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('old_password') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            @endif
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">New Password *</Label>
                    
                    <input type="password" name="new_password" class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}" placeholder="New Password" value="{{ old('new_password')}}">
                    @if ($errors->has('new_password'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('new_password') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            
           
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
