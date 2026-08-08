@extends('inc.master')

@section('head')

<title>Add Leave</title>
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
                    <h1 class="">Add Leave Applications Details</h1>
                    <br>
                </div>

              <form action="{{ url('storeLeave') }}" method="post">
                  @csrf
                  <div class="row">
                      <div class="col-md-6" style="margin-bottom: 20px">
                        <label for="exampleDataList" class="form-label">Employee Name :</label>

                        <select name="empId" class="form-control">
                            <option>Selec Employee</option>
                            @foreach ($employee as $emp)
                            <option value="{{ $emp->id}}">{{ $emp->employee_name }}
                            @endforeach
                        </select>
                          </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Leave Type : </label>
                          <select name="type" id="" class="form-control" required>
                            <option value="" disabled selected>Choose Leave Type..</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Causal Leave">Causal Leave</option>
                          </select>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 15px">
                          <label for="">Leave Reason : </label>
                          <input type="text" name="reason" id="" class="form-control" placeholder="Type reason here.." required>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 15px">
                          <label for="">Leave Status : </label>
                          <select name="status" id="" class="form-control" >
                            <option value="" selected disabled>Choose Status..</option>
                            <option value="0">Pending</option>
                            <option value="1">Approved</option>
                          </select>
                      </div>
                      <br><br>
                    </div>
                  <center>
                      <button class="btn btn-info">
                          {{-- <i class="fa-regular fa-plus"></i> --}}
                           Add Leave Entry Details
                      </button>
                  </center>

              </form>

          </div>
      <!-- </div> -->

</div>
<script>
$(function() {
  $('.selectpicker').selectpicker();
});
</script>
@stop
