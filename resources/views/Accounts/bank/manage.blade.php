@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Bank</title>
<style>
    label{
        font-size: 1.2rem;
    }
</style>
@endsection
 @section('content')
        <div class="content-area">
            <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12 ">
                        <div class="row report-title ">
                            <h4 class=" "><b>Bank Manage</b> </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row row-card-one my-4">
                    <div class="col-md-12 col-lg-12 col-sm-12">

                        <!-- start insert modal -->
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          <i class="bx bx-plus"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Bank</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="{{route('bank.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id"  required>

                                                           	<div class="col-sm-3">
                                                                <label for="">Bank Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="bankName"  placeholder="1" />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-primary mt-4 ">
                                                                    <i class="fa fa-save pr-2"></i>Save
                                                                </button>
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
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Bank</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true" style="color: white;line-height:18px;">&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="{{route('bank.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id" id="bankID" required>

                                                            <div class="col-sm-3">
                                                                <label for="">Bank Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="bankName" id="bankName" placeholder="1" />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-sm btn-primary mt-4 ">
                                                                    <i class="fa fa-save pr-2"></i>Save
                                                                </button>
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

                        <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>SN.</th>
                              <th>Bank Name</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($banks as $key=>$bank)
                            <tr class="{{$bank->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$bank->name}}</td>
                                <td>
                                  <span  data-token="{{csrf_token()}}" id="bankEdit" data-id="{{$bank->id}}" data-bs-toggle="modal" data-bs-target="#updateModal">
                                      <i class="bx bx-edit text-success"></i>
                                  </span>

                                    <a title="Delete" class="del_hr_data" data-token="{{csrf_token()}}" data-id="{{$bank->id}}"><i class="bx bx-trash text-danger
                                      "></i>
                                  	</a>
                                </td>
                            </tr>
                           @endforeach

                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area End -->
    </div>
</div>
@endsection
@section('script')
<link href="{{ asset('public/assets/css') }}/sweetalert2.min.css" rel="stylesheet">
<script src="{{ asset('public/assets/js') }}/sweetalert2.all.min.js"></script>
<!-- month manage Edit-->
<script type="text/javascript">
     $(document).ready(function(){
        $(document).on('click','#bankEdit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
          // alert(id);
            $.ajax({
                url: "{{route('bank.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    // $('#fetchDepartment').html(data.html);
                    $('#bankID').val(data.bankID);
                    $('#bankName').val(data.bankName);
                }
            });
        });
    });
    $(document).on('click','.del_hr_data',function(){
        let id = $(this).attr('data-id');
        Swal.fire({
            title: '{{__("lang.are_you_sure")}}',
            text: '{{__("lang.you_wont_be_able_to_revert_this")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{__("lang.yes_delete_it")}}',
            cancelButtonText: '{{__("lang.cancel")}}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
		}).then(function (result) {
		    if (result.value) {
                window.location = "{{ url('bank-delete') }}/"+id;
            }
        });
    });
</script>


@endsection
