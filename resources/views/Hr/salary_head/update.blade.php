<div id="update_errors"></div>
{{-- validate End  --}}

<!----- Start Add Category Form input ------->
<div class="col-xl-12 mx-auto">
    <div class="form-layout form-layout-4 py-1 pb-5">

        <form id="data-form-update" action="{{ route('school.salary_head.update', $salary_head->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-sm-6 mt-2">
                    <label class="form-control-label">Name: <span class="tx-danger">*</span></label>
                    <div class="mg-t-10 mg-sm-t-0">
                        <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ $salary_head->name }}" required>
                    </div>
                </div>
                <div class="col-sm-6 mt-2">
                    <label class="form-control-label">Type: <span class="tx-danger">*</span></label>
                    <div class="mg-t-10 mg-sm-t-0">
                        <select name="type" id="edit_type" class="form-control type-edit">
                            <option @if($salary_head->type == "Addition") selected @endif value="Addition">Addition</option>
                            <option @if($salary_head->type == "Deduction") selected @endif value="Deduction">Deduction</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6 mt-2">
                    <label class="form-control-label">Sort: <span class="tx-danger">*</span></label>
                    <div class="mg-t-10 mg-sm-t-0">
                        <input type="number" name="sort" class="form-control" placeholder="Enter Sort" value="{{ $salary_head->position }}" required>
                    </div>
                </div>
                
            </div>

            <div class="row mt-3">
                <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right">
                    <a href="javascript:void(0);" type="button" class="btn-cancel btn btn-secondary text-white mr-2" >Cancel</a>
                    <button type="submit" class="btn btn-info btn-update">Update</button>
                </div>
            </div>
        </form>

    </div>
</div>

