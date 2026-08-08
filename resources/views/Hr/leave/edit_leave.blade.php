@extends('inc.master')

@section('head')

<title>Edit Leave</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
</style>
@endsection


@section('content')

<div class="br-mainpanel">

    <!-- <div class="br-pagetitle">
      <i class="fa-duotone fa-gear"></i>
      <div>
        <h4>Leave Application</h4>
      </div>
    </div>d-flex -->


      <!-- <div class="p-5"> -->

          <div class="br-section-wrapper">
          <div class="text-center mb-4">
                    <h1 class="">Edit Leave Applications Details</h1>
                    <br>
                </div>
                @php
                    $edit = $editData;
                @endphp
            {{-- @foreach ($editData as $edit ) --}}

              <form action="{{ url('updateLeave') }}/{{ $edit->id }}" method="post">
                  @csrf
                  <div class="row">
                      <div class="col-md-6" style="margin-bottom: 20px">
                        <label for="exampleDataList" class="form-label">Employee Name :</label>

                        <select name="empId" class="form-control">
                            @foreach ($employee as $emp)
                            <option @if($emp->id == $edit->employee_id) selected @endif value="{{ $emp->id}}">{{ $emp->employee_name }}
                            @endforeach
                         </select>
                          </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Leave Type : </label>
                          <select name="type" id="" class="form-control" required>

                            <option @if($edit->leave_type == "Sick Leave") selected @endif value="Sick Leave">Sick Leave</option>
                            <option @if($edit->leave_type == "Causal Leave") selected @endif  value="Causal Leave">Causal Leave</option>
                          </select>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Leave Reason : </label>
                          <input type="text" name="reason" value="{{ $edit->reason }}" id="" class="form-control" placeholder="Type reason here.." required>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Leave Status : </label>
                          <select name="status" id="" class="form-control" >

                            <option @if($edit->status == 0) selected @endif value="0">Pending</option>
                            <option @if($edit->status == 1) selected @endif value="1">Approved</option>
                          </select>
                      </div>
                      <br><br>
                  <center>
                      <button class="btn btn-info" type="submit">
                          {{-- <i class="fa-regular fa-plus"></i> --}}
                         Edit Leave Entry
                      </button>
                  </center>

              </form>
              {{-- @endforeach --}}

          </div>
      <!-- </div> -->

</div>
<script>
$(function() {
  $('.selectpicker').selectpicker();
});
</script>
@stop
