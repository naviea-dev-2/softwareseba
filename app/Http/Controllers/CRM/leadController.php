<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class leadController extends Controller
{
    function index(){
        return view('crm.lead.index');
    }
}
