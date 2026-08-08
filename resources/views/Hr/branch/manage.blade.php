@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Branch</title>
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
                    <h1 class="">Manage Branch </h1>
                    <br>
                </div>

              <input type="text" placeholder="Search Data" id="myInput">

              <a  class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i></a>
              <a  class="btn btn-info btn-sm float-right mx-2 text-light" href="{{route('manageBank')}}">Bank</a>

                  <br>
                  <br>
                <table class="table table-striped" id="dataTable">
                  <thead>
                    <tr>
                      <th scope="col">SL</th>
                      <th scope="col">Bank Name</th>
                      <th scope="col">Name</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="dataTable" >

                      <tr>
                      @foreach ($viewAll as $i=>$data)
                        <tr>
                          <th scope="row">{{ $i+1 }}</th>
                          <td> {{ucwords(bank($data->bank_id)->name)}}</td>
                          <td> {{ucwords($data->name)}}</td>
                          <td>
                              <a  style="color: #d49d27; color:15px !important"
                              data-toggle="modal" data-target="#editModal_{{$data->id}}"
                              >
                                  <i class="bx bx-edit btn btn-sm btn-success"></i>
                              </a>
                              <a href="/deleteBranch/{{ $data->id }}" style="color: #000">
                                  <i class="bx bx-trash btn btn-sm btn-danger"></i>
                              </a>
                          </td>
                        </tr>
                      @endforeach

                      </tr>

                  </tbody>
                </table>
          </div>
      </div>

      <!-- Add  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
        <h5 class="modal-title" id="addModalLabel" style="color:white;line-height:18px;">Create Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color: white;line-height:18px;">×</span>
        </button>
        </div>
      <div class="modal-body">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12">

                        <!-- form here -->
                        <form method="POST" action="{{route('storeBranch')}}">
                            @csrf
                            <div class="form-row">
                            <div class="col-md-6">
                              <label for="">Bank Name</label>
                              <select name="bank_id" id="" class="form-control" required>
                                <option value="">Select a Bank</option>
                                @foreach($banks as $bank)
                                 <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                              </select>
                            </div>
                                <div class="col-md-6">
                                    <label for="">Name *</label>
                                     <div class="form-group d-flex dynamic_field">
                                      <input type="text" class="form-control" name="names[]" placeholder="Enter Branch Name" required>
                                      <div>
                                          <button class="btn btn--sm btn-success" id="addNewBtn">+</button>
                                      </div>
                                  </div>

                                </div>
                                <div class="col-md-12">

                                </div>
                                <div class="col-sm-3 align-self-center">
                                    <button class="btn btn-sm btn-primary mt-4 ">
                                        <i class="fa fa-save pr-2"></i>Create
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

      <!-- Edit  -->

      @forEach($viewAll as $p)

<!-- edit  Modal -->
<div class="modal fade" id="editModal_{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
        <h5 class="modal-title" id="addModalLabel" style="color:white;line-height:18px;">Update Branch</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" style="color: white;line-height:18px;">×</span>
        </button>
        </div>
      <div class="modal-body">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12">

                        <!-- form here -->
                        <form method="POST" action="{{route('updateBranch',$p->id)}}">
                            @csrf

                            <div class="form-row">
                                <div class="col-md-6">
                              <label for="">Bank Name</label>
                              <select name="bank_id" id="" class="form-control" required>
                                <option value="">Select a Bank</option>
                                @foreach($banks as $bank)
                                 <option value="{{$bank->id}}"
                                 @if($bank->id == $p->bank_id)
                                 selected
                                 @endif
                                 >{{$bank->name}}</option>
                                @endforeach
                              </select>
                            </div>
                                <div class="col-sm-6">
                                    <label for="">Name *</label>
                                    <input type="text" class="form-control" value="{{$p->name}}" name="name">
                                </div>
                                <div class="col-sm-3 align-self-center">
                                    <button class="btn btn-sm btn-primary mt-4 ">
                                        <i class="fa fa-save pr-2"></i>Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
      @endforeach
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


@push('script')
<script>
  $('body').on('click',"#addNewBtn",function(e){
    e.preventDefault();
    let html = `
    <div class="form-group my-2 d-flex">
        <input type="text" class="form-control" name="names[]" placeholder="Enter Branch Name" required>
        <div>
            <button class="btn btn--sm btn-danger deleteRow ">-</button>
        </div>
    </div>
    `
    $('.dynamic_field').after(html);
});
// Delete Row
$('body').on('click',".deleteRow",function(e){
    e.preventDefault();
    $(this).parents(".form-group").remove();
})
</script>
@endpush
