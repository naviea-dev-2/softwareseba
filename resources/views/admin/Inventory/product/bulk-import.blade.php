@extends('admin.inc.master')
@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Bulk Import</title>
<style>
    label{
        font-size: 1.2rem;
    }
    .error-show .select2-container--bootstrap-5 .select2-selection{
        border: 1px solid red;
    }
    .btn-loading{
        color:transparent!important;
        pointer-events:none;
        position:relative;
        text-shadow:none!important;
    }
    .btn-loading>*{opacity:0}
    .btn-loading:after{
        animation:spinner-border .75s linear infinite;
        border-right-color:currentcolor;
        border:2px solid;
        border-radius:100rem;
        border-right:2px solid transparent;
        color:#f6f8fb;
        content:"";
        display:inline-block;
        height:1.25rem;
        left:calc(50% - 1.25rem/2);
        position:absolute;
        top:calc(50% - 1.25rem/2);
        vertical-align:text-bottom;
        width:1.25rem;
    }
</style>
@endsection

@section('content')
<style>
    .hidden {
    display: none!important;
}
</style>
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
        <div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-6">
								<h3 style="color:black">{{ __('Import Product') }}</h3>
							</div>
							<div class="col-lg-6">
								<div class="text-right" style="text-align: right;">
									<a href="{{ route('product.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
                    <div class="card-body tabs-area p-0">
                        <form action="{{ route('admin.product.import') }}" method="POST" class="form-import-data" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-xxl-12 col-xl-12 col-lg-12 col-12">
                                    <div class="widget meta-boxes  p-2 border m-4">
                                        <div class="widget-title pl-2">
                                            <h4 style="color:white">{{ __('Product Import') }}</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="my-3">
                                                <label style="color:white;" for="">Business Type *</label>
                                                <Select id="business_type" name="business_type" class="form-control select2">
                                                    @foreach (b_types() as $k=>$business_type)
                                                        
                                                        <option @if(old('business_type',request()->business_type) == $k) selected @endif value="{{ $k }}">{{ $business_type }}</option>
                                                        
                                                    @endforeach
                                                </Select>
                                            </div>

                                            <div class="form-group mb-3 @if ($errors->has('file')) has-error @endif">
                                                {{-- <label
                                                    class="control-label required"
                                                    for="input-group-file"
                                                >
                                                    {{ __('Choose A File') }}
                                                </label> --}}
                                                <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" type="file" id="input-group-file" class="form-control" name="file" required aria-describedby="input-group-addon">

                                                <label
                                                    class="d-block mt-1 help-block"
                                                    for="input-group-file"\
                                                    style="font-size: 12px;"
                                                >
                                                    {{ __('Choose file with mime: (:types)', ['types' => implode(', ', ['xls','xlsx', 'csv',])]) }}
                                                </label>


                                                <div class="mt-3 text-center p-2 border bg-light">
                                                    <a
                                                        class="download-template"
                                                        data-url="{{ route('admin.import_download_template') }}"
                                                        data-extension="csv"
                                                        data-btype ="{{old('business_type',request()->business_type)}}"
                                                        data-filename="template_products_import.csv"
                                                        data-downloading="<i class='fas fa-spinner fa-spin'></i> {{ __('Download CSV Template') }}"
                                                        href="#"
                                                    >
                                                        <i class="fas fa-file-csv"></i>
                                                        {{ __('Download CSV Template') }}
                                                    </a> &nbsp; | &nbsp;
                                                    <a
                                                        class="download-template"
                                                        data-url="{{ route('admin.import_download_template') }}"
                                                        data-extension="xlsx"
                                                        data-btype ="{{old('business_type',request()->business_type)}}"
                                                        data-filename="template_products_import.xlsx"
                                                        data-downloading="<i class='fas fa-spinner fa-spin'></i> {{ __('Download Excel template') }}"
                                                        href="#"
                                                    >
                                                        <i class="fas fa-file-excel"></i>
                                                        {{ __('Download Excel template') }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="">
                                                <button
                                                    class="btn btn-primary"
                                                    id="input-group-addon"
                                                    data-choose-file="{{ __('Please choose the file') }}"
                                                    data-loading-text="{{ __('Importing') }}"
                                                    data-complete-text="{{ __('Imported successfully.') }}"
                                                    type="submit"
                                                >
                                                    {{ __('Start Import') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden main-form-message">
                                        <p id="imported-message"></p>
                                        <div class="show-errors hidden">
                                            <h3 class="text-warning text-center">{{ __('Failures') }}</h3>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#{{ __('Row') }}</th>
                                                        <th scope="col">{{ __('Attribute') }}</th>
                                                        <th scope="col">{{ __('Errors') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="imported-listing">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="widget meta-boxes mt-4 p-3">
                            <div class="widget-title pl-2">
                                <h4 class="text-info">{{ __('Import Rule') }}</h4>
                            </div>
                            <div class="widget-body">
                                <table class="table text-start table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Column') }}</th>
                                            <th scope="col">{{ __('Rules') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rules as $k => $rule)
                                            <tr>
                                                <th scope="row">{{ Arr::get($headings, $k) }}</th>
                                                <td>({{ $rule }})</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="widget meta-boxes p-3">
                            <div class="widget-title pl-2">
                                <h4 class="text-info">{{ __('Template') }}</h4>
                            </div>
                            <div class="widget-body">
                                <div class="table-responsive">
                                    <table class="table text-start table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($headings as $heading)
                                                    <th>{{ $heading }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $product)
                                                <tr>
                                                    @foreach ($headings as $k => $h)
                                                        <td>{{ Arr::get($product, $k) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ url('public/assets/js/bulk-import.js') }}"></script>
<script>
    $('#business_type').on('change',function(){
        window.location = '?business_type='+this.value;
    })
</script>
@endsection
