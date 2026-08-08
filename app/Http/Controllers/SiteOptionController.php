<?php

namespace App\Http\Controllers;

use App\Models\Tp_option;
use Illuminate\Http\Request;

class SiteOptionController extends Controller
{
    function getThemeOptionsColorPageLoad(Request $request){
        if(can_p('theme-options-color') == false){
            return redirect()->route('dashboard');
        }
        $id = auth()->user()->business_id;
        $results = Tp_option::where('option_name', 'f_theme_color')->where('business_id', $id)->first();
        $data = array();
		if($results){

            $dataObj = json_decode($results->option_value);
            $data['header_back_color'] =  $dataObj->header_back_color ?? '#fff';
            $data['header_font_color'] =  $dataObj->header_font_color ?? '#000';
            $data['header_btn_back_color'] =  $dataObj->header_btn_back_color ?? '#fff';
            $data['header_btn_font_color'] =  $dataObj->header_btn_font_color ?? '#000';
            //Sidebar
            $data['sidebar_back_color'] =  $dataObj->sidebar_back_color ?? '#fff';
            $data['sidebar_font_color'] =  $dataObj->sidebar_font_color ?? '#000';
            $data['sidebar_back_hover_color'] =  $dataObj->sidebar_back_hover_color ?? '#fff';
            $data['sidebar_font_hover_color'] =  $dataObj->sidebar_font_hover_color ?? '#000';

        }else{
            //Header
            $data['header_back_color'] =  '#fff';
            $data['header_font_color'] =  '#000';
            $data['header_btn_back_color'] =  '#fff';
            $data['header_btn_font_color'] = '#000';
            //Sidebar
            $data['sidebar_back_color'] =  '#fff';
            $data['sidebar_font_color'] = '#000';
            $data['sidebar_back_hover_color'] = '#fff';
            $data['sidebar_font_hover_color'] = '#000';
        }

        $datalist = $data;
        return view('theme_option.theme-options-color', compact('datalist'));
    }
    public function saveThemeOptionsColor (Request $request){
        //dd($request);
        if(can_p('theme-options-color') == false){
            return redirect()->route('dashboard');
        }
        $og_title = '';
		$og_description = '';
		$og_keywords = '';
		$og_image = '';
        try{
            $id = auth()->user()->business_id;
            $gData = Tp_option::where('option_name', 'f_theme_color')->where('business_id', $id)->first();

            $option = array();
           //Header
            $option['header_back_color'] =  $request->header_back_color ?? '#3bb77e';
            $option['header_font_color'] =  $request->header_font_color ?? '#3bb77e';
            $option['header_btn_back_color'] =  $request->header_btn_back_color ?? '#3bb77e';
            $option['header_btn_font_color'] =  $request->header_btn_font_color ?? '#3bb77e';
            //Sidebar
            $option['sidebar_back_color'] =  $request->sidebar_back_color ?? '#3bb77e';
            $option['sidebar_font_color'] =  $request->sidebar_font_color ?? '#3bb77e';
            $option['sidebar_back_hover_color'] =  $request->sidebar_back_hover_color ?? '#3bb77e';
            $option['sidebar_font_hover_color'] =  $request->sidebar_font_hover_color ?? '#3bb77e';



            $data = array(
                'option_name' => 'f_theme_color',
                'business_id' => $id,
                'option_value' => json_encode($option)
            );
            //dd($gData);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                return back()->with('success',__('Data Updated Successfully'));
            }else{
               // dd($data);
                $response = Tp_option::create($data);
                return back()->with('success',__('New Data Added Successfully'));
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            return back()->with('success',__('Data update failed'));
        }
    }
}
