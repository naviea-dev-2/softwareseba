@section('head')
<title>Admin - Google Analytics</title>

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
								{{ __('Google Analytics') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('backend.saveGoogleAnalytics') }}" method="post" enctype="multipart/form-data">
                                @csrf
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="tracking_id">Tracking ID</label>
											<input value="{{ $datalist['tracking_id'] }}" type="text" name="tracking_id" id="tracking_id" class="form-control" placeholder="UA-123456789-1">
											<small class="form-text text-muted">e.g. <strong>Tracking ID: UA-123456789-1</strong></small>
										</div>
									</div>
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

@push('scripts')
<!-- css/js -->
<script src="{{asset('public/backend/pages/google_analytics.js')}}"></script>
@endpush
