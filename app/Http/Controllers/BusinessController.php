<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    function getSetting(Request $request){
        if(can_p('business_setting') == false){
            return redirect()->route('dashboard');
        }
        $id = auth()->user()->business_id;
        $data['business'] = Business::find($id);
        $data['currencies'] = \App\Models\Inventory\Currency::where('business_id',$id)->get();
        //dd($data);
        return view('theme_option.theme-options',$data);
        // return view('business.setting',$data);
    }
    function setSetting (Request $request,$id){
        //dd($request->all());
        if(can_p('business_setting') == false){
            return redirect()->route('dashboard');
        }
        $this->validate($request,[
            'business_name'=>'required',
            'email_address'=>'required',
            'organization_type_id'=>'required',
            'business_type_id'=>'required',
            'business_logo'=>'image|mimes:jpeg,png,jpg,webp',
        ],
        [
           'organization_type_id.required' =>'Organization Type is Required',
           'business_type_id.required' =>'Buisness Type is Required',
        ]);
        $business = Business::find($id);
        $file=$request->file('business_logo');
        if($file){
            @unlink(public_path('upload/business/'.$business->business_logo));
            $filename=date('YmdHi')."_business".$file->getClientOriginalName();
            $file->move(public_path('upload/business'),$filename);
            $business->business_logo=$filename;
        }
        $business->business_name = $request->business_name;

        $business->mobile_number = $request->mobile_phone;
        $business->phone_number = $request->phone_number;
        $business->email = $request->email_address;
        $business->fax= $request->fax;
        $business->address1= $request->address_line_1 ?? '';
        $business->address2= $request->address_line_2 ?? '';
        $business->post_code= $request->postal_or_zip_code ?? '';
        $business->website= $request->website;
        $business->business_type_id = $request->business_type_id ?? 0;
        $business->oranization_id = $request->organization_type_id ?? 0;
        $business->currency_id = $request->currency ?? 0;
        $business->timezone_id = $request->timezone_id ?? 0;
        $business->country_id = $request->country ?? 0;
        $business->state_id = $request->state ?? 0;
        $business->city_id = $request->city ?? 0;
        $business->save();
        $notification=array(
                'message'=>"Save Success",
                'alert-type'=>'success'
            );

        return redirect()->route('business_setting',$id)->with($notification);
    }
}
