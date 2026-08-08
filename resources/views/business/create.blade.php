@include('BackEnd.assets.dropzone-assets')
@extends('BackEnd.layouts.master')

@section('title', __('lang.business').' -> '.__('lang.create'))

@section('breadcrumb')
<div class="content-header row">
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-left mb-0">{{__('lang.business')}}</h2>
        <div class="breadcrumb-wrapper col-12">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('lang.dashboard')}}</a></li>
            <li class="breadcrumb-item active">{{__('lang.profile')}}</li>
            <li class="breadcrumb-item"><a href="{{route('business.index')}}">{{__('lang.businesses')}}</a></li>
            <li class="breadcrumb-item active">{{__('lang.create')}}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content-header-right text-md-right col-md-3 col-12">
    <div class="form-group breadcrum-right">
      <a href="{{route('business.index')}}" class="btn btn-outline-warning"><span><i class="feather icon-arrow-left"></i> {{ __('lang.back') }}</span></a>
    </div>
  </div>
</div><!--/.content-header-->
@endsection

@section('content')
{{Form::open(['route'=>'business.store', 'class'=>'form form-horizontal custom-form-horizontal'])}}
<div class="card">
  <div class="card-header">
    <h4 class="card-title">{{__('lang.add_new_business')}}</h4>
  </div>
  <hr class="my-1">
  <div class="card-content">
    <div class="card-body">
      <div class="d-flex justify-content-center">
        <div class="col-sm-8">
          <div class="form-group row">
            {{Form::label('business_name', __('lang.business_name').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::text('business_name', null, ['class'=>'form-control '.($errors->has('business_name') ? 'is-invalid' : ''), 'placeholder'=>__('lang.business_name')])}}
              @if ($errors->has('business_name'))
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('business_name') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            {{Form::label('phone_number', __('lang.phone').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::number('phone_number', null, ['class'=>'form-control '.($errors->has('phone_number') ? 'is-invalid' : ''), 'placeholder'=>__('lang.phone')])}}
              @if ($errors->has('phone_number'))
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('phone_number') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class="form-group row">
            {{Form::label('email_address', __('lang.email_address').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::text('email_address', null, ['class'=>'form-control '.($errors->has('email_address') ? 'is-invalid' : ''), 'placeholder'=>__('lang.email_address')])}}
              @if ($errors->has('email_address'))
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('email_address') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group row">
            {{Form::label('business_type_id', __('lang.type_of_business').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::select('business_type_id', business_types(), null, ['class'=>'form-control select2 '.($errors->has('business_type_id') ? 'is-invalid' : '')])}}
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('business_type_id') }}</strong>
              </span>
            </div>
          </div>

          <div class="form-group row">
            {{Form::label('country_id', __('lang.country').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::select('country_id', App\Models\Settings\Country::options(), null, ['class'=>'form-control select2 '.($errors->has('country_id') ? 'is-invalid' : '')])}}
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('country_id') }}</strong>
              </span>
            </div>
          </div>

          <div class="form-group row">
            {{Form::label('currency_id', __('lang.business_currency').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::select('currency_id', App\Models\Settings\Currency::options(), null, ['class'=>'form-control select2 '.($errors->has('currency_id') ? 'is-invalid' : '')])}}
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('currency_id') }}</strong>
              </span>
            </div>
          </div>

          <div class="form-group row">
            {{Form::label('organization_type_id', __('lang.type_of_organization').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::select('organization_type_id', organization_types(), null, ['class'=>'form-control select2 '.($errors->has('organization_type_id') ? 'is-invalid' : '')])}}
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('organization_type_id') }}</strong>
              </span>
            </div>
          </div>

          <div class="form-group row">
            {{Form::label('business_logo', __('lang.logo'), ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              <div class="dropzone business_logo-dropzone dropzone-area dz-clickable invoice-logo-dropzone single-dropzone-thumbnail" style="min-height: 150px; width: 220px;padding: 0;">
                <input type="hidden" name="business_logo" value="">
                <div class="dz-message" style="top: 11%;"></div>
              </div>
            </div>
          </div>
          {{-- <div class="form-group row">
            {{Form::label('invoice_footer', __('lang.invoice_footer').' *', ['class'=>'control-label col-md-4'])}}
            <div class="col-md-8">
              {{Form::text('invoice_footer', null, ['class'=>'form-control '.($errors->has('invoice_footer') ? 'is-invalid' : ''), 'placeholder'=>__('lang.invoice_footer')])}}
              @if ($errors->has('invoice_footer'))
              <span class="invalid-feedback mb-0">
                <strong>{{ $errors->first('invoice_footer') }}</strong>
              </span>
              @endif
            </div>
          </div> --}}

          <div class="card-btns mt-2">
            <button type="submit" class="btn btn-primary float-right">{{ __('lang.save') }}</button>
          </div>
        </div><!--/.col-sm-8-->
      </div><!--/.d-flex justify-content-center-->
    </div><!--/.card-body-->
  </div><!--/.card-content-->
</div><!--/.card-->
{{Form::close()}}
@endsection

@section('page-script')
<script type="text/javascript">
  Dropzone.autoDiscover = false;
  $(function(){
    var business_logo = $(".business_logo-dropzone").dropzone({
      url: "{{route('media.store')}}",
      maxFilesize:1,
      maxFiles:1,
      uploadMultiple: false,
      parallelUploads: 1,
      thumbnailWidth: null,
      thumbnailHeight: null,
      dictFileTooBig: 'File is larger than 1MB',
      paramName:"file",
      createImageThumbnails:true,
      acceptedFiles: ".jpeg,.jpg,.png",
      dictRemoveFileConfirmation: "Are you sure?",
      previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-details"><div class="dz-filename"><span data-dz-name></span></div><img data-dz-thumbnail /></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-success-mark"><a><i style="cursor: pointer;" class="feather icon-check"></i></a><a data-dz-remove name="" class="text-danger"><i style="cursor: pointer;" class="feather icon-x"></i></a></div><div class="dz-error-mark"><a class="text-danger"><i style="cursor: pointer;" class="feather icon-check"></i></a><a data-dz-remove name="" class="text-danger"><i style="cursor: pointer;" class="feather icon-x"></i></a> <div class="dz-error-message"><span data-dz-errormessage></span></div></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div>',
      headers: {
        'x-csrf-token': "{{csrf_token()}}",
      },

      success: function (file, response) {
        $('input[name="business_logo"]').val(response.name);
        $("[data-dz-remove]").attr('name', response.name)
      },

      removedfile: function(file) {
        var name = file.previewElement.querySelector("[data-dz-remove]").name;
        if(name !="" || name != null){
          $.ajax({
            type: 'POST',
            url: '{{route('media.delete')}}',
            data: {op: "delete", name: name, _token:"{{csrf_token()}}"},
            success: function(data){
              file.previewElement.remove();
              $('input[name="business_logo"]').val('');
            }
          });
        }
      },
    });
  });
</script>
@endsection
