@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Department</title>
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
            <h5 style="font-size: 0.875rem; margin:0;">Department</h5>
            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('addDepartment') }}" class="btn btn-primary">
                  <i class="fa fa-plus ml-0 mr-1"></i> Add Department
                </a>
            </div>
        </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @php
                      $p_edit = can_p('editDepartment');
                      $p_delete = can_p('deleteDept');
                    @endphp
                    <table class="table table-striped" id="tableDepartment">
                      <thead>
                        <tr>
                          <th scope="col">SN</th>
                          <th scope="col">Department Name</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody >
                        @foreach ($departmentData as $i=>$dept)
                          @php
                            $total = 0
                          @endphp
                          <tr>
                            <td scope="row">{{ $i+1 }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>
                              @if($p_edit)
                              <a  class="btn btn-primary"  href="{{ route('editDepartment',$dept->id) }}" >
                                <i class="bx bx-edit"></i>
                              </a>
                              @endif
                              @if($p_delete)
                              <a  del_data="{{ $dept->id }}" data-action="{{route('deleteDept',$dept->id)}}" class="del_hr_data btn btn-danger" href="#">
                                <i class="bx bx-trash"></i>
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
$('#tableDepartment').DataTable();
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
            cancelButtonClass: 'btn btn-danger ms-2',
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
