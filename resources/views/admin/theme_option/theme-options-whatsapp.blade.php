@section('head')
<title>Admin - Whatsapp</title>

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
								{{ __('Whatsapp') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('backend.saveSocialMediaData') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="whatsapp_id">Whatsapp Phone Number</label>
											<input value="{{ $datalist['whatsapp_id'] }}" type="text" name="whatsapp_id" id="whatsapp_id" class="form-control" placeholder="0123456789">
										</div>
									</div>
									<div class="col-lg-6"></div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="whatsapp_text">Text</label>
											<input value="{{ $datalist['whatsapp_text'] }}" type="text" name="whatsapp_text" id="whatsapp_text" class="form-control" placeholder="Text..">
										</div>
									</div>
									<div class="col-lg-6"></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="position">Position</label>
											<select name="position" id="position" class="chosen-select form-control">
												<option {{ 'left' == $datalist['position'] ? "selected=selected" : '' }} value="left">Left</option>
												<option {{ 'right' == $datalist['position'] ? "selected=selected" : '' }} value="right">Right</option>
											</select>
										</div>
									</div>
									<div class="col-md-8"></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="is_publish">{{ __('Status') }}</label>
											<select name="is_publish" id="is_publish" class="chosen-select form-control">
											    <option {{ 1 == $datalist['is_publish'] ? "selected=selected" : '' }} value="1">
													Active
												</option>
                                                <option {{ 0 == $datalist['is_publish'] ? "selected=selected" : '' }} value="0">
													InActive
												</option>
											</select>
										</div>
									</div>
									<div class="col-md-8"></div>
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


