@include('BackEnd.assets.datatables-assets')

@extends('BackEnd.layouts.master')

@section('title', __('lang.business'))

@section('breadcrumb')
<div class="content-header row">
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-left mb-0">{{__('lang.business')}}</h2>
        <div class="breadcrumb-wrapper col-12">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('lang.dashboard')}}</a></li>
            <li class="breadcrumb-item active">{{__('Profile')}}</li>
            <li class="breadcrumb-item active">{{__('Businesses')}}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content-header-right text-md-right col-md-3 col-12">
    <div class="form-group breadcrum-right">
    @can('businesses.create')
        
    <a href="{{route('business.create')}}" class="btn btn-outline-primary"><span><i class="feather icon-plus"></i> {{__('lang.add_new')}}</span></a>
    @endcan

    </div>
  </div>
</div><!--/.content-header-->
@endsection
@section('content')
<section>
  {{-- DataTable starts --}}
  <div class="table-responsive">
    <table id="business-datasource" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th class="no-sort">{{ __('lang.id') }}</th>
              <th>{{ __('lang.business_name') }}</th>
              <th>{{ __('lang.type_of_business') }}</th>
              <th>{{ __('lang.country') }}</th>
              <th>{{ __('lang.business_currency') }}</th>
              <th>{{ __('lang.type_of_organization') }}</th>
              <th class="no-sort">{{ __('lang.options') }}</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
  </div>
  {{-- DataTable ends --}}
</section>
@endsection

@section('page-script')
  <script type="text/javascript">
  $(document).ready(function() {
    "use strict"

    var datatable = $('#business-datasource').DataTable({
      "processing": true,
      "serverSide": true,
      "searching" : true,
      "ajax":{
        "url": "{{ route('business.datasource') }}",
        "data": {"_token": "{{ csrf_token() }}"},
        "dataType": "json",
        "type": "POST",
      },
      "columns": [
        { "data": "id"},
        { "data": "business_name"},
        { "data": "business_type_id"},
        { "data": "country_id"},
        { "data": "currency_id"},
        { "data": "organization_type_id"},
        { "data": "options"}
      ],
      "order": [[ 0, "asc" ]],
      "columnDefs": [
        {"targets"  : 'no-sort',"orderable": false},
        {"targets": [0,4,6],"className": 'text-center'}
      ]
    });

    $(document.body).on('click', '.restore_business', function(){
      var id = $(this).attr('business_id');
      Swal.fire({
		      title: 'Restore the Bussiness?',
          text: "This will restore your bussiness from every menu and you can access it. You can always archive the bussiness later.",
		      type: 'info',
		      showCancelButton: true,
		      confirmButtonColor: '#3085d6',
		      cancelButtonColor: '#d33',
          confirmButtonText: '{{__("lang.yes_restore_it")}}',
          cancelButtonText: '{{__("lang.cancel")}}',
		      confirmButtonClass: 'btn btn-primary',
		      cancelButtonClass: 'btn btn-danger ml-1',
		      buttonsStyling: false,
		    }).then(function (result) {
		      if (result.value) {
      $.ajax({
              method:'get',
              url:'{{route("business.restore", '')}}/'+id,
              data:{_token:"{{csrf_token()}}"},
              dataType:'json',
			        success:function(response){
                             console.log('restored');    
			            toastr.success("Bussiness restored Successfully", 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });

                  location.reload()
			          if (0 == response.status) {
			            toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
			          }
                datatable.draw();
			        }
			    });

        }
		      else if (result.dismiss === Swal.DismissReason.cancel) {
		        Swal.fire({
		          title: 'Cancelled',
		          text: 'Your Bussiness is not restored',
		          type: 'info',
		          confirmButtonClass: 'btn btn-success',
		        })
		      }
		    });
    });

        $(document.body).on('click', '.delete_business', function(){
      var id = $(this).attr('business_id');

      Swal.fire({
		      title: 'Archive the Bussiness?',
          text: "This will hide your bussiness from every menu and you will no longer access it. You can always restore the bussiness later.",
		      type: 'info',
		      showCancelButton: true,
		      confirmButtonColor: '#3085d6',
		      cancelButtonColor: '#d33',
          confirmButtonText: '{{__("lang.yes_archive_it")}}',
          cancelButtonText: '{{__("lang.cancel")}}',
		      confirmButtonClass: 'btn btn-primary',
		      cancelButtonClass: 'btn btn-danger ml-1',
		      buttonsStyling: false,
		    }).then(function (result) {
		      if (result.value) {
		      	$.ajax({
              method:'delete',
              url:'{{route("business.destroy", '')}}/'+id,
              data:{_token:"{{csrf_token()}}"},
              dataType:'json',
			        success:function(response){                   
			            toastr.success(response.success, 'Success', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
                 
			          location.reload()
			          if (0 == response.status) {
			            toastr.error(response.error, 'Error', { "showMethod": "fadeIn", "hideMethod": "fadeOut", timeOut: 5000 });
			          }
                datatable.draw();
			        }
			    });
		      }
		      else if (result.dismiss === Swal.DismissReason.cancel) {
		        Swal.fire({
		          title: 'Cancelled',
		          text: 'Your Bussiness is not archived',
		          type: 'info',
		          confirmButtonClass: 'btn btn-success',
		        })
		      }
		    });
    });
  });
  </script>
@endsection