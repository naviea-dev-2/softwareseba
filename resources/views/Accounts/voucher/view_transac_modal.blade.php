
<style>

    td,th {
        height: 30px !important;
    }
        .modal-lg, .modal-xl {
            max-width: 1500px;
        }
</style>


    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12" id="edit-handler">
                    <div class="card">
                        <div class="card-body">
				            <div class="col-12 pt-5">
				                <div class="table-responsive">
				                    <table class="table table-striped table-hover" style="width:100%;border-collapse: collapse;">
				                        <tbody>
				                            <tr>
				                                <td style="text-align: left; width: 38%">
								                    <h4>{{$info->site_name}}</h4>
                                                    <sub style="width: 100%">---------------------------------------------------------------------------</sub>
                                                    <h6>&nbsp;L&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; m&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; i&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; e&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; d</h6>
								                    Address: <span>{{$info->address}}</span><br>
								                    Cell: <span>{{$info->phone}} </span><br>
								                    Email: <span>{{$info->email}}, Web: www.resturantmanagement.com</span>
				                                </td>
				                                <td>
				                                    <h3 style="margin-top: 104px;">
                                                        @if($transactions->transactions_type == 'Debit')
                                                            DEBIT VOUCHER
                                                        @else
                                                            CREDIT VOUCHER
                                                        @endif

                                                    </h3>
				                                </td>
				                                <td>
				                                    <span>Voucher No. <input type="text" name="" readonly="" value="{{$transactions->voucher_no}}"></span> <br>
				                                   <span style="margin-left: 43px;">Date: <input type="text" name="" style="margin-top: -1px;" readonly="" value="{{$transactions->date}}"></span>
				                                </td>
				                            </tr>
				                        </tbody>
				                    </table>
				                </div>
				            </div>				            

				            <div class="col-12">
				                <div class="table-responsive">
                                <table class="table table-bordered" style="border: 1px solid black;border-collapse: collapse;">
                                    <thead>
	                                    <tr>
                                            <th scope="col" style="border: 1px solid black;width: 146px;">Note </th>
                                            <td style="border: 1px solid black;">{{$transactions->note ?? ''}} </td>
                                            <td style="border: 1px solid black; ">- </td>
                                            <td style="border: 1px solid black;">- </td>
	                                    </tr>
                                    </thead>
                                     <tbody>
                                     	<tr>
                                             
	                                     	 <td style="border: 1px solid black">{{$transactions->method->name ?? ''}} <span><input type="checkbox" name="" checked=""></span></td>
                                             

	                                     	<td style="border: 1px solid black">A/C Name.{{$transactions->balance_account->account_name ?? ''}} </td>
	                                     	<td style="border: 1px solid black">Dt. {{$transactions->date}}</td>
	                                     	<td style="border: 1px solid black">A/C. {{$transactions->balance_account->account_number ?? ''}}</td>
                                     	</tr>
                                    </tbody>
                                </table>
				                </div>
				            </div>

                            <div id="AllLedger">
                                <table class="table table-bordered" style="border: 1px solid black;border-collapse: collapse;"  align="center">
                                    <thead>
                                    	<tr>
                                    		<b><h6 style="margin-left: 16px;">Received From : {{$transactions->received_from}}</h6> </b>

                                    		<b><h6 style="margin-left: 16px;"> Head of A/C : 
                                                @if($transactions->account_head_id != NULL)
                                                   {{$transactions->account_head->title ?? ''}}
                                                @else
                                                    
                                                @endif

                                            </h6> </b>
                                    	</tr><br>
	                                    <tr>
	                                        <th scope="col" class="text-center" style="border: 1px solid black">Description</th>
	                                        <th scope="col" class="text-center" style="border: 1px solid black">A/C. Code No.</th>
	                                        <th scope="col" class="text-center" style="border: 1px solid black">Amount (In Taka)</th>
	                                    </tr>
                                    </thead>
                                     <tbody>
                                        @foreach($transacs as $data)
                                     	<tr>
	                                     	<td style="border: 1px solid black" class="text-right">{{$data->account_sub_head->account_title}}</td>
	                                     	<td style="border: 1px solid black" class="text-right">{{$data->ac_code}}</td>
	                                     	<td style="border: 1px solid black" class="text-right">{{$data->amount}} /-</td>	
                                     	</tr>
                                        @endforeach
                                     	<tr>
	                                     	<td style="border: 1px solid black; text-align: right; font-weight: bold" colspan="2">Total Taka</td>	

	                                     	<td style="border: 1px solid black" class="text-right">{{$transactions->total}} /-</td>	

                                     	</tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: right; font-weight: bold" colspan="2">Pay Taka</td>   

                                            <td style="border: 1px solid black" class="text-right">{{$transactions->pay_amount ?? '0'}} /-</td>    

                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid black; text-align: right; font-weight: bold" colspan="2">Due Taka</td>   

                                            <td style="border: 1px solid black" class="text-right">{{$transactions->due_amount ?? '0'}} /-</td>    

                                        </tr>
                                     	<tr>
                                     		<td style="border: 1px solid black;" colspan="6">Taka (In Words):  @if($str_amount){{$str_amount}} Only @endif </td>
                                     	</tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 pt-5">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" style="width:100%;border-collapse: collapse;">
                                        <tbody>
                                            <tr class="text-center">
                                                <td>
                                                    <sub style="width: 100%">---------------------------------------------</sub>
                                                    <p>Receiver's Signature</p>
                                                </td>
                                                <td>
                                                    <sub style="width: 100%">---------------------------------------------</sub>
                                                    <p>Prepared by</p>
                                                </td>                                                
                                                <td>
                                                    <sub style="width: 100%">---------------------------------------------</sub>
                                                    <p>Accountant</p>
                                                </td>
                                                <td>
                                                    <sub style="width: 100%">---------------------------------------------</sub>
                                                    <p>Authorised Signature</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

