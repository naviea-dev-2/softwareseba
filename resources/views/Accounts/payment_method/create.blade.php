 <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Payment Method</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('payment_method.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id"  required>

                                    <div class="col-sm-3">
                                        <label for="">Method Name *</label>
                                        <input type="text" class="form-control form-control-sm" name="name"  placeholder="Method Name" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Sorting </label>
                                        <input type="text" value="0" class="form-control form-control-sm" name="sorting"  placeholder="Sorting" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    @if(auth()->user()->business->business_type_id != 17)
                                    <div class="col-sm-3">
                                        <label for="">For POS </label>
                                        <select name="for_pos" id="for_pos" class="form-control form-control-sm">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-sm-3 bank_show" style="display: none;">
                                        <label for="">Account *</label>
                                        <select id="add_account" name="account" class="form-control">
                                            <option value="">Select Account</option>


                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Icon </label>
                                        <div class="mt-1 mr-2" style="position:relative;box-shadow: 0px 0px 1px 1px;width: 50px;">
                                            <img class="display-upload-img-add" style="width: 50px;height: 40px;" src="{{ asset("public/images/No-image.jpg")}}" alt="">
                                            <input type="file" name="image" class="form-control upload-img-add" placeholder="Enter Activity Image" style="position: absolute;top: 0;opacity: 0;height: 100%;">
                                            <span class="invalid-feedback mb-0"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <button class="btn btn-sm btn-primary mt-4 " type="submit">
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
