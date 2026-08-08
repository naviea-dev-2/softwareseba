<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PDF</title>
        <style type="text/css">
            /* html { -webkit-print-color-adjust: exact; } */
            table {
                border-spacing: 0;
                width: 100%;
                border-collapse: collapse;
            }
            .b_btn{
                color:#fff;
                font-size:15px;
                background-color:red;
            }
            @media print {
                body{
                    margin: 0;
                }
                @page{
                    margin:0px;
                    /* margin-top:5px;
                    margin-left:10px;
                    margin-right:10px; */
                    /* size: Letter; */
                    size:landscape;
                    /* margin-top: 20px; */

                }
                .print-div{
                    margin: 0;
                    /* margin-top:5px; */
                    page-break-after: always;
                    /* page-break-inside: avoid; */
                    /* height:1070px!important; */
                }
                .print-div1{
                    /* height:1060px!important; */
                }
                .print-div2{
                    /* height:1040px!important; */
                }
            }
        </style>
    </head>
    <body style="padding: 0;margin:0;">



        <table style="marign-top:10px;margin-left:20px;margin-right:20px;">
            <tr>
                <th rowspan="2" style="width: 100px;padding-top:10px;" valign="top">
                    <img style="width: 100px;height:100px;" src="{{ $business->business_logo_show }}" alt="{{ @$business->business_name }}">
                </th>
                <th style="text-align: left;padding-left:5px;padding-top:10px;" valign="top">
                    <p style="padding:0;margin:0;font-weight:bold;font-size:20px;">{{ $business?->business_name }}</p>
                    <p style="padding:0;margin:0;font-size:15px;color:#303030;">{{ $business?->address }}</p>
                    {{-- <div style="border-bottom:2px solid #000;margin-top:10px;font-size:20px;font-weight:bold;text-align:center;">{{ $voucher->v_type }} Voucher</div> --}}
                </th>
            </tr>
            <tr>

                <th style="text-align: center;padding-left:5px;padding-top:10px;" valign="top">

                    <div style="border-bottom:2px solid #000;margin-top:10px;font-size:20px;font-weight:bold;text-align:center;padding-left:-100px;">{{ $voucher->v_type }} Voucher</div>
                </th>
            </tr>
        </table>
        <table style="padding-left:0;marign-top:20px; margin-left:20px;margin-right:20px;">
            <tr style="padding-left:0px;">
                <td style="width: 20%;font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;padding-top:5px;">Voucher No.</td>
                <td style="width: 40%;font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;padding-top:5px;">: {{ $voucher->voucher_no }}</td>
                <td style="width: 40%;font-size: 15px;text-align:right;color:#303030;padding-left:0px;padding-right:10px;padding-top:5px;">Date: {{ \Carbon\Carbon::parse($voucher->voucher_date)->format('d-m-Y') }}</td>
            </tr>

            <tr>
                <td style="font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;">Ref. No.</td>
                <td colspan="2" style="font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;">: {{ $voucher->ref }}</td>
            </tr>
            <tr>
                <td style="font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;">Description</td>
                <td colspan="2" style="font-size: 15px;text-align:left;color:#303030;padding-left:0px;padding-right:10px;">: {{ $voucher->description }}</td>
            </tr>
        </table>
        <table style="width: 100%;margin-top:5px;margin-left:20px;margin-right:20px;">
            <thead style="background: #9bb39ca8;">
                <tr style="background: #9bb39ca8;">
                    <th style="width:50%;border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:center;color:#303030; padding-left:5px;padding-right:5px;">Ledger Name</th>
                    <th style="width:25%;border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:center;width:100px;color:#303030;padding-left:5px;padding-right:5px;">Debit</th>
                    <th style="width:25%;border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:center;width:100px;color:#303030;padding-left:5px;padding-right:5px;">Credit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_dr = 0;
                    $total_cr = 0;
                @endphp
                @foreach ($ledger_list as $ledger_v)
                @php
                    if($ledger_v->type == "debit"){
                        $total_dr += $ledger_v->amount;
                    }else{
                        $total_cr += $ledger_v->amount;
                    }


                @endphp
                @if($ledger_v->type == "debit")
                <tr>
                    <td  style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:left;color:#303030;padding-left:5px;padding-right:5px;">{{ $ledger_v->account?->title }}</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">{{ $ledger_v->amount }}</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">0</td>
                </tr>
                @else
                <tr>
                    <td  style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:left;color:#303030;padding-left:5px;padding-right:5px;">{{ $ledger_v->account?->title }}</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">0</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">{{ $ledger_v->amount }}</td>
                </tr>
                @endif
                @endforeach
                <tr>
                    <td  style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;padding-left:5px;padding-right:5px;">Total</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">{{ number_format($total_dr,2) }}</td>
                    <td style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 15px;text-align:right;width:100px;color:#303030;padding-left:5px;padding-right:5px;">{{ number_format($total_cr,2) }}</td>
                </tr>
                <tr>
                    @php

                    @endphp
                    <td colspan="3" style="border:1px solid #000;padding:1px;font-weight: 700;font-size: 14px;text-align:left;width:100px;color:#303030;padding-left:5px;padding-right:5px;">
                        <span style="font-weight: bold;">In Words : </span>
                        {{ $in_word }} Taka Only
                    </td>
                </tr>

            </tbody>
        </table>

        <div style="height:50px;"></div>
        <div style="margin-top:5px;margin-left:20px;margin-right:20px;">
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Approved By
            </div>
            <div style="float:left;width:19%;color:white;">sdfd</div>
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Received By
            </div>
            <div style="float:left;width:19%;color:white;">sdfsdf</div>
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Accountant
            </div>
        </div>
        <div style="height:30px;"></div>
        <div style="margin-top:5px;margin-left:20px;margin-right:20px;">
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Date
            </div>
            <div style="float:left;width:19%;color:white;">sdfd</div>
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Date
            </div>
            <div style="float:left;width:19%;color:white;">sdfsdf</div>
            <div style="text-align:center;font-size: 14px;border-top:1px dashed;float:left;width:20%;">
                Date
            </div>
        </div>
        <div style="position: absolute;bottom:15px;width:100%;">
            <table style="marign-top:5px;margin-left:20px;margin-right:20px;width:100%;border-spacing: 20px 0;">
                <tr>
                    <td  style="font-weight: bold;font-size: 8px;text-align:left;width: 47%;padding-left:5px;padding-right:5px;">
                        <span style="font-size: 8px;color: #7c7c7c;">Powered By: Navieasoft Ltd.</span>
                    </td>
                    <td style="width: 6%;">1</td>
                    <td style="font-weight: bold;font-size: 8px;text-align:right;width: 47%;padding-left:5px;padding-right:5px;">
                        <span>This is a Software Generated Receipt</span>
                    </td>
                </tr>
            </table>
        </div>

    </body>
</html>
