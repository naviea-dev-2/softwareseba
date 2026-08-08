@section('head')
<title>Admin - Software Service</title>
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
								{{ __('Software Service') }}
							</div>
						</div>
					</div>
                    <div class="card-body tabs-area p-0">
						@include('admin.theme_option.partials.theme_options_tabs_nav')
						<div class="tabs-body">
                            <div class="my-3">
                                 <a href="{{route('backend.create_soft_service')}}" class="btn btn-outline-primary"><span><i class="feather icon-plus"></i> {{__('lang.add_new')}}</span></a>
                            </div>
                            <div class="cus-table">
                            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                    <th>SN.</th>
                                    {{-- <th>Image</th> --}}
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($software_services as $key=>$software_service)
                                    <tr class="{{$software_service->id}}">
                                        <td>{{$key+1}}</td>
                                        {{-- <td><img src="{{$software_service->image_show}}" style="height:50px;width:50px;"></td> --}}
                                        <td>{{$software_service->title}}</td>
                                        <td>{{$software_service->url}}</td>
                                        <td>
                                            <a class="btn btn-primary data_edit" href="{{ route('backend.edit_soft_service',$software_service->id) }}">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a href="{{route('backend.delete_soft_service',$software_service->id)}}" data-token="{{csrf_token()}}" data-id="{{$software_service->id}}" class="del_data btn btn-danger confirmation">
                                                <i class="bx bx-trash"></i>
                                            </a>


                                        </td>
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
<!-- /main Section -->


@endsection
@section('script')
<script>
     $('#dataTable').DataTable();
    $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });
</script>
@endsection
