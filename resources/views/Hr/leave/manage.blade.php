@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Leave Application</title>
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

              <input type="text" placeholder="Search Data" id="myInput">
              <a href="{{ route('applyLeave') }}" class="btn btn-info btn-sm float-right">Apply Leave</a>
                  <br>
                  <br>
                <table class="table table-striped" id="dataTable">
                  <thead>
                    <tr>
                      <th scope="col">SN</th>
                      <th scope="col">Leave Type</th>
                      <th scope="col">Leave Part</th>
                      <th scope="col">From Date</th>
                      <th scope="col">To Date</th>
                      <th scope="col">Reason</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody id="dataTable" >
                      <tr>
                      @foreach ($viewAll as $i=>$data)
                        <tr>
                          <th scope="row">{{ $i+1 }}</th>

                          <td>
                            {{ $data->type }}
                          </td>
                          <td>
                            {{ $data->part }}
                          </td>
                          <td>
                            {{ $data->from }}
                          </td>
                          <td>
                            {{ $data->to }}
                          </td>
                          <td>
                            {{ $data->reason }}
                          </td>
                          <td>
                            {{ $data->status }}
                          </td>
                        </tr>
                      @endforeach

                      </tr>

                  </tbody>
                </table>
          </div>
      </div>
      @endsection
      @section('script')
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
@endsection
