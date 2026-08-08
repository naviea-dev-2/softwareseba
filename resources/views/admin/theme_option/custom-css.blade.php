@section('head')
<title>Admin - Custom CSS</title>
@endsection
@extends('admin.inc.master')
@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">

		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header theme-option-header">{{ __('Custom CSS') }}</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Custom CSS-->
							<form action="{{ route('backend.saveCustomCSS') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="custom_css">{{ __('Custom CSS') }}</label>
											<textarea name="custom_css" id="custom_css" class="form-control" rows="13">{{ $datalist['custom_css'] }}</textarea>
											<small class="form-text text-muted">Paste your custom CSS code here</small>
										</div>
									</div>
								</div>
								<div class="row tabs-footer mt-5">
									<div class="col-lg-12">
										<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
									</div>
								</div>
							</form>
							<!--/Custom CSS-->
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /main Section -->
@endsection
