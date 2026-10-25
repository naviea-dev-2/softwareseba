
<div class="modal fade attendance-modal"
     id="outTimeModal"
     tabindex="-1"
     aria-labelledby="outTimeModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('attendanceStoreOut') }}"
                  class="out_data_form"
                  novalidate>

                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="outTimeModalLabel">
                            <i class="fa fa-sign-out me-2"></i>
                            Employee Check Out
                        </h5>
                        <small class="text-white-50">
                            Record employee departure time
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
                            <label for="empID2">
                                Employee <span class="text-danger">*</span>
                            </label>

                            <select class="form-control employee2_select2"
                                    id="empID2"
                                    name="empID"
                                    required>
                                <option value="">Select employee</option>
                            </select>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Duty Date --}}
                        <div class="col-md-6">
                            <label for="dutyDate2">
                                Duty Date <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control datepicker"
                                   id="dutyDate2"
                                   name="dutyDate"
                                   value="{{ now()->timezone('Asia/Dhaka')->format('Y-m-d') }}"
                                   placeholder="Select duty date"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Out Time --}}
                        <div class="col-md-6">
                            <label for="outTime">
                                Out Time <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   class="form-control datetimepicker"
                                   id="outTime"
                                   name="outTime"
                                   placeholder="Select departure time"
                                   autocomplete="off"
                                   required>

                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1"></i>
                                Confirm the employee and duty date before recording checkout.
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
                            class="btn btn-danger px-4">
                        <i class="fa fa-save me-1"></i>
                        Save Out Time
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
