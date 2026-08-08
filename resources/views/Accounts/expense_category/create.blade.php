 <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Expense Category</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('expense_category.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id"  required>

                                    <div class="col-sm-3">
                                        <label for="">Category Name *</label>
                                        <input type="text" class="form-control form-control-sm" name="name"  placeholder="EX:Utility" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    {{-- <div class="col-sm-3">

                                        <label for="">Category Code *</label>
                                        <input type="text" class="form-control form-control-sm" name="code" placeholder="EX:ca-1001" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div> --}}
                                    <div class="col-sm-3">

                                        <label for="">Expense Type *</label>
                                        <select name="expense_type" class="form-control">
                                            <option value="2">Indirect Expense</option>
                                            <option value="1">Direct Expense</option>
                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>

                                    <div class="col-sm-3">
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
