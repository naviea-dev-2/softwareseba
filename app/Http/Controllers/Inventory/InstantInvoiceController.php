<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account\PaymentMethod;

class InstantInvoiceController extends Controller
{
    function create(Request $request){
        $data['methods']=PaymentMethod::orderBy('id','DESC')->get();
        return view('Inventory.instance_invoice.create',$data);
    }
}
