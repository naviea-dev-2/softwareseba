@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Salary</title>
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
                    <h1 class="">Manage Salary</h1>
                    <br>
                </div>

              <input type="text" placeholder="Search Data" id="myInput">
              <a href="{{ route('addSalary') }}" class="btn btn-info btn-sm float-right">Add Salary</a>
                  <br>
                  <br>
                <table class="table table-striped" id="dataTable">
                  <thead>
                    <tr>
                      <th scope="col">SN</th>
                      <th scope="col">Employee Name</th>
                      <th scope="col">Employee Id</th>
                      <th scope="col">Basic Salary</th>
                      <th scope="col">Salary Month</th>
                      <th scope="col">Bonus</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="dataTable" >
                      @foreach ($allData as $i=>$data)
                        <tr>
                          <th scope="row">{{ $i+1 }}</th>
                          @foreach ($employee as $emp)
                          @if ($emp->id == $data->emp_id)
                          <td>{{ $emp->emp_name }}</td>
                          <td>{{ $emp->employee_id }}</td>
                          <td>{{ $emp->salary }}</td>
                       @endif
                       @endforeach
                       <td>{{ $data->salary_month }}</td>

                       <td><button class="btn btn-info">{{ $data->status }}</button></td>
                          <td>
                              <a href="/editSalary/{{ $data->id }}">
                                 <i class="bx bx-edit btn btn-sm btn-success"></i>
                              </a>
                              <a href="/deleteSalary/{{ $data->id }}" >
                                <i class="bx bx-trash btn btn-sm btn-danger"></i>
                              </a>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                </table>
          </div>
      </div>
      <script>
$(document).ready(function(){
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/alfrcr/paginathing/dist/paginathing.min.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function ($) {
    const listElement = $('.list-group');

    for (let i = 1; i <= 1500; i++) {
      listElement.append('<li class="list-group-item"> Item ' + i + '</li>');
    }

    listElement.paginathing({
      perPage: 5,
      limitPagination: 9,
      containerClass: 'panel-footer mt-4',
      pageNumbers: true,
      ulClass: 'pagination flex-wrap justify-content-center',
    })

    $('.table tbody').paginathing({
      perPage: 10,
      insertAfter: '.table',
    //   pageNumbers: true,
      ulClass: 'pagination flex-wrap justify-content-center'
    });
  });
</script>
<!-- </div> -->
@stop
