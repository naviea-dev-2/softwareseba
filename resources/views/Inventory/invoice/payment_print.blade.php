<div class="receipt-main" id="payment-print-details">
               
    <div class="receipt-header">
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
    
    <h3 class="text-center">Payment Receipt</h3>
    <div class="receipt-header receipt-header-mid">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 text-left">
                <div class="receipt-right">
                    <h6 id="payment_print_customer_name"></h6>
                    <p style="margin: 0px;"><b>Mobile :</b> +<span id="payment_print_mobile"></span></p>
                    <p style="margin: 0px;"><b>Email :</b> <span id="payment_print_email"></span></p>
                    <p style="margin: 0px;"><b>Address :</b> <span id="payment_print_address"></span></p>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="receipt-left">
                    <h6>Sales Reference # <span id="payment_print_invoice_code"></span></h6>
                    <p style="margin: 0px;"><b>Payment Date :</b> <span id="payment_print_date"></span></p>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Account</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
               <tr>
                    <td id="payment_print_method"></td>
                    <td id="payment_print_account"></td>
                    <td id="payment_print_amount"></td>
               </tr>
            </tbody>
        </table>
    </div>
    
    <div class="row">
        <div class="receipt-header receipt-header-mid receipt-footer">
            <div class="col-xs-12 col-sm-12 col-md-12 text-left">
                <div class="receipt-right">
                    <p><b>Note :</b><span id="payment_print_note"></span></p>
                    
                </div>
            </div>
            {{-- <div class="col-xs-4 col-sm-4 col-md-4">
                <div class="receipt-left">
                    <h1>Stamp</h1>
                </div>
            </div> --}}
        </div>
    </div>
    
</div>