@extends('inc.master')

@section('head')

<title>Edit Salary</title>
<style>
    label{
        /* font-size: 1.2rem; */
    }
</style>
@endsection


@section('content')

<div class="br-mainpanel">

    <!-- <div class="br-pagetitle">
      <i class="fa-duotone fa-gear"></i>
      <div>
        <h4>Update Employee Salary</h4>
      </div>
    </div>d-flex -->


      <!-- <div class="p-5"> -->

          <div class="br-section-wrapper">
                <div class="text-center mb-4">
                    <h1 class="">Update Employee Salary</h1>
                    <br>
                </div>

            @foreach ($salaryData as $data )

              <form action="{{ url('updateSalary') }}/{{ $data->id }}" method="post">
                  @csrf
                  <div class="row">
                      <div class="col-md-4" style="margin-bottom: 20px">
                        <label for="exampleDataList" class="form-label">Employee Name :</label>
                        <input class="form-control" name="empId" value="{{ $data->emp_id }}" list="datalistOptions" id="exampleDataList" placeholder="Type to Employee Name..." required>
                        <datalist id="datalistOptions">
                            @foreach ($employee as $emp)
                            <option value="{{ $emp->id}}">{{ $emp->emp_name }}
                            @endforeach
                        </datalist>
                          </div>
                      <div class="col-md-4" style="margin-bottom: 20px">
                          <label for="">Salary Month : </label>
                          <input type="text" name="salary_month" value="{{ $data->salary_month }}" id="" class="form-control" placeholder="Type salary month here.." required>
                      </div>
                      <div class="col-md-4" style="margin-bottom: 20px">
                          <label for="">Generate Date : </label>
                          <input type="date" name="generate_date" value="{{ $data->generate_date }}" id="" class="form-control" placeholder="Type generate date.." required>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Generate By : </label>
                          <input type="text" name="generate_by" value="{{ $data->generate_by }}" id="" class="form-control" placeholder="Type generate by.." required>
                      </div>
                      <div class="col-md-6" style="margin-bottom: 20px">
                          <label for="">Leave Status : </label>
                          <select name="status" id="" class="form-control" requird>
                            <option value="{{ $data->status }}" selected >{{ $data->status }}</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                          </select>
                      </div>
</div>
                      <br><br>
                      <div class="row">
                        <div class="col-md-12">
                  <center>
                      <button class="btn btn-info">
                          <i class="fa-regular fa-plus"></i>
                           Update Salary
                      </button>
                  </center>
                      </div>
                      </div>

              </form>
              @endforeach
          </div>
      </div>

</div>
<script>
$(function() {
  $('.selectpicker').selectpicker();
});
</script>
@stop
