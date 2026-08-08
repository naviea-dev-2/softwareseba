<div id="purchase-details"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content" style="width:650px;">

        <div class="container pt-0 mt-3 pb-2 border-bottom no-print">
            <div class="row justify-content-end">
                @if($p_print)
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="bx bx-printer"></i> Print </button>
                </div>
                @endif
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{ auth()->user()->business->business_name }}</h3>
                </div>
                <div class="col-md-3" style="text-align: right;">
                    <button type="button" id="close-btn" data-bs-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="bx bx-x"></i></span></button>
                </div>
                <div class="col-md-12 text-center">
                    <Strong>Sales Return Details</Strong>
                </div>
            </div>
        </div>
        
        <div class="receipt-header print-only">
            <div class="receipt-left">
                <img class="img-responsive" alt="iamgurdeeposahan" src="{{auth()->user()->business->business_logo_show}}" style="left: 10px;top: 5px;position: absolute;width: 71px;height: 71px; border-radius: 43px;">
            </div>
            <div class="receipt-right" style="text-align: center;">
                <h6 style="font-size: 16px;font-weight: bold;margin: 0 0 7px 0;">{{ auth()->user()->business->business_name }}</h6>
                <p style="font-size: 12px;margin: 0px;">+{{ auth()->user()->business->mobile_number }}<i class="fa fa-phone"></i></p>
                <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->email }}<i class="fa fa-envelope-o"></i></p>
                <p style="font-size: 12px;margin: 0px;">{{ auth()->user()->business->country?->name }} <i class="fa fa-location-arrow"></i></p>
            </div>
           
        </div>

        <div id="view-ajax-data"></div>
      </div>
    </div>
</div>
