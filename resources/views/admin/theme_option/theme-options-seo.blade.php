@section('head')
<title>Admin -SEO
</title>

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
								{{ __('SEO') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('backend.saveThemeOptionsSEO') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="og_title">{{ __('SEO Title') }}</label>
											<input value="{{ $datalist['og_title'] }}" type="text" name="og_title" id="og_title" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="og_keywords">{{ __('SEO Keywords') }}</label>
											<input value="{{ $datalist['og_keywords'] }}" type="text" name="og_keywords" id="og_keywords" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="og_description">{{ __('SEO Description') }}</label>
											<textarea name="og_description" id="og_description" class="form-control" rows="2">{{ $datalist['og_description'] }}</textarea>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="og_image">{{ __('Open Graph Image') }}</label>
                                            <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                                <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ $datalist['og_image'] }}" alt="">
                                                <input type="file" name="og_image" class="form-control upload-img" placeholder="Enter Activity Image"  style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                            </div>

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
