 <!-- Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Bonus Pay</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <form method="POST" action="{{route('bonuspay.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="" name="id" value="0" required>
                                

                                    <div class="col-sm-6">
                                        <label for="">Employee ID *</label>
                                        <select class="form-control employee_select2" id="empID" name="empID" required>
                                            <option value="">-- wait --</option>
                                        </select>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Paid Date *</label>
                                        <input type="date"  class="form-control datepicker" name="paidDate" required/>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="">Bonus Amount *</label>
                                        <input type="number" step="any" class="form-control " name="bonusAmount" placeholder="12000" required/>
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Occasion</label>
                                        <input type="text" name="occation"  class="form-control">
                                        <span class="invalid-feedback mb-0"></span>
                                    </div>

                                   
                                    @if($payment_setting->status != 1)
                                    <div class="col-sm-3">
                                       <label for="">Payment Method</label>
                                        <select name="payment_method" class="form-control" id="add_payment_method">
                                            <option value=""> select Method</option>
                                            @foreach ($methods as $method)
                                            <option @if(old('payment_method') == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach

                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    @if(array_search('accounts',load_pack_option()) != false)

                                   <div class="col-sm-6 bank_show">
                                        <label for="">Account *</label>
                                        <select id="add_account" name="account" class="form-control">
                                            <option value="">Select Account</option>


                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    @endif
                                    @endif
                                    <div class="col-sm-12" style="text-align:right;">
                                        <button class="btn btn-primary " type="submit">
                                            <i class="fa fa-save pr-2"></i>Pay
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
<!-- end modal outTime -->
