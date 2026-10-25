<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">New Salary Head</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="create_errors"></div>
                    {{-- validate End  --}}
                    <div class="col-xl-12 mx-auto">
                        <div class="form-layout form-layout-4 py-1 pb-5">
                            <form id="data-form-create" action="{{ route('school.salary_head.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Name: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Type: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <select name="type" id="type" class="form-control t-add">
                                                <option value="Addition">Addition</option>
                                                <option value="Deduction">Deduction</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 mt-2">
                                        <label class="form-control-label">Sort: <span class="tx-danger">*</span></label>
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <input type="number" max="100" min="1" name="sort" class="form-control" placeholder="Enter Sort" value="{{ old('sort') }}" required>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 mt-2">
                                        <div class="mg-t-10 mg-sm-t-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="is_basic" name="is_basic" value="1">
                                                <label class="custom-control-label" for="is_basic">Is Basic Salary <small class="text-muted">(used for attendance fine calculation)</small></label>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>


                                <div class="row mt-2">
                                    <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-end">
                                        <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a>
                                        <button type="submit" class="btn btn-info btn-create">Save</button>
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
