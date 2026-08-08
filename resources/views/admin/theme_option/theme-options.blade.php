@section('head')
<title>Admin - Theme Option</title>

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
								{{ __('Theme Options') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('backend.saveThemeLogo') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-md-4">

										<div class="form-group">
											<label for="favicon">{{ __('Favicon') }}<span class="red">*</span></label>
											<div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                                <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ $site_setting->favicon == '' ? $site_setting->no_image : $site_setting->favicon_show}}" alt="">
                                                <input type="file" name="favicon" class="form-control upload-img" placeholder="Enter Activity Image"  style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                            </div>

										</div>

                                    </div>
                                    <div class="col-md-4">

										<div class="form-group">
											<label for="back_logo">{{ __('Header Logo') }}<span class="red">*</span></label>
											<div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 76px;">
                                                <img class="display-upload-img" style="width: 76px;height: 70px;" src="{{ $site_setting->header_image == '' ? $site_setting->no_image : $site_setting->header_image_show}}" alt="">
                                                    <input type="file" name="header_logo" class="form-control upload-img" placeholder="Enter Activity Image"
                                                    style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                            </div>

										</div>

									</div>
                                </div>
                                <div class="row mt-3">
                                     <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Company Name:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" value="{{ $site_setting->company_name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Email:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="email" class="form-control" placeholder="Enter Email" value="{{ $site_setting->email1 ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Help Phone:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="phone" class="form-control" placeholder="Enter Help Phone" value="{{ $site_setting->phone1 ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Company Establish Year:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="company_establish_year" class="form-control" placeholder="Enter Establish Year" value="{{ $site_setting->company_establish_year ?? '' }}">
                                        </div>
                                    </div>



                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Copy Right's' Text:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="right_text"  value="{{ $site_setting->right_text ?? '' }}" class="form-control" placeholder="Enter Copy Rights">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Software Service Slogan:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="software_service_slogan" class="form-control" placeholder="Software Service Slogan" value="{{ $site_setting->software_service_slogan ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <label class=" form-control-label">Redirect URL:<span class="tx-danger"></span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                        <input type="text" name="redirect_url" class="form-control" placeholder="Redirect url" value="{{ $site_setting->redirect_url ?? '' }}">
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
