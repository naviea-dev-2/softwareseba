@section('head')
<title>Admin - Free User Limit</title>
@endsection
@extends('admin.inc.master')
@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">

		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header theme-option-header">{{ __('User Limit') }}</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Custom JS-->
							<form action="{{ route('backend.free_user_limit') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="custom_js">{{ __('Days') }}</label>
											<input type="number" class="form-control" name="days" value="{{  $datalist['days'] }}">
										</div>
									</div>
								</div>
								<div class="row tabs-footer mt-5">
									<div class="col-lg-12">
										<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
									</div>
								</div>
							</form>
							<!--/Custom JS-->
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /main Section -->
@endsection
