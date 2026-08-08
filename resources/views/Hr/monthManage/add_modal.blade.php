<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Month Manage</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('monthManage.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id"  required>

                                    <div class="col-sm-3">
                                        <label for="">Month *</label>
                                        <input type="text" class="form-control datetimepicker" name="monthDate" id="monthDate2" placeholder="yyyy-mm" />
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">Total Day *</label>
                                        <input type="number"  class=" form-control" name="monthTotalDay" autocomplete="off" placeholder="30" required>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="">Total Working Day *</label>
                                        <input type="number"  class=" form-control" name="workingDay" autocomplete="off" placeholder="22" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="">Total Holiday *</label>
                                        <input type="number"  class=" form-control" name="holiday" autocomplete="off" placeholder="22" required>
                                    </div>

                                    <div class="col-sm-3">
                                        <button class="btn btn-primary mt-4 " type="submit">
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
