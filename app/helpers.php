<?php

use App\Models\SiteSetting;
use App\Models\Tp_option;
use Illuminate\Support\Str;
if (! function_exists('str_slug')) {
	function str_slug($str) {

		$str_slug_v = Str::slug($str, "-");

		return $str_slug_v;
	}
}
if (! function_exists('str_slug_c')) {
	function str_slug_c($str) {

		$str_slug_v = Str::slug($str, "_");

		return $str_slug_v;
	}
}
if (! function_exists('esc')) {
	function esc($string){
		$string = (string) $string;

		if ( 0 === strlen($string) ) {
			return '';
		}

		$string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

		return $string;
	}
}
if (! function_exists('findAttr')) {
	function findAttr($variation,$atttribute_set){
		return $variation->items->firstWhere(function ($item) use ($atttribute_set) {
			return $item->attribute->attribute_set_id == $atttribute_set->attribute_set_id;
		})->attribute->title ?? '-';
	}
}
if (! function_exists('mapped_implode')) {
    function mapped_implode(string $glue, array $array, string $symbol = '='): string
    {
        return implode(
            $glue,
            array_map(
                function ($k, $v) use ($symbol) {
                    return $k . $symbol . $v;
                },
                array_keys($array),
                array_values($array)
            )
        );
    }

}
if (! function_exists('f_gtext')) {
	function f_gtext()
	{
		$id = auth()->user()->business_id;
		$data =array();
		$theme_color = Tp_option::where('option_name', 'f_theme_color')->where('business_id', $id)->get();

		$theme_color_id = '';
		foreach ($theme_color as $row){
			$theme_color_id = $row->id;
		}

		if($theme_color_id != ''){
			$tcData = json_decode($theme_color);
			$tcObj = json_decode($tcData[0]->option_value);
			$data['header_back_color'] =  $tcObj->header_back_color ?? '#fff';
			$data['header_font_color'] =  $tcObj->header_font_color ?? '#000';
			$data['header_btn_back_color'] =  $tcObj->header_btn_back_color ?? '#fff';
			$data['header_btn_font_color'] =  $tcObj->header_btn_font_color ?? '#000';
			//Sidebar
			$data['sidebar_back_color'] =  $tcObj->sidebar_back_color ?? '#fff';
			$data['sidebar_font_color'] =  $tcObj->sidebar_font_color ?? '#000';
			$data['sidebar_back_hover_color'] =  $tcObj->sidebar_back_hover_color ?? '#fff';
			$data['sidebar_font_hover_color'] =  $tcObj->sidebar_font_hover_color ?? '#000';
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
		return $data;
	}
}
if (! function_exists('load_pack_option')) {
	function load_pack_option(){
		$user = auth()->user();
		//dd($user);
		$arr_con = [];
		if($user->user_type == 0){
			// $arr_con = ['inventory','hr-payroll','accounts','general'];
			$au_business = $user->business;  
			if($au_business->package){
				$arr_con = json_decode($au_business->package?->pack_option,true);
				array_push($arr_con,'general');
			}else{
				$arr_con = ['inventory','hr-payroll','accounts','general'];
			}
			
		}else{
			$au_business = $user->business;
			if($au_business->package){
				$arr_con = json_decode($au_business->package?->pack_option,true);
				array_push($arr_con,'general');
			}else{
				$arr_con = ['inventory','hr-payroll','accounts','general'];
			}
			
		}
		return $arr_con;
	}
}
if (! function_exists('can_p')) {
	function can_p($route_name){
		//return true;
		$user = auth()->user();
		$au_business = $user->business;
		if($au_business->business_type_id == 17){
			return true;
		}
		if($user->user_type == 0){
			$arr_con = [];
			if($au_business->package){
				if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false){
					return false;
				}
				$arr_con = [];
				if($au_business->package){
					$arr_con = json_decode($au_business->package?->pack_option,true);
				}
				array_push($arr_con,'general');
			}else{
				$results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
				$data = array();
				if($results){
					$dataObj = json_decode($results->option_value);
					$data['days'] = $dataObj->days;
				}else{
					$data['days'] = 0;
				}

				$user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days'])->format('Y-m-d');
				$user_now_date = \Carbon\Carbon::now()->format('Y-m-d');
				if($user_now_date > $user_end_date){
					return false;
				}
				$arr_con = ['inventory','hr-payroll','accounts','general'];
			}
// 			dd($arr_con);
// 			dd($route_name);
			$m_menu = \App\Models\Permission::whereIn('section',array_values($arr_con))->where('route_name',$route_name)->first();
			if($m_menu == null){
			    	dd($route_name);
			}
// 			dd($m_menu->is_condition);
			$ex_con = true;
			if((int)$m_menu->is_condition == 1){
			 //   dd("ss");
				//$arr_con_a = ['inventory','hr-payroll','accounts','general','crm'];
				$arr_con_val = in_array($m_menu->condition_type,$arr_con);
				if($m_menu->condition_val == 0){
					if($arr_con_val == false){
						$ex_con = true;
					}else{
						$ex_con =false;
					}
				}else{
					if($arr_con_val == false){
						$ex_con = false;
					}else{
						$ex_con =true;
					}
				}
			}else  if($m_menu->is_condition == 2){
				if(auth()->user()->business->business_type_id == $m_menu->condition_val){
					$ex_con = false;
				}
			}
			if($m_menu && $ex_con){
				return true;
			}else{
				return false;
			}
		}else{
		  //  dd("sss");
			if($au_business->package){
				if(\Carbon\Carbon::now()->lte($au_business->pack_end_date) == false){
					return false;
				}
				$arr_con = [];
				if($au_business->package){
					$arr_con = json_decode($au_business->package?->pack_option,true);
				}
				array_push($arr_con,'general');
			}else{
				$results = \App\Models\Tp_option::where('option_name', 'user_limit')->first();
				$data = array();
				if($results){
					$dataObj = json_decode($results->option_value);
					$data['days'] = $dataObj->days;
				}else{
					$data['days'] = 0;
				}

				$user_end_date = \Carbon\Carbon::parse($au_business->start_date)->addDays((int)$data['days'])->format('Y-m-d');
				$user_now_date = \Carbon\Carbon::now()->format('Y-m-d');
				if($user_now_date > $user_end_date){
					return false;
				}
				$arr_con = ['inventory','hr-payroll','accounts','general','crm'];
			}
			// 		dd($user);
			$m_menu = \App\Models\RolePermission::Join('permissions','permissions.id','role_permissions.permission_id')->where('role_permissions.role_id',$user->role_id)->whereIn('permissions.section',array_values($arr_con))->where('permissions.route_name',$route_name)->first();
			//dd($m_menu);
			if($m_menu){
				$ex_con = true;
				if($m_menu->is_condition == 1){
					//$arr_con_a = ['inventory','hr-payroll','accounts','general','crm'];
					$arr_con_val = in_array($m_menu->condition_type,$arr_con);
					if($m_menu->condition_val == 0){
						if($arr_con_val == false){
							$ex_con = true;
						}else{
							$ex_con =false;
						}
					}else{
						if($arr_con_val == false){
							$ex_con = false;
						}else{
							$ex_con =true;
						}
					}
				}else  if($m_menu->is_condition == 2){
					if(auth()->user()->business->business_type_id == $m_menu->condition_val){
						$ex_con = false;
					}
				}
				if($m_menu && $ex_con){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}
		
	}
}
if (! function_exists('gtext')) {
	function gtext()
	{
		$data = array();
		//theme_logo
		$site_setting = SiteSetting::first();



		if($site_setting){

			$data['favicon'] = $site_setting->favicon;
			$data['front_logo'] = $site_setting->header_image;
			$data['back_logo'] = $site_setting->header_image;
			$data['company_name'] = $site_setting->company_name;
			$data['right_text'] = $site_setting->right_text;
		}else{
			$data['company_name'] = '';
			$data['favicon'] = '';
			$data['front_logo'] = '';
			$data['back_logo'] = '';
			$data['right_text'] = '';
		}
		//facebook
		$facebook = Tp_option::where('option_name', 'facebook')->get();

		$facebook_id = '';
		foreach ($facebook as $row){
			$facebook_id = $row->id;
		}

		if($facebook_id != ''){
			$facebookData = json_decode($facebook);
			$facebookObj = json_decode($facebookData[0]->option_value);
			$data['fb_app_id'] = $facebookObj->fb_app_id;
			$data['fb_publish'] = $facebookObj->is_publish;
		}else{
			$data['fb_app_id'] = '';
			$data['fb_publish'] = '';
		}

		//twitter
		$twitter = Tp_option::where('option_name', 'twitter')->get();

		$twitter_id = '';
		foreach ($twitter as $row){
			$twitter_id = $row->id;
		}

		if($twitter_id != ''){
			$twitterData = json_decode($twitter);
			$twitterObj = json_decode($twitterData[0]->option_value);
			$data['twitter_id'] = $twitterObj->twitter_id;
			$data['twitter_publish'] = $twitterObj->is_publish;
		}else{
			$data['twitter_id'] = '';
			$data['twitter_publish'] = '';
		}

		//Theme Option SEO
		$theme_option_seo = Tp_option::where('option_name', 'theme_option_seo')->get();

		$theme_option_seo_id = '';
		foreach ($theme_option_seo as $row){
			$theme_option_seo_id = $row->id;
		}

		if($theme_option_seo_id != ''){
			$SEOData = json_decode($theme_option_seo);
			$SEOObj = json_decode($SEOData[0]->option_value);
			$data['og_title'] = $SEOObj->og_title;
			$data['og_image'] = $SEOObj->og_image;
			$data['og_description'] = $SEOObj->og_description;
			$data['og_keywords'] = $SEOObj->og_keywords;
		}else{
			$data['og_title'] = '';
			$data['og_image'] = '';
			$data['og_description'] = '';
			$data['og_keywords'] = '';
		}

		//Theme Option Facebook Pixel
		$theme_option_facebook_pixel = Tp_option::where('option_name', 'facebook-pixel')->get();

		$theme_option_fb_pixel_id = '';
		foreach ($theme_option_facebook_pixel as $row){
			$theme_option_fb_pixel_id = $row->id;
		}

		if($theme_option_fb_pixel_id != ''){
			$fb_PixelData = json_decode($theme_option_facebook_pixel);
			$fb_PixelObj = json_decode($fb_PixelData[0]->option_value);
			$data['fb_pixel_id'] = $fb_PixelObj->fb_pixel_id;
			$data['fb_pixel_publish'] = $fb_PixelObj->is_publish;
		}else{
			$data['fb_pixel_id'] = '';
			$data['fb_pixel_publish'] = '';
		}

		//Theme Option Google Analytics
		$theme_option_google_analytics = Tp_option::where('option_name', 'google_analytics')->get();

		$theme_option_ga_id = '';
		foreach ($theme_option_google_analytics as $row){
			$theme_option_ga_id = $row->id;
		}

		if($theme_option_ga_id != ''){
			$gaData = json_decode($theme_option_google_analytics);
			$gaObj = json_decode($gaData[0]->option_value);
			$data['tracking_id'] = $gaObj->tracking_id;
			$data['ga_publish'] = $gaObj->is_publish;
		}else{
			$data['tracking_id'] = '';
			$data['ga_publish'] = '';
		}

		//Theme Option Google Tag Manager
		$theme_option_google_tag_manager = Tp_option::where('option_name', 'google_tag_manager')->get();

		$theme_option_gtm_id = '';
		foreach ($theme_option_google_tag_manager as $row){
			$theme_option_gtm_id = $row->id;
		}

		if($theme_option_gtm_id != ''){
			$gtmData = json_decode($theme_option_google_tag_manager);
			$gtmObj = json_decode($gtmData[0]->option_value);
			$data['google_tag_manager_id'] = $gtmObj->google_tag_manager_id;
			$data['gtm_publish'] = $gtmObj->is_publish;
		}else{
			$data['google_tag_manager_id'] = '';
			$data['gtm_publish'] = '';
		}

		$theme_color = Tp_option::where('option_name', 'theme_color')->get();

		$theme_color_id = '';
		foreach ($theme_color as $row){
			$theme_color_id = $row->id;
		}

		if($theme_color_id != ''){
			$tcData = json_decode($theme_color);
			$tcObj = json_decode($tcData[0]->option_value);
			$data['header_back_color'] =  $tcObj->header_back_color ?? '#fff';
			$data['header_font_color'] =  $tcObj->header_font_color ?? '#000';
			$data['header_btn_back_color'] =  $tcObj->header_btn_back_color ?? '#fff';
			$data['header_btn_font_color'] =  $tcObj->header_btn_font_color ?? '#000';
			//Sidebar
			$data['sidebar_back_color'] =  $tcObj->sidebar_back_color ?? '#fff';
			$data['sidebar_font_color'] =  $tcObj->sidebar_font_color ?? '#000';
			$data['sidebar_back_hover_color'] =  $tcObj->sidebar_back_hover_color ?? '#fff';
			$data['sidebar_font_hover_color'] =  $tcObj->sidebar_font_hover_color ?? '#000';
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

		//custom_css
		$custom_css_data = Tp_option::where('option_name', 'custom_css')->get();
		$custom_css = '';
		foreach ($custom_css_data as $row){
			$custom_css = $row->option_value;
		}
		$data['custom_css'] = $custom_css;

		//custom_js
		$custom_js_data = Tp_option::where('option_name', 'custom_js')->get();
		$custom_js = '';
		foreach ($custom_js_data as $row){
			$custom_js = $row->option_value;
		}
		$data['custom_js'] = $custom_js;

		return $data;

	}
}
if (! function_exists('check_business_type')) {
	function check_business_type($type){
		$business_types = [
			'1'=>'Clothing & Brand',
			'2'=>'Super Shop',
			'3'=>'Cosmetices Shop',
			'4'=>'Jewellery Shop',
			'5'=>'Pharmacy Shop',
			'6'=>'Mobile Shop',
			'7'=>'Glossary Shop',
			'8'=>'Agro Farm',
			'9'=>'Ecommerce & F-commerce',
			'10'=>'Restaurant',
			'11'=>'Electric & Electronics',
			'12'=>'Trading & Traders',
			'13'=>'Book Shop',
			'14'=>'Computer Shop',
			'15'=>'Dealership',
			'16'=>'Software Company',
		];
		return $business_types[$type] ?? '';
	}
}
if (! function_exists('b_types')) {
	function b_types(){
		$business_types = [
			''=>'Select Business Type',
			'1'=>'Clothing & Brand',
			'2'=>'Super Shop',
			'3'=>'Cosmetices Shop',
			'4'=>'Jewellery Shop',
			'5'=>'Pharmacy Shop',
			'6'=>'Mobile Shop',
			'7'=>'Glossary Shop',
			'8'=>'Agro Farm',
			'9'=>'Ecommerce & F-commerce',
			'10'=>'Restaurant',
			'11'=>'Electric & Electronics',
			'12'=>'Trading & Traders',
			'13'=>'Book Shop',
			'14'=>'Computer Shop',
			'15'=>'Dealership',
			'16'=>'Software Company',
		];
		return $business_types;
	}
}
if (! function_exists('f_b_types')) {
	function f_b_types(){
		$business_types = [
			'1'=>'clothing & brand',
			'2'=>'super shop',
			'3'=>'cosmetices shop',
			'4'=>'jewellery shop',
			'5'=>'pharmacy shop',
			'6'=>'mobile shop',
			'7'=>'glossary shop',
			'8'=>'agro farm',
			'9'=>'ecommerce & e-commerce',
			'10'=>'restaurant',
			'11'=>'electric & electronics',
			'12'=>'trading & traders',
			'13'=>'book shop',
			'14'=>'computer shop',
			'15'=>'dealership',
			'16'=>'Software Company',
		];
		return $business_types;
	}
}
