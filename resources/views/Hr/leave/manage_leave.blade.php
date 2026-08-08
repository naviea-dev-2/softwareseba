@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Leave</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection


@section('content')
<div class="br-mainpanel">

          <div class="br-section-wrapper">

                <div class="text-center mb-4">
                    <h1 class="">Manage Leave Applications Details</h1>
                    <br>
                </div>


              <a href="{{ route('addLeave') }}" class="btn btn-info float-right">Add Leave Details</a>
                  <br>
                  <br>
                <table class="table table-striped" id="tableleave">
                  <thead>
                    <tr>
                      <th scope="col">SN</th>
                      <th scope="col">Employee Name s</th>
                      <th scope="col">Employee Id</th>
                      <th scope="col">Leave Type</th>
                      <th scope="col">Reason</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody >
                      @foreach ($viewAll as $i=>$data)
                        <tr>
                          <th scope="row">{{ $i+1 }}</th>

                          <td>{{ $data->employee->employee_name }}</td>
                          <td>{{ $data->employee->employee_id }}</td>

                          <td>
                            {{ $data->leave_type }}
                          </td>
                          <td>
                            {{ $data->reason }}
                          </td>
                          <td>
                            {{ $data->status == 1  ? "Approved" :  "Pending"  }}
                          </td>
                          <td>
                              <a href="{{ url('editLeave') }}/{{ $data->id }}" class="btn btn-success">
                                 <i class="bx bx-edit"></i>
                              </a>
                              <a del_data="{{ $data->id }}" class="del_hr_data btn btn-danger bg-white">
                                  <i class="bx bx-trash"></i>
                              </a>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
          </div>
      </div>
      @endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function(){
$('#tableleave').DataTable();
});
$(document).on('click','.del_hr_data',function(){
    let id = $(this).attr('del_data');
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
            window.location = "{{ url('deleteLeave') }}/"+id;
        }
    });
});
</script>

<!-- </div> -->
@endsection
