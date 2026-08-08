@section('head')
<title>Admin - Social Media</title>
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
								{{ __('Social Media') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('backend.saveSocialMediaData') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="facebook">{{ __('Facebook') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="facebook" value="{{ $site_setting->facebook }}">
										</div>
                                    </div>
                                    <div class="col-md-4">
										<div class="form-group">
											<label for="twitter">{{ __('Twitter') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="twitter" value="{{ $site_setting->twitter }}">
										</div>
                                    </div>
                                    <div class="col-md-4">
										<div class="form-group">
											<label for="instagram">{{ __('Instagram') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="instagram" value="{{ $site_setting->twitter }}">
										</div>
                                    </div>
                                    <div class="col-md-4">
										<div class="form-group">
											<label for="youtube">{{ __('Youtube') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="youtube" value="{{ $site_setting->twitter }}">
										</div>
                                    </div>
                                    <div class="col-md-4">
										<div class="form-group">
											<label for="linkedin">{{ __('Linkedin') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="linkedin" value="{{ $site_setting->linkedin }}">
										</div>
                                    </div>
                                    <div class="col-md-4">
										<div class="form-group">
											<label for="google">{{ __('Google') }}<span class="red">*</span></label>
											<input type="text" class="form-control" name="google" value="{{ $site_setting->google }}">
										</div>
                                    </div>


								</div>

								<div class="row tabs-footer mt-5">
									<div class="col-lg-12">
										<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
									</div>
								</div>
							</form>
							<!--/Data Entry Form/-->
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
<!-- css/js -->
<script type="text/javascript">
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
