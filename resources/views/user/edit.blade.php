@extends('inc.master')

@section('head')

<title>User Edit</title>
<style>

</style>
@endsection

@section('content')

<Form action="{{ route('bussiness.user.edit',$user->id) }}" method="POST" class="form form-horizontal custom-form-horizontal" enctype="multipart/form-data">
    @csrf
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Edit User</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group row">
                    <Label class="control-label col-md-4">User Image</Label>

                    <div class="col-md-8">
                        {{-- <p>Browse Your Image Here. <p>Max file 5MP in size.</p><p>JPG, PNG and GIF fomat.</p><p>Recommended size 300px X 200px.</p></p> --}}
                        <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 150px;">
                            <img class="display-upload-img-add" style="width: 150px;height: 70px;" src="{{ $user->image_show }}" alt="">
                            <input type="file" name="business_logo" class="form-control upload-img-add" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                        </div>
                    </div>
                </div>
            </div><!--/.col-12-->
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label"> Name *</Label>
                    <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name">

                    @if ($errors->has('name'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Email *</Label>
                    
                    <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email Address" value="{{ old('email') ? old('email') : $user->email }}">
                    @if ($errors->has('email'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label ">Mobile *</Label>
                    
                    <input type="text" name="mobile" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" placeholder="Mobile Number" value="{{ old('mobile') ? old('mobile') : $user->mobile }}">
                    @if ($errors->has('mobile'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('mobile') }}</strong>
                    </span>
                    @endif
                    
                </div>
            </div><!--/.col-6-->
            @if(auth()->user()->business->business_type_id != 17)
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Branch</Label>
                    <select class="form-control " id="branch" name="branch">
                        <option value="" disabled selected>-- Select One --</option>
                        @foreach ($branches as $branch)
                            <option @if($user->branch_id == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('branch'))
                    <span class="invalid-feedback mb-0">
                    <strong>{{ $errors->first('branch') }}</strong>
                    </span>
                    @endif
                </div>   
            </div>
            @endif
            <input type="hidden" name="user_type" value="{{$user->user_type}}"/>
            @if($user->user_type != 0)
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Role</Label>
                    <select class="form-control " id="role" name="role" required>
                        <option value="" disabled selected>-- Select One --</option>
                        @foreach ($roles as $role)
                            <option @if($user->role_id == $role->id) selected @endif value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>   
            </div>
            @endif
            
            <div class="col-6">
                <div class="form-group">
                    <Label class="control-label">Addres</Label>
                    
                    <input type="text" name="address" class="form-control" value="{{ old('address') ? old('address') : $user->address }}">

                    
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
