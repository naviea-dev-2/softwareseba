<?php

namespace App\Http\Controllers\Admin\Inventory;

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
        return view("admin.Inventory.attributes.new_attribute",compact("is_edit","row_no"));
    }
    //Attributes page load
    public function getAttributesPageLoad() {
       //dd("sdfds");
		$attribute_sets = AttributeSet::orderBy('title', 'ASC')->paginate(10);
        // $media_datalist = Media_option::orderBy('id','desc')->paginate(28);
        return view('admin.Inventory.attributes.manage', compact('attribute_sets'));
    }
    function ajaxAttribute(Request $request){
        $columns = array(
           0 => 'attribute_sets.id',
           1 => 'attribute_sets.title',
           2 => 'attribute_sets.order',
           3 => 'businesses.business_name',
       );
       $totalData = AttributeSet::count();
       $totalFiltered = $totalData;

       $limit = $request->input('length');
       $start = $request->input('start');
       $order = $columns[$request->input('order.0.column')];
       $dir = $request->input('order.0.dir');
       $search = $request->input('search.value');
       $products = AttributeSet::leftJoin('businesses','businesses.id','attribute_sets.business_id');
       if(!empty($search))
       {
           $products = $products->where("attribute_sets.title","LIKE","%{$search}%")
           ->orWhere("businesses.business_name","LIKE","%{$search}%");
       }
       $products = $products->select('attribute_sets.*','businesses.business_name')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
       $data = array();
       if(!empty($products))
       {
            $i = $start == 0 ? 1 : $start+1;
        
           foreach($products as $product)
           {
               $nestedData['id'] = $i++;
               $nestedData['title'] = $product->title;
               $nestedData['order'] = $product->order;
               $nestedData['business_name'] = $product->business?->business_name;
              
               $nestedData['options'] = '';
              
               $nestedData['options'] = '<a class="btn btn-primary data_edit" href="javascript:void(0)" data-id="'.$product->id.'"><i class="bx bx-edit"></i></a>';
              
               
                $nestedData['options'] .= '<a href="#" data-id="'.$product->id.'" class="del_data btn btn-danger"> <i class="bx bx-trash"></i></a>';
               
               $data[] = $nestedData;

           }
       }
       $json_data = array(
           "draw"            => intval($request->input('draw')),
           "recordsTotal"    => intval($totalData),
           "recordsFiltered" => intval($totalFiltered),
           "data"            => $data
       );

       return json_encode($json_data);
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
       
       // return $request->all();
		$res = array();

		$id = $request->input('id');
		$name = $request->input('name');
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:100',
			'business_id' => 'required',
		]);

        if($validator->fails()){
            return response([
                'status' => 0,
                'errors' => $validator->errors()
            ]);
        }
        try{
            DB::beginTransaction();
            $data = array(
                'title' => $request->name,
                'business_id'=>$request->business_id,
                'slug' => esc(str_slug($request->name)),
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
                DB::commit();
                return response([
                    'status' => 1,
                    'success' => 'Save successfully.',
                ]);
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
                DB::commit();
                return response([
                    'status' => 1,
                    'success' => 'Update successfully.',
                ]);
            }

		}catch(\Exception $e){
            DB::rollBack();
            return response([
                'status' => 0,
                'error' => 'Something went Wrong!',
            ]);
        }
    }

	//Get data for Attribute by id
    public function getAttributesById(Request $request){
        
		$id = $request->id;

		$attribute_set = AttributeSet::where('id', $id)->first();
        $is_edit= 1;
        $data_view =  view("admin.Inventory.attributes.new_attribute",compact("is_edit",'attribute_set'))->render();
		return response()->json(['attribute_set'=>$attribute_set,'business_id'=>$attribute_set->business_id ,'business_name'=>$attribute_set->business?->business_name,'attributes'=>$data_view]);
	}

	//Delete data for Attributes
	public function deleteAttributes(Request $request){
       
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
                return redirect()->route('admin.attributes.index')->with($notification);
            }catch(\Exception $e){
                DB::rollBack();
               // dd($e->getMessage());
                $notification=array(
                    'message'=>__('Data remove failed'),
                    'alert-type'=>'error'
                );
                return redirect()->route('admin.attributes.index')->with($notification);
            }

		}
        $notification=array(
            'message'=>"Id is not found!",
            'alert-type'=>'error'
        );

        return redirect()->route('admin.attributes.index')->with($notification);
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
