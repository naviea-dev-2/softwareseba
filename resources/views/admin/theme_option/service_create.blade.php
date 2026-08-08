@section('head')
<title>Admin - Software Service</title>
<style>
    .invalid-feedback{
        display: block;
    }
</style>
@endsection
@extends('admin.inc.master')
@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">

		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header theme-option-header">
						<div class="row">
							<div class="col-lg-12">
								{{ __('New Software Service') }}
							</div>
						</div>
					</div>
                    <div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
                            <form action="{{ route('backend.store_soft_service') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="custom_js">{{ __('Title') }}</label>
											<input value="{{ old('title') }}" type="text" name="title" class="form-control" placeholder="Title">

										</div>
                                        @error('title')
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @enderror
									</div>
                                    <div class="col-lg-6">
										<div class="form-group">
											<label for="custom_js">{{ __('URL') }}</label>
											<input value="{{ old('url') }}" type="text" name="url" class="form-control" placeholder="URL">
										</div>
                                        @if($errors->has('url'))
                                            <span class="invalid-feedback mb-0">
                                            <strong>{{ $errors->first('url') }}</strong>
                                            </span>
                                        @endif
									</div>
                                    <div class="col-md-4 mt-3">

										<div class="form-group">
											<label for="back_logo">{{ __('Logo') }}<span class="red">*</span></label>
											<div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                                <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ asset("public/images/No-image.jpg")}}" alt="">
                                                    <input type="file" name="logo" class="form-control upload-img" placeholder="Enter Activity Image"
                                                    style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                            </div>
                                             @if($errors->has('logo'))
                                                <span class="invalid-feedback mb-0">
                                                <strong>{{ $errors->first('logo') }}</strong>
                                                </span>
                                            @endif

										</div>

									</div>
								</div>
								<div class="row tabs-footer mt-5">
									<div class="col-lg-12">
										<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /main Section -->


@endsection
@section('script')
<script>

    $(document).on('change','.upload-img',function(){
        var files = $(this).get(0).files;
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        var arg=this;
        reader.addEventListener("load", function(e) {
            console.log($(arg).parent().find('.display-upload-img'));
            var image = e.target.result;
            $(arg).parent().find('.display-upload-img').attr('src', image);
        });
    });
</script>
@endsection
