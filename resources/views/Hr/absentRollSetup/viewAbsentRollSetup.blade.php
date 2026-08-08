@extends('BackEnd.layouts.master')

@section('title', 'Page Title')

@section('content')

<div class="br-mainpanel">

    <div class="br-section-wrapper">

                <div class="text-center mb-4">
                    <h1 class="">Absent Roll Setup</h1>
                    <br>
                </div>

              <!-- <form action="/storeAbsentRollData" method="post">
                  @csrf
                  <div class="row">
                      <div class="col-md-9" style="margin-bottom: 20px">
                          <input type="text" name="name" id="" class="form-control" required placeholder="Add A Department Name (Example: Engineering/Marketing/Accounting etc.)">
                      </div>
                      <br><br>
                  <center>
                      <button class="btn btn-info">
                          <i class="fa-regular fa-plus"></i>
                           Add Department
                      </button>
                  </center>

              </form> -->

                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>First Absent Amount(%)</th>
                              <th>Other Absent Amount(%)</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($absent as $i=>$data)
                                <tr class="1">
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $data->firstAbsentAmount }}%</td>
                                    <td>{{ $data->otherAbsentAmount }}%</td>
                                    <td>
                                    <span id="absentrollEdit" data-id="{{ $data->id }}" data-toggle="modal" data-target="#updateModal">
                                        <i class="bx bx-edit"></i>
                                    </span>
                                        <!-- <a title="Delete" href="/absentroll-delete" id="delete" data-id="1"><i class="fas fa-trash-alt text-danger
                                        "></i></a> -->
                                    </td>
                                </tr>
                            @endforeach
                          </tbody>
                        </table>

        <!-- Main Content Area End -->


              <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Absent Roll Setup</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" style="color: white;line-height:18px;">&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="/storeAbsentRollData" enctype="multipart/form-data">
                                                        @csrf
                                                            <input type="hidden" value="0" name="id" id="absentrollID" required>
                                                            <div class="row">
                                                           	<div class="col-sm-4">
                                                                <label for="">First Absent Amount(%) *</label>
                                                                <input type="number" step="any" class="form-control form-control-sm" name="firstAbsentAmount" placeholder="Ex: 1.00" />
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label for="">Other Absent Amount(%) *</label>
                                                                <input type="number" step="any" class="form-control form-control-sm" name="otherAbsentAmount" placeholder="Ex: 0.50" />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-primary mt-4 ">
                                                                    <i class="fa fa-save pr-2"></i>Save
                                                                </button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- start update modal -->

                        <!-- Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Absent Roll Setup</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" style="color: white;line-height:18px;">&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="/updateAbsentRoll/{id}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id" id="absentrollID" required>

                                                            <div class="col-sm-4">
                                                                <label for="">First Absent Amount(%)*</label>
                                                                <input type="number" step="any" class="form-control form-control-sm" name="firstAbsentAmount" id="firstAbsentAmount" placeholder="Ex: 1.00" />
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label for="">Other Absent Amount(%)*</label>
                                                                <input type="number" step="any" class="form-control form-control-sm" name="otherAbsentAmount" id="otherAbsentAmount" placeholder="Ex: 0.50" />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-primary mt-4 ">
                                                                    <i class="fa fa-save pr-2"></i>Save
                                                                </button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->
                        <br/><br/>

    </div>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/alfrcr/paginathing/dist/paginathing.min.js"></script>
<script>
    $(document).on('click', '#absentrollEdit', function(){
        var id = $(this).data('id');
        // console.log(id);
        $.ajax({
            url: '/editAbsentRoll/'+id,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                $('#absentrollID').val(data.id);
                $('#firstAbsentAmount').val(data.firstAbsentAmount);
                $('#otherAbsentAmount').val(data.firstAbsentAmount);
            }
        });
    });
</script>


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


<script type="text/javascript">
  jQuery(document).ready(function ($) {
    const listElement = $('.list-group');

    for (let i = 1; i <= 1500; i++) {
      listElement.append('<li class="list-group-item"> Item ' + i + '</li>');
    }

    listElement.paginathing({
      perPage: 5,
      limitPagination: 9,
      containerClass:'panel-footer mt-4',
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
