@extends('inc.master')

@section('head')
<link href="{{ asset('public/assets/css') }}/dataTables.bootstrap5.min.css" rel="stylesheet"/>

<title>Manage Bank Account</title>
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
                        <div class="row report-title">
                          <h4 class="my-2 mx-auto "><b>Bank Accounts</b></h4>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="container-fluid">
                <div class="row row-card-one">
                    <div class="col-sm-12">
                    <form>
                        <div class="form-row">
                          <div class="col-sm-3">
                            <label for="">Search Type:</label>
                            <select class="form-control form-control-sm">
                                <option>All </option>
                                <option>By Customer </option>
                                <option>By Employee </option>
                                <option>By Category </option>
                                <option>By Product </option>
                                <option>By User </option>

                              </select>
                          </div>
                          <div class="col-sm-3">
                            <label for="">Record Type:</label>
                            <select class="form-control form-control-sm">
                                <option>With Details</option>
                                <option>Without Details </option>


                              </select>
                          </div>
                          <div class="col-sm-2">
                            <label for="birthdaytime">From Date:</label>
                            <input type="date" class=" form-control form-control-sm" id="birthdaytime" name="time">

                          </div>
                          <div class="col-sm-2">
                            <label for="">To Date:</label>
                            <input type="date" class=" form-control form-control-sm" id="birthdaytime" name="time">

                          </div>
                          <div class="col-sm-2">
                            <button  type="submit" class="btn btn-primary btn-block mt-4">Report</button>

                          </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div> -->


            <div class="container" >
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
                                                    <form method="POST" action="{{route('bankaccount.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id"  required>
                                                            <div class="col-sm-6">
                                                                <label for="">Bank *</label>
                                                                <select class="form-control form-control-sm" name="bankID" required>
                                                                    <option>-- Select One --</option>
                                                                    @foreach($banks as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account's Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acName" placeholder="Tania" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Branch Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="branchName" placeholder="Mymensingh" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Type *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acType" placeholder="Savings" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Number *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acNumber" placeholder="2131031251196" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Routing Number *</label>
                                                                <input type="text" class="form-control form-control-sm" name="routingNumber" placeholder="09033100" required />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Initial Balnace *</label>
                                                                <input type="number" class="form-control form-control-sm" name="initalBalance" value="0" required />
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="">Note </label>
                                                                <input type="text" class="form-control form-control-sm" name="note"  />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-primary mt-4 ">
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
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row row-card-one">
                                                <div class="col-sm-12">
                                                   <!-- form here -->
                                                    <form method="POST" action="{{route('bankaccount.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" value="0" name="id" id="bankaccountID" required>

                                                            <div class="col-sm-6">
                                                                <label for="">Bank *</label>
                                                                <select class="form-control form-control-sm" id="bankID" name="bankID" required>
                                                                    <option>-- Select One --</option>
                                                                    @foreach($banks as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account's Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acName" id="acName" placeholder="Tania" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Branch Name *</label>
                                                                <input type="text" class="form-control form-control-sm" name="branchName" id="branchName" placeholder="Mymensingh" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Type *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acType" id="acType" placeholder="Savings" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Account Number *</label>
                                                                <input type="text" class="form-control form-control-sm" name="acNumber" id="acNumber" placeholder="2131031251196" required/>
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Routing Number *</label>
                                                                <input type="text" class="form-control form-control-sm" name="routingNumber" id="routingNumber" placeholder="09033100" required />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <label for="">Initial Balnace *</label>
                                                                <input type="number" class="form-control form-control-sm" name="initalBalance" id="initalBalance" value="0" required />
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <label for="">Note </label>
                                                                <input type="text" class="form-control form-control-sm" name="note" id="note"  />
                                                            </div>

                                                            <div class="col-sm-3">
                                                                <button class="btn btn-primary mt-4 ">
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
                              <th>Name</th>
                              <th>Branch</th>
                              <th>Type</th>
                              <th>Number</th>
                              <th>Routing Number</th>
                              <th>Balance</th>
                              <th>Note</th>
                              <th>Actions</th>
                            </tr>
                          </thead>

                          <tbody>
                            @foreach($bankaccounts as $key=>$bankaccount)
                            <tr class="{{$bank->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$bankaccount->bank->name}}</td>
                                <td>{{$bankaccount->acName}}</td>
                                <td>{{$bankaccount->branchName}}</td>
                                <td>{{$bankaccount->acType}}</td>
                                <td>{{$bankaccount->acNumber}}</td>
                                <td>{{$bankaccount->routingNumber}}</td>
                                <td>{{$bankaccount->initalBalance}}</td>
                                <td>{{$bankaccount->note}}</td>

                                <td>
                                  <span  data-token="{{csrf_token()}}" id="bankaccountEdit" data-id="{{$bankaccount->id}}" data-bs-toggle="modal" data-bs-target="#updateModal">
                                      <i class="bx bx-edit text-primary"></i>
                                  </span>

                                    {{-- <a title="Delete" href="{{route('bankaccount.delete')}}" id="delete" data-token="{{csrf_token()}}" data-id="{{$bankaccount->id}}"><i class="fas fa-trash text-danger
                                      "></i>
                                  	</a> --}}
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
<!-- bank account manage Edit-->
<script type="text/javascript">
     $(document).ready(function(){
        $(document).on('click','#bankaccountEdit',function(){
         var id=$(this).attr('data-id');
         // var termID=$('#termID').val();
          // alert(id);
            $.ajax({
                url: "{{route('bankaccount.edit') }}?id=" + id,
                method: 'GET',
                success: function(data) {
                    $('#bankID').html(data.html);
                    $('#bankaccountID').val(data.bankaccountID);
                    $('#acName').val(data.acName);
                    $('#branchName').val(data.branchName);
                    $('#acType').val(data.acType);
                    $('#acNumber').val(data.acNumber);
                    $('#routingNumber').val(data.routingNumber);
                    $('#note').val(data.note);
                    $('#initalBalance').val(data.initalBalance);
                }
            });
        });
    });
</script>


@endsection
