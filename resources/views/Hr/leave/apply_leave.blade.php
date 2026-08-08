@extends('inc.master')

@section('head')

<title>Apply Leave</title>
<style>
    /* label{
        font-size: 1.2rem;
    } */
</style>
@endsection

@section('content')

<div class="br-mainpanel">

          <div class="br-section-wrapper">

                <div class="text-center mb-4">
                    <h1 class="">Leave Application</h1>
                    <br>
                </div>

              <form action="{{ url('storeLeaveApplication') }}" method="post">
                  @csrf
                  <div class="row">
                      <!-- <div class="col-md-6" style="margin-bottom: 20px">
                        <label for="exampleDataList" class="form-label">Employee Name :</label>
                        <input class="form-control" name="empId" list="datalistOptions" id="exampleDataList" placeholder="Type to Employee Name..." required>
                        <datalist id="datalistOptions">
                            @foreach ($employee as $emp)
                            <option value="{{ $emp->id}}">{{ $emp->emp_name }}
                            @endforeach
                        </datalist>
                          </div> -->
                      <div class="col-md-3" style="margin-bottom: 20px">
                          <label for="">Leave Type : </label>
                          <select name="type" id="" class="form-control" required>
                            <option value="" disabled selected>Choose Leave Type..</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Causal Leave">Causal Leave</option>
                            <option value="Annual Leave">Annual Leave</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Special Leave">Special Leave</option>
                          </select>
                      </div>
                      <div class="col-md-3" style="margin-bottom: 15px">
                          <label for="">Leave Part : </label>
                          <select name="part" id="" class="form-control" >
                            <option value="" selected disabled>Choose part..</option>
                            <option value="fullday">Full Day</option>
                            <option value="secondhalf">Second Half</option>
                            <option value="firsthalf">First Half</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                            <div class="form-group">
                                <label>From Date : </label>
                                <input type="date"
                                class="form-control datepicker" name="from">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>To Date : </label>
                                <input type="date"
                                class="form-control datepicker" name="to">
                            </div>
                        </div>
                      <div class="col-md-6" style="margin-bottom: 15px">
                          <label for="">Leave Reason : </label>
                                <textarea name="reason" id="" cols="30" class="form-control" rows="2"></textarea>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 15px">
                          <label for="">Address : </label>
                                <textarea name="address" cols="30" class="form-control" rows="2"></textarea>
                      </div>
                      @php
                            $department = DB::table('department')->get()
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Department Name : </label>
                                <select name="department" id="deptID" class="form-control">
                                    <option value="" disabled selected>Choose Department</option>
                                    @foreach ($department as $dept)
                                      <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @php
                            $designation = DB::table('designation')->get()
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation : </label>
                                <select name="designation" id="desigID" class="form-control">
                                    <option value="" disabled selected>Choose Designation</option>
                                    <!-- @foreach ($designation as $desg)
                                      <option value="{{ $desg->id }}">{{ $desg->name }}</option>
                                    @endforeach -->
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label>Employee ID : </label>
                                <input type="text" class="form-control" name="employee_id">
                            </div>
                        </div> -->
                      <!-- <div class="col-md-6" style="margin-bottom: 15px">
                          <label for="">Leave Status : </label>
                          <select name="status" id="" class="form-control" >
                            <option value="" selected disabled>Choose Status..</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                          </select>
                      </div> -->
                      <div class="col-md-6" style="margin-bottom: 20px">
                        <label for="exampleDataList" class="form-label">Employee ID :</label>
                        <!-- <input class="form-control" name="empID" list="datalistOptions" id="exampleDataList" placeholder="Type to Employee Name..." required> -->
                            <!-- <datalist id="datalistOptions">
                                @foreach ($employee as $emp)
                                <option value="{{ $emp->id}}">{{ $emp->emp_name }}
                                @endforeach
                            </datalist> -->
                            <select class="form-control" id="empID" name="empId" required>
                                <!-- <option>-- wait --</option>                                          -->
                                <option>-- Select One --</option>
                                    <!-- @foreach ($employee as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->employee_id }}</option>
                                    @endforeach -->
                            </select>

                        </div>
                      <br><br>
                    </div>
                  <center>
                      <button class="btn btn-info">
                      <i class="fa-solid fa-floppy-disk"></i>                           Save
                      </button>
                  </center>

              </form>

          </div>
      <!-- </div> -->

</div>
@endsection
@section('script')
<script>

$(".datepicker").flatpickr();
$(document).on('change', '#deptID', function(){
    var deptID = $(this).val();
    console.log(deptID);
      $.ajax({
        url: '{{ url("getDesigName4") }}',
        method: "POST",
        dataType: "JSON",
        data: {
          "_token": "{{ csrf_token() }}",
          "deptID": deptID
        },
        success: function (response) {
            console.log(response);
            var option = '';
            option += '<option value="" disabled selected>-- Select One --</option>';
            $.each(response, function (index, value) {
                option += '<option value="' + value.id + '">' + value.name + '</option>';
            });
            $('#desigID').empty().append(option);
        }
      });
    });

    $(document).on('change', '#desigID', function(){
    var desigID = $(this).val();
    console.log(desigID);
    $.ajax({
        url: '{{ url("getEmployeeId4") }}',
        method: "POST",
        dataType: "JSON",
        data: {
          "_token": "{{ csrf_token() }}",
          "desigID": desigID
        },
        success: function (response) {
            console.log(response);
            var option = '';
            option += '<option value="" disabled selected>-- Select One --</option>';
            $.each(response, function (index, value) {
                option += '<option value="' + value.id + '">' + value.employee_id + '</option>';
            });
            $('#empID').empty().append(option);
        }
      });
    });






</script>
@endsection
