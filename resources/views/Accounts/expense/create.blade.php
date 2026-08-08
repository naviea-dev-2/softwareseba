 <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#01303f;max-height: 50px;">
            <h5 class="modal-title" id="exampleModalLabel" style="color:white;line-height:18px;">Expense</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row row-card-one">
                        <div class="col-sm-12">
                            <!-- form here -->
                            <form method="POST" action="{{route('expense.store')}}" enctype="multipart/form-data" class="add_data_form">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="0" name="id"  required>


                                    <div class="col-sm-12">
                                        <label for="">Reason For*</label>
                                        <input type="text" class="form-control" name="reason" value="{{ old('reason') }}" placeholder="Reason For Expense" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                               
                                    <div class="col-sm-4">
                                        <label for="">Category *</label>
                                        <select name="category" class="form-control">
                                            <option value=""> select category</option>
                                            @foreach ($categories as  $category)
                                            <option @if(old('category') == $category->id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="">Date*</label>
                                        <input onclick="this.showPicker();" type="text" class="form-control datepicker" name="date"  placeholder="Reason For Expense" value="{{ old('date') ?? date('Y-m-d') }}" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="">Amount*</label>
                                        <input type="number" class="form-control" name="amount" value="{{ old('amount') ?? 0 }}" />
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    {{-- @if($payment_setting->status != 1) --}}
                                    <div class="col-sm-4">
                                        <label for="">Payment Method</label>
                                        <select name="payment_method" class="form-control" id="add_payment_method">
                                            <option value=""> select Method</option>
                                            @foreach ($methods as $method)
                                            <option @if(old('payment_method') == $method->id) selected @endif value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach

                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                    <div class="col-sm-4 bank_show">
                                        <label for="">Account *</label>
                                        <select id="add_account" name="account" class="form-control">
                                            <option value="">Select Account</option>


                                        </select>
                                        <span class="invalid-feedback mb-0"> </span>
                                    </div>
                                  

                                    <div class="col-sm-12">
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
