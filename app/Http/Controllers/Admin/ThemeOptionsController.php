<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Tp_option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ThemeOptionsController extends Controller
{
    function getThemeOptionsPageLoad(Request $request){
         $site_setting = SiteSetting::FirstorNew();
        return view('admin.theme_option.theme-options', compact('site_setting'));
    }
    public function saveThemeLogo(Request $request){

        try{
            $this->validate($request,[
                'header_logo'=>'image|mimes:jpeg,png,jpg,webp',
                'favicon'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
            $site_setting = SiteSetting::first();
            if($site_setting == null){
                $site_setting = New SiteSetting;
            }
            $site_setting->company_name=$request->company_name;
            $site_setting->right_text=$request->right_text;
            $site_setting->redirect_url=$request->redirect_url;
            $site_setting->email1=$request->email;
            $site_setting->phone1=$request->phone;
            $site_setting->company_establish_year=$request->company_establish_year;
            $site_setting->software_service_slogan=$request->software_service_slogan;

            // $site_setting->facebook=$request->facebook;
            // $site_setting->twitter=$request->twitter;
            // $site_setting->instagram=$request->instagram;
            // $site_setting->youtube=$request->youtube;
            // $site_setting->linkedin=$request->linkedin;
            // $site_setting->google=$request->google;

            if($request->hasFile('header_logo')){
                @unlink(public_path('upload/site_setting/'.$site_setting->header_image));
                $fileName = time().'_header-logo23.'.$request->header_logo->getClientOriginalExtension();
            //  dd( $fileName);
                $request->header_logo->move(public_path('upload/site_setting'), $fileName);

                $site_setting->header_image =$fileName;
            }
            if($request->hasFile('favicon')){
                @unlink(public_path('upload/site_setting/'.$site_setting->favicon));
                $fileName = time().'_favicon.'.$request->favicon->getClientOriginalExtension();
                $request->favicon->move(public_path('upload/site_setting'), $fileName);

                $site_setting->favicon =$fileName;
            }
            $site_setting->save();

            $notification=array(
                'message'=>"Updated Successfully",
                'alert-type'=>'success'
            );
            return  back()->with($notification);
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'success'
            );
            return  back()->with($notification);
        }

    }
    public function getUserLimit(){
        $results = Tp_option::where('option_name', 'user_limit')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);
			$data['days'] = $dataObj->days;
		}else{
			$data['days'] = '';
		}
		$datalist = $data;
        return view('admin.theme_option.free_user_limit', compact('datalist'));
    }
    public function userLimitSave(Request $request){
       
        try{
            $gData = Tp_option::where('option_name', 'user_limit')->first();

            $option = array();
            $option['days'] = $request->days;
            $data = array(
                'option_name' => 'user_limit',
                'option_value' => json_encode($option)
            );

            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                return back()->with('success',__('Data Updated Successfully'));
            }else{
                $response = Tp_option::create($data);
                return back()->with('success',__('New Data Added Successfully'));
            }
        }catch(\Exception $e){
            return back()->with('success',__('Data update failed'));
        }
    }
    public function getSocialMediaPageLoad() {
        // $results = Tp_option::where('option_name', 'theme_social_media')->first();
        // $data = array();
		// if($results){
		// 	$dataObj = json_decode($results->option_value);

		// 	$data['facebook'] = $dataObj->facebook;
		// 	$data['twitter'] = $dataObj->twitter;
		// 	$data['instagram'] =  $dataObj->instagram;
		// 	$data['youtube'] =  $dataObj->youtube;
		// 	$data['linkedin'] =  $dataObj->linkedin;
		// 	$data['google'] =  $dataObj->google;
		// }else{
		// 	$data['facebook'] = '';
		// 	$data['twitter'] = '';
		// 	$data['instagram'] = '';
		// 	$data['youtube'] = '';
		// 	$data['linkedin'] = '';
		// 	$data['google'] = '';
		// }

		// $datalist = $data;
        $site_setting = SiteSetting::first();
        if($site_setting == null){
            $site_setting = New SiteSetting;
        }

        return view('admin.theme_option.social-media', compact('site_setting'));
    }
    public function saveSocialMediaData(Request $request){

        
        try{
            $gData = true;
            $site_setting = SiteSetting::first();
            if($site_setting == null){
                $gData = false;
                $site_setting = New SiteSetting;
            }
            $site_setting->facebook=$request->facebook;
            $site_setting->twitter=$request->twitter;
            $site_setting->instagram=$request->instagram;
            $site_setting->youtube=$request->youtube;
            $site_setting->linkedin=$request->linkedin;
            $site_setting->google=$request->google;
            $site_setting->save();
           

            // $gData = Tp_option::where('option_name', 'theme_social_media')->first();

            // $option = array();
            // $option['facebook'] = $request->facebook;
			// $option['twitter'] = $request->twitter;
			// $option['instagram'] = $request->instagram;
			// $option['youtube'] = $request->youtube;
			// $option['linkedin'] = $request->linkedin;
			// $option['google'] = $request->google;


            // $data = array(
            //     'option_name' => 'theme_social_media',
            //     'option_value' => json_encode($option)
            // );

            if($gData){
                // $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                // $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
                //return back()->with('success',__('New Data Added Successfully'));
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'success'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsColorPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'theme_color')->first();
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
        return view('admin.theme_option.theme-options-color', compact('datalist'));
    }
    public function saveThemeOptionsColor (Request $request){
        //dd($request);
        $og_title = '';
		$og_description = '';
		$og_keywords = '';
		$og_image = '';
        try{
            $gData = Tp_option::where('option_name', 'theme_color')->first();

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
                'option_name' => 'theme_color',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'success'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsSEOPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'theme_option_seo')->first();
        $data = array();
		if($results){


			$dataObj = json_decode($results->option_value);

			$data['og_title'] = $dataObj->og_title;
			$data['og_image'] = asset('public/upload/theme_option').'/'.$dataObj->og_image;
			$data['og_description'] = $dataObj->og_description;
			$data['og_keywords'] = $dataObj->og_keywords;
		}else{
			$data['og_title'] = '';
			$data['og_image'] = asset("public/images/No-image.jpg");
			$data['og_description'] = '';
			$data['og_keywords'] = '';
		}

		$datalist = $data;
        return view('admin.theme_option.theme-options-seo', compact('datalist'));
    }
    public function saveThemeOptionsSEO(Request $request){
        //dd($request);
        $og_title = '';
		$og_description = '';
		$og_keywords = '';
		$og_image = '';
        try{
            $this->validate($request,[
                'og_image'=>'image|mimes:jpeg,png,jpg,webp',
            ]);
            $gData = Tp_option::where('option_name', 'theme_option_seo')->first();
            if($gData){
                $dataObj = json_decode($gData->option_value);
                $og_title =$dataObj->og_title;
                $og_description = $dataObj->og_description;
                $og_keywords =$dataObj->og_keywords;
                $og_image = $dataObj->og_image;
            }
            $option = array();
            $option['og_title'] = $request->og_title;
			$option['og_description'] = $request->og_description;
			$option['og_keywords'] = $request->og_keywords;
            if($request->file('og_image')){
                $file=$request->file('og_image');
                @unlink(public_path('upload/theme_option/'.$gData->og_image));
                $filename=date('YmdHi')."_og_image".$file->getClientOriginalName();
                $file->move(public_path('upload/theme_option'),$filename);
                $og_image =$filename;
            }
			$option['og_image'] = $og_image;


            $data = array(
                'option_name' => 'theme_option_seo',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsFacebookPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'facebook')->first();
        $data = array();
		if($results){


			$dataObj = json_decode($results->option_value);

			$data['fb_app_id'] = $dataObj->fb_app_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['fb_app_id'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.theme-options-facebook', compact('datalist'));
    }
    public function saveThemeOptionsFacebook(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'facebook')->first();

            $option = array();
            $option['fb_app_id'] = $request->fb_app_id;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'facebook',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsFacebookPixelLoad(Request $request){
        $results = Tp_option::where('option_name', 'facebook-pixel')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);

			$data['fb_pixel_id'] = $dataObj->fb_pixel_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['fb_pixel_id'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.theme-options-facebook-pixel', compact('datalist'));
    }
    public function saveThemeOptionsFacebookPixel(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'facebook-pixel')->first();

            $option = array();
            $option['fb_pixel_id'] = $request->fb_pixel_id;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'facebook-pixel',
                'option_value' => json_encode($option)
            );
           //  dd($data);
           if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsTwitterPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'twitter')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);

			$data['twitter_id'] = $dataObj->twitter_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['twitter_id'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.theme-options-twitter', compact('datalist'));
    }
    public function saveThemeOptionsTwitter(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'twitter')->first();

            $option = array();
            $option['twitter_id'] = $request->twitter_id;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'twitter',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getThemeOptionsWhatsappPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'whatsapp')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);

			$data['whatsapp_id'] = $dataObj->whatsapp_id;
			$data['whatsapp_text'] = $dataObj->whatsapp_text;
			$data['position'] = $dataObj->position;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['whatsapp_id'] = '';
			$data['whatsapp_text'] = '';
			$data['position'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.theme-options-whatsapp', compact('datalist'));
    }
    public function saveThemeOptionsWhatsapp(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'whatsapp')->first();

            $option = array();
            $data['whatsapp_id'] = $request->whatsapp_id;
			$data['whatsapp_text'] = $request->whatsapp_text;
			$data['position'] = $request->position;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'whatsapp',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getGoogleAnalytics(Request $request){
        $results = Tp_option::where('option_name', 'google_analytics')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);

			$data['tracking_id'] = $dataObj->tracking_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['tracking_id'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.google-analytics', compact('datalist'));
    }
    public function saveGoogleAnalytics(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'google_analytics')->first();

            $option = array();
            $option['tracking_id'] = $request->tracking_id;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'google_analytics',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getGoogleTagManager(Request $request){
        $results = Tp_option::where('option_name', 'google_tag_manager')->first();
        $data = array();
		if($results){
			$dataObj = json_decode($results->option_value);

			$data['google_tag_manager_id'] = $dataObj->google_tag_manager_id;
			$data['is_publish'] = $dataObj->is_publish;
		}else{
			$data['google_tag_manager_id'] = '';
			$data['is_publish'] = '2';
		}

		$datalist = $data;

        return view('admin.theme_option.google-tag-manager', compact('datalist'));
    }
    public function saveGoogleTagManager(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'google_tag_manager')->first();

            $option = array();
            $option['google_tag_manager_id'] = $request->google_tag_manager_id;
			$option['is_publish'] = $request->is_publish;



            $data = array(
                'option_name' => 'google_tag_manager',
                'option_value' => json_encode($option)
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getCustomCSSPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'custom_css')->first();
        $data = array();
		if($results){
			// $dataObj = json_decode($results->option_value);

			$data['custom_css'] = $results->option_value;
		}else{
			$data['custom_css'] = '';
		}

		$datalist = $data;

        return view('admin.theme_option.custom-css', compact('datalist'));
    }
    public function saveCustomCSS(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'custom_css')->first();

            $option = array();
            $option['custom_css'] = $request->custom_css;



            $data = array(
                'option_name' => 'custom_css',
                'option_value' =>  $request->custom_css
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
    function getCustomJSPageLoad(Request $request){
        $results = Tp_option::where('option_name', 'custom_js')->first();
        $data = array();
		if($results){
			//$dataObj = json_decode($results->option_value);

			$data['custom_js'] = $results->option_value;
		}else{
			$data['custom_js'] = '';
		}

		$datalist = $data;

        return view('admin.theme_option.custom-js', compact('datalist'));
    }
    public function saveCustomJS(Request $request){
        //dd($request);

        try{
            $gData = Tp_option::where('option_name', 'custom_js')->first();

            $option = array();
            $option['custom_js'] = $request->custom_js;



            $data = array(
                'option_name' => 'custom_js',
                'option_value' => $request->custom_js
            );
           //  dd($data);
            if($gData){
                $response = Tp_option::where('id', $gData->id)->update($data);
                $notification=array(
                    'message'=>"Updated Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }else{
                $response = Tp_option::create($data);
                $notification=array(
                    'message'=>"Added Successfully",
                    'alert-type'=>'success'
                );
                return  back()->with($notification);
            }
        }catch(\Exception $e){
            $notification=array(
                'message'=>"Something Went Wrong",
                'alert-type'=>'error'
            );
            return  back()->with($notification);
        }
    }
}
