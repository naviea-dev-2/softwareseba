<div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content" style="width: 600px;">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">Add Payment</h5>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="bx bx-x"></i></span></button>
            </div>
            <div class="modal-body">

                <form method = 'post' action="{{ route('invoice.add-payment') }}" class = 'add_payment_data_form'>
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-8 text-right">
                                    Payment Method *
                                </label>
                                <Select class="form-control" name="payment_method" id="payment_method">
                                    <option value="">Select Method</option>
                                    @foreach ($methods as $method)
                                        <option @if(old('payment_method') == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </Select>
                               <span class="invalid-feedback mb-0"></span>
                            </div>
                        </div>
                        <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-md-8 text-right">
                                        Account *
                                    </label>
                                    <Select class="form-control" name="account" id="add_account">
                                        <option value="">Select Account</option>

                                    </Select>

                                    <span class="invalid-feedback mb-0"></span>

                                </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Date *</label>
                                <input type="date" name="payment_date" onclick="this.showPicker();" value="{{ date('Y-m-d') }}" class="form-control">
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Amount *</label>
                                <input type="number" step="any" name="amount" id="add_amount" class="form-control">
                                <span class="invalid-feedback mb-0"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Payment Note</label>
                        <textarea rows="2" class="form-control" name="payment_note"></textarea>
                        <span class="invalid-feedback mb-0"></span>
                    </div>
                    <input type="hidden" name="due_amount" id="pay_due_amount">
                    <input type="hidden" name="invoice_id" id="pay_invoice_id">
                    <div style="text-align: right;">
                        <button type="submit" class="mt-2 btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
