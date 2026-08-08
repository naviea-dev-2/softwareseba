@section('head')
<title>Admin - Color</title>
<style>
    .tw-picker .input-group-addon:last-child {
        border-left: 0;
    }
    .tw-picker .input-group-addon:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .tw-picker .input-group-addon {
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        cursor: pointer;
    }
</style>
@endsection
@extends('inc.master')
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
								{{ __('Color') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form action="{{ route('saveThemeOptionsColor') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <h3>Header</h3>
                                <div class="border">
                                    <div class="container p-3">
                                        <div class="row m-0">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Background color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="header_back_color" id="header_back_color" type="text" value="{{ $datalist['header_back_color'] == '' ? '#61a402' : $datalist['header_back_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Font color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="header_font_color" id="header_font_color" type="text" value="{{ $datalist['header_font_color'] == '' ? '#61a402' : $datalist['header_font_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Button Background Color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="header_btn_back_color" id="header_btn_back_color" type="text" value="{{ $datalist['header_btn_back_color'] == '' ? '#61a402' : $datalist['header_btn_back_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Button Font Color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="header_btn_font_color" id="header_btn_font_color" type="text" value="{{ $datalist['header_btn_font_color'] == '' ? '#61a402' : $datalist['header_btn_font_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
								<h3>Sideabar</h3>
                                <div class="border">
                                    <div class="container p-3">
                                        <div class="row m-0">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Background color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="sidebar_back_color" id="sidebar_back_color" type="text" value="{{ $datalist['sidebar_back_color'] == '' ? '#61a402' : $datalist['sidebar_back_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Font color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="sidebar_font_color" id="sidebar_font_color" type="text" value="{{ $datalist['sidebar_font_color'] == '' ? '#61a402' : $datalist['sidebar_font_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Hover Background color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="sidebar_back_hover_color" id="sidebar_back_hover_color" type="text" value="{{ $datalist['sidebar_back_hover_color'] == '' ? '#61a402' : $datalist['sidebar_back_hover_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ __('Hover Font color') }}<span class="red">*</span></label>
                                                    <div class="input-group tw-picker color_picker_theme">
                                                        <input name="sidebar_font_hover_color" id="sidebar_font_hover_color" type="text" value="{{ $datalist['sidebar_font_hover_color'] == '' ? '#61a402' : $datalist['sidebar_font_hover_color'] }}" class="form-control color_picker_theme"/>
                                                        <span class="input-group-addon"><i></i></span>
                                                    </div>
                                                </div>
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
<link rel="stylesheet" href="{{asset('public/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<script src="{{asset('public/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script>
    $(".color_picker_theme").colorpicker({
        format: "hex", //format - hex | rgb | rgba.
    });
</script>
@endsection
