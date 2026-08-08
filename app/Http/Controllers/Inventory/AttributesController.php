<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Inventory\Attribute;
use App\Models\Inventory\AttributeSet;
use App\Models\Inventory\Media_option;

class AttributesController extends Controller
{
    public function getNewAttributeData(Request $request){
        $is_edit= $request->is_edit;
        $row_no = $request->row_no;
        return view("Inventory.attributes.new_attribute",compact("is_edit","row_no"));
    }
    //Attributes page load
    public function getAttributesPageLoad() {
        if(can_p('attributes.index') == false){
            return redirect()->route('dashboard');
        }
		$attribute_sets = AttributeSet::orderBy('title', 'ASC')->paginate(10);
        // $media_datalist = Media_option::orderBy('id','desc')->paginate(28);
        return view('Inventory.attributes.manage', compact('attribute_sets'));
    }

	//Get data for Attributes Pagination
	public function getAttributesTableData(Request $request){

		$search = $request->search;

		if($request->ajax()){

			if($search != ''){
				$datalist = AttributeSet::where(function ($query) use ($search){
                    $query->where('title', 'like', '%'.$search.'%');
                })
                ->orderBy('order', 'ASC')->paginate(10);
			}else{
				$datalist = AttributeSet::orderBy('order', 'ASC')->paginate(10);
			}

			return view('backend.partials.attributes_table', compact('datalist'))->render();
		}
	}

	//Save data for Attributes
    public function saveAttributesData(Request $request){
        if(can_p('attributes.add') == false){
            return redirect()->route('dashboard');
        }
       // return $request->all();
		$res = array();

		$id = $request->input('id');
		$name = $request->input('name');

		$validator_array = array(
			'name' => $request->input('name')
		);

		$validator = Validator::make($validator_array, [
			'name' => 'required|max:100'
		]);

		$errors = $validator->errors();

		if($errors->has('name')){
			$res['msgType'] = 'error';
			$res['msg'] = $errors->first('name');
			return response()->json($res);
		}

		$data = array(
			'title' => $name,
            // 'business_type_id'=>auth()->user()->business->business_type_id,
            'business_id'=>auth()->user()->business->id,
            'slug' => esc(str_slug($name)),
            'order'=> $request->input('order') ?? 0,
		);

		if($id ==''){
			$response = AttributeSet::create($data);
            if($request->title){
                foreach($request->title as $key => $value){
                    $attribute = new Attribute;
                    $attribute->attribute_set_id = $response->id;
                    $attribute->title = $value;
                    $attribute->slug = esc(str_slug($value));
                    $attribute->is_default = isset($request->is_default[$key]) ? 1 : 0;
                    // $attribute->color = $request->color[$key];
                    // $attribute->image = $request->image[$key];
                    $attribute->save();
                }
            }
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('New Data Added Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data insert failed');
			}
		}else{
			$response = AttributeSet::where('id', $id)->update($data);
            if($request->title){
                foreach($request->title as $key => $value){
                    $attribute = new Attribute;
                    $attribute->attribute_set_id = $id;
                    $attribute->title = $value;
                    $attribute->slug = esc(str_slug($value));

                    $attribute->is_default = $request->is_default == $key ? 1 : 0;
                    // $attribute->color = $request->color[$key];
                    // $attribute->image = $request->image[$key];
                    $attribute->save();
                }
            }
            if($request->del_attribute){
                foreach($request->del_attribute as $key => $value){
                    $attribute = Attribute::find($value);
                    $attribute->delete();
                }
            }
            if($request->old_title){
                 foreach($request->old_title as $key => $value){
                    $attribute =  Attribute::find($key);
                    $attribute->attribute_set_id = $id;
                    $attribute->title = $value;
                    $attribute->slug = esc(str_slug($value));
                    $attribute->is_default = $request->is_default == $key ? 1 : 0;
                    // $attribute->color = $request->old_color[$key];
                    // $attribute->image = $request->old_image[$key];
                    $attribute->save();
                }
            }
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Updated Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data update failed');
			}
		}

		return response()->json($res);
    }

	//Get data for Attribute by id
    public function getAttributesById(Request $request){
        if(can_p('attributes.edit') == false){
            return redirect()->route('dashboard');
        }
		$id = $request->id;

		$attribute_set = AttributeSet::where('id', $id)->first();
        $is_edit= 1;
        $data_view =  view("Inventory.attributes.new_attribute",compact("is_edit",'attribute_set'))->render();
		return response()->json(['attribute_set'=>$attribute_set,'attributes'=>$data_view]);
	}

	//Delete data for Attributes
	public function deleteAttributes(Request $request){
        if(can_p('attributes.delete') == false){
            return redirect()->route('dashboard');
        }
		$res = array();

		$id = $request->id;

		if($id != ''){
            try{
                DB::beginTransaction();
                $attribute_set = AttributeSet::find($id);
              //  $attribute_set->attributes->each()->delete();
                $attribute_set->delete();
                DB::commit();
                $notification=array(
                    'message'=>"Data Removed Successfully!",
                    'alert-type'=>'success'
                );
                return redirect()->route('attributes.index')->with($notification);
            }catch(\Exception $e){
                DB::rollBack();
                dd($e->getMessage());
                $notification=array(
                    'message'=>__('Data remove failed'),
                    'alert-type'=>'error'
                );
                return redirect()->route('attributes.index')->with($notification);
            }

		}
        $notification=array(
            'message'=>"Id is not found!",
            'alert-type'=>'error'
        );

        return redirect()->route('attributes.index')->with($notification);
	}

	//Bulk Action for Attributes
	public function bulkActionAttributes(Request $request){

		$res = array();

		$idsStr = $request->ids;
		$idsArray = explode(',', $idsStr);

		$BulkAction = $request->BulkAction;

		if($BulkAction == 'delete'){
			$response = Attribute::whereIn('id', $idsArray)->delete();
			if($response){
				$res['msgType'] = 'success';
				$res['msg'] = __('Data Removed Successfully');
			}else{
				$res['msgType'] = 'error';
				$res['msg'] = __('Data remove failed');
			}
		}

		return response()->json($res);
	}


}
