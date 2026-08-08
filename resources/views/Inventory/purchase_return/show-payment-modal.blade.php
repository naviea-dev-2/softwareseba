<div id="show_payments"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content" style="width:650px;">
        <div class="container mt-3 pb-0 pt-0 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    {{-- <button id="print-btn-payment" type="button" class="btn btn-default btn-sm d-print-none"><i class="bx bx-printer"></i> Print </button> --}}
                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{  auth()->user()->business->business_name }}</h3>
                </div>
                <div class="col-md-3" style="text-align: right;">
                    <button type="button" id="close-btn" data-bs-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="bx bx-x"></i></span></button>
                </div>
                <div class="col-md-12 text-center">
                   <Strong>Purchase Return Payemnts</Strong>
                </div>
            </div>
        </div>
        <div id="view-ajax-data-payments"></div>
      </div>
    </div>
</div>
