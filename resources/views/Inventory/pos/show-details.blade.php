<div id="purchase-details"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content" style="width:650px;">
            <div class="container pt-0 mt-3 pb-2 border-bottom no-print">
                <div class="">
                    <div class="row justify-content-end">
                       
                        <div class="col-md-4">
                            <button id="print-btn" pos_id="" type="button" class="btn btn-default btn-sm d-print-none"><i class="bx bx-printer"></i> Print </button>
                        </div>
                        
                        <div class="col-md-4">
                            <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{ auth()->user()->business->business_name }}</h3>
                        </div>
                        <div class="col-md-4" style="text-align: right;">
                            <button type="button" id="close-btn" data-bs-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true"><i class="bx bx-x"></i></span></button>
                        </div>
                        <div class="col-md-12 text-center">
                            <Strong>Sales Details</Strong>
                        </div>
                    </div>
                </div>
            
            </div>
            <div id="view-ajax-data"></div>
        </div>
    </div>
</div>