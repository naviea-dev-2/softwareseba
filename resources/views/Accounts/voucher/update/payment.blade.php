
<div class="br-section-wrapper data-update pt-4">
    <div class="row">
        <div class="col-md-8">

                <h6 class="br-section-label text-center mb-1">Edit Voucher Entry (Debit Voucher)</h6>
                <div id="create_errors"></div>

                <div class="form-layout form-layout-4 pt-1 pb-0" style="border: 1px solid;padding: 10px;">

                    <form id="data-form-create" action="{{ route('account.voucher.update',$voucher->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Payment Date: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="p_date" id="p_date" value="{{ $voucher->voucher_date }}" class="form-control fl-datepicker"  required>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Ref.: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <input type="text" name="ref" id="ref" class="form-control" value="{{ $voucher->ref }}">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <table class="table table-bordered" >
                                    <thead class="thead-colored thead-light ">
                                        <tr>
                                            <th style="background-color: #e9ecef;">Payment For</th>
                                            <th style="background-color: #e9ecef;">Amount</th>
                                            <th style="background-color: #e9ecef;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_table">

                                        @foreach ($voucher->details as $detail)
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <select name="old_ledgers[{{ $detail->id }}]" data-id="{{ $detail->id }}" id="p_ledger_{{ $detail->id }}" class="form-control a-old_payment check_ledger" >
                                                        <option value=""> Select Ledger</option>
                                                    </select>
                                                </td>
                                                <td style="padding: 5px;">
                                                    <input type="number" name="old_amount[{{ $detail->id }}]" data-id="{{ $detail->id }}" value="{{ $detail->debit }}" class="form-control a-old_amount check_amount">
                                                </td>
                                                <td style="padding: 5px;vertical-align:middle;text-align:center;">
                                                    <div>
                                                        <button type="button" class="btn btn-success add_row btn-sm "><i class="bx bx-plus-circle"></i> </button>
                                                        <button type="button" data-id="{{ $detail->id }}" class="btn btn-danger old_del_row2 btn-sm "><i class="bx bx-trash"></i> </button>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="thead-colored thead-info ">
                                        <input type="hidden" id="total_amount_input" name="total_amount_input" value="{{ $voucher->voucher_amount }}">
                                        <tr>
                                            <th style="background-color: #17a2b8;text-align: right;color:white;font-weight:bold;font-size:15px;">Total</th>
                                            <th style="background-color: #17a2b8;text-align: right;color:white;font-weight:bold;font-size:15px;" id="total_amount">{{ $voucher->voucher_amount }}</th>
                                            <th style="background-color: #17a2b8;"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Payment Method: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="">Select Method</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label class="form-control-label">Account: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <select name="add_account" id="add_account" class="form-control">
                                        <option value="">Select Account</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 mt-2">
                                <label class="form-control-label">Description: <span class="tx-danger">*</span></label>
                                <div class="mg-t-10 mg-sm-t-0">
                                    <textarea class="form-control" rows="4" name="description" id="description">{{ $voucher->description }}</textarea>
                                </div>
                            </div>
                        </div>



                        <div class="row mt-3 mb-3">
                            <div class="col-sm-12 mg-t-10 mg-sm-t-0 text-right" style="text-align: right;">
                            {{-- <a href="javascript:void(0);" type="button" class="btn btn-secondary text-white mr-2 btn-cancel" >Cancel</a> --}}
                            <button type="btn" class="btn btn-info" id="cus-submit-btn">Update</button>
                            </div>
                        </div>
                    </form>

                </div>


        </div>
    </div>
</div>

