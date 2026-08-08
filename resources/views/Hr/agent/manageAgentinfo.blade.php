@extends('BackEnd.layouts.master')

@section('title', 'Manage Agent')

@section('content')
    <div class="br-mainpanel">
        <div class="br-section-wrapper">
        <div class="text-center mb-4">
                    <h1 class="">Manage Agent Details</h1>
                    <br>
                </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" id="myInput" class="form-control" placeholder="Search">
                </div>
                <div class="col-md-9 text-right">
                    <a href="/agentHiring" class="btn btn-info">
                        <i class="fa-solid fa-plus"></i> Add Agent
                    </a>
                </div>
            </div>

            <table class="table table-striped" id="dataTable">
                <thead>
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Agent Name</th>
                    <th scope="col">Agent Email</th>
                    <th scope="col">License</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Education</th>
                    <th scope="col">Specialist</th>
                    <th scope="col">Action</th>

                  </tr>
                </thead>
                <tbody>
                    @foreach ($getAgentData as $i=>$agent)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $agent->name }}</td>
                            <td>{{ $agent->email }}</td>
                            <td>{{ $agent->license }}</td>
                            <td>{{ $agent->phone }}</td>
                            <td>{{ $agent->education }}</td>
                            <td>{{ $agent->specialist }}</td>
                            <td>
                                <ul class="actionBar">
                                    <li>
                                        <a href="">
                                            <i class="fa-duotone fa-eye fa-edit btn btn-sm btn-primary"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/editAgentHiring/{{ $agent->agent_id }}">
                                            <i class="fa-duotone fa-edit btn btn-sm btn-success"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/deleteAgentData/{{ $agent->agent_id }}">
                                            <i class="fa-duotone fa-trash btn btn-sm btn-danger" ></i>
                                        </a>
                                    </li>
                                </ul>
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
@stop
