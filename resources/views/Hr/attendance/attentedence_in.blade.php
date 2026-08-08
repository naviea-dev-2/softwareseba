<div class="modal fade" tabindex="-1" id="inTimeModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">In Time</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" >&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <form method="POST" action="{{ route('attendanceStoreIn') }}" enctype="multipart/form-data" class="in_data_form" novalidate>
                                @csrf
                                <div class="row">
                                    {{-- <div class="col-sm-3">
                                        <label for="">Department *</label>
                                        <select class="form-control " id="deptID" name="deptID" required>

                                            <option value="" disabled selected>-- Select One --</option>
                                            @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Designation *</label>
                                        <select class="form-control " id="desigID" name="desigID" required>

                                        </select>
                                    </div> --}}

                                    <div class="col-sm-6">
                                        <label for="">Employee ID*</label>
                                        <select class="form-control employee_select2" id="empID" name="empID" required>
                                            <option value="">-- wait --</option>

                                        </select>
                                    </div>
                                    @php
                                        date_default_timezone_set("Asia/Dhaka");
                                    @endphp
                                    <div class="col-sm-3">
                                        <label for="">Duty Date *</label>
                                        <input type="text" class="datepicker form-control " id="dutyDate" name="dutyDate" autocomplete="off" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                </br>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="">Shift *</label>
                                        <select class="form-control " id="shiftID" name="shiftID" required>
                                            <option value="">-- Select One --</option>
                                                @foreach ($shifts as $shift)
                                                <option value="{{ $shift->id }}">{{ $shift->shiftName }}</option>
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <label for="">In Time *</label>
                                        <input type="text" class=" form-control datetimepicker" id="inTime" name="inTime" autocomplete="off">
                                    </div>


                                    <div class="col-sm-3">
                                        <button class="btn btn-sm btn-primary mt-4 " type="submit">
                                            <i class="fa fa-save pr-2"></i>Save
                                        </button>
                                    </div>
                                </div>
                                <!-- </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
