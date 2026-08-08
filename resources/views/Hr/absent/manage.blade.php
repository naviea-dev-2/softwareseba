@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Absent</title>
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
        <h5 style="font-size: 0.875rem; margin:0;">Absent Setting</h5>
        <div class="d-flex" style="gap:10px;">
          @if($viewAll->count() == 0)
            <a href="{{ route('addAbsent') }}" class="btn btn-primary float-right">Add Absent</a>
          @endif
        </div>
      </div>
            
    </div>
    <div class="row" style="padding-top: 24px;">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                     @php
                        $p_edit = can_p('editAbsent');
                    @endphp
                    <table class="table table-bordered table-light" id="dataTable">
                      <thead>
                        <tr>
                          <th scope="col">SL</th>
                          <th scope="col">First</th>
                          <th scope="col">Other</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody id="dataTable" >

                          <tr>
                          @foreach ($viewAll as $i=>$data)
                            <tr>
                              <th scope="row">{{ $i+1 }}</th>
                              <th scope="row">{{$data->first}}</th>
                              <th scope="row">{{$data->other}}</th>
                              <td>
                                @if($p_edit)
                                  <a class="btn text-primary" href="{{ route('editAbsent',$data->id) }}">
                                      <i class="bx bx-edit"></i>
                                  </a>
                                @endif
                              </td>
                            </tr>
                          @endforeach

                          </tr>

                      </tbody>
                    </table>
                </div>
            </div>
        </div>
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
