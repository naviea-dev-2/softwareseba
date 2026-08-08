@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Designation</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection


@section('content')
  <div class="content-area">
    <div class="container-fluid mt-2" style="background:#ffffff;dmin-height: 55px;padding: 13px 25px;">
        
        <div class="d-flex justify-content-between align-items-center">
            <h5 style="font-size: 0.875rem; margin:0;">Designation</h5>
            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('addDesignation') }}" class="btn btn-primary">
                  <i class="fa fa-plus ml-0 mr-1"></i> Add Designation
                </a>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @php
                        $p_edit = can_p('editDesignation');
                        $p_delete = can_p('deleteDesg');
                    @endphp
                    <table class="table table-striped" id="tableDesignation">
                        <thead>
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">Department</th>
                                <th scope="col">Designation Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designationData as $i=>$desg)

                                <tr>
                                <th scope="row">{{ $i+1 }}</th>
                                <td>
                                    {{ $desg->department?->name }}
                                </td>
                                <td>{{ $desg->name }}</td>
                                <td>
                                    @if($p_edit)
                                    <a class="btn btn-primary" href="{{ route('editDesignation',$desg->id) }}" >
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    @endif
                                    @if($p_delete)
                                    <a del_data="{{ $desg->id }}" data-action="{{route('deleteDesg',$desg->id)}}" data-ac class="del_hr_data btn btn-danger" href="#">
                                        <i class="bx bx-trash"></i>
                                        {{-- <i class="fa-duotone fa-trash-can btn btn-sm btn-danger"></i> --}}
                                    </a>
                                    @endif
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

@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
      <script>
$(document).ready(function(){
$('#tableDesignation').DataTable();
});
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('del_data');
        let action = $(this).attr('data-action');
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = action;
            }
        });
    });
</script>

<!-- </div> -->
@endsection
