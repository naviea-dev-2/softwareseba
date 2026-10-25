
<div class="modal fade attendance-modal"
     id="inTimeModal"
     tabindex="-1"
     aria-labelledby="inTimeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('attendanceStoreIn') }}"
                  class="in_data_form"
                  novalidate>

                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="inTimeModalLabel">
                            <i class="fa fa-sign-in me-2"></i>
                            Employee Check In
                        </h5>
                        <small class="text-white-50">
                            Record employee arrival time
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Employee --}}
                        <div class="col-12">
                            <label for="empID">
                                Employee <span class="text-danger">*</span>
                            </label>

                            <select class="form-control employee_select2"
                                    id="empID"
                                    name="empID"
                                    required>
                                <option value="">Select employee</option>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Duty Date --}}
                        <div class="col-md-6">
                            <label for="dutyDate">
                                Duty Date <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control datepicker"
                                   id="dutyDate"
                                   name="dutyDate"
                                   value="{{ now()->timezone('Asia/Dhaka')->format('Y-m-d') }}"
                                   placeholder="Select duty date"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Shift --}}
                        <div class="col-md-6">
                            <label for="shiftID">
                                Shift <span class="text-danger">*</span>
                            </label>

                            <select class="form-control"
                                    id="shiftID"
                                    name="shiftID"
                                    required>
                                <option value="">Select shift</option>

                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">
                                        {{ $shift->shiftName }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- In Time --}}
                        <div class="col-md-6">
                            <label for="inTime">
                                In Time <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control datetimepicker"
                                   id="inTime"
                                   name="inTime"
                                   placeholder="Select arrival time"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1"></i>
                                Verify the employee, duty date and shift before saving.
                            </small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-success px-4">
                        <i class="fa fa-save me-1"></i>
                        Save In Time
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
