<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Exports\AdminTemplateProductExport;
use App\Http\Controllers\Controller;
use App\Imports\AdminProductImport;
use App\Imports\ValidateAdminProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
class BulkImportController extends Controller
{
    // protected ValidateProductImport $validateProductImport;
    // public function __construct()
    // {
    //     $this->validateProductImport =new ValidateProductImport(request(),auth()->user()->business->business_type_id);
    // }
     public function index()
    {
        $business_type = request()->business_type ?? 0;
        $template = new AdminTemplateProductExport('xlsx',$business_type);
        $data['headings'] = $template->headings();
        $data['data'] = $template->collection();
        $data['rules'] = $template->rules();
        // dd($data);
        // ValidateProductImport
        return view('admin.Inventory.product.bulk-import',$data);
    }
    public function postImport(Request $request){
       // dd($request);
        $file = $request->file('file');
        $validateProductImport= new ValidateAdminProductImport($request,$request->business_type);


         $validateProductImport->import($file);
        if ($validateProductImport->failures()->count()) {
           $data = [
                'total_failed' =>  $validateProductImport->failures()->count(),
                'total_error' =>  $validateProductImport->errors()->count(),
                'failures' =>  $validateProductImport->failures(),
            ];

            $message = __('Import failed, please check the errors below!');
            return response()->json([
                'error' => true,
                'data' =>  $data,
                'message' =>  $message,
            ]);

        }
        $productImport = new AdminProductImport($request,$request->business_type);
        $productImport->import($file);
        $data = [
            'total_success' => $productImport->successes()->count(),
            'total_failed' => $productImport->failures()->count(),
            'total_error' => $productImport->errors()->count(),
            'failures' => $productImport->failures(),
            'successes' => $productImport->successes(),
        ];

        $message = __('Imported successfully.');

        $result = __('Result: :success successes, :failed failures', [
            'success' => $data['total_success'],
            'failed' => $data['total_failed'],
        ]);
        return response()->json([
            'error' => false,
            'data' =>  $data,
            'message' =>  $message . ' ' . $result,
        ]);
    }
     public function downloadTemplate(Request $request)
    {
        
        $extension = $request->input('extension');
        $business_type = $request->input('business_type') ?? 0;
        //dd($business_type);
        $extension = $extension == 'csv' ? $extension : Excel::XLSX;
        $writeType = $extension == 'csv' ? Excel::CSV : Excel::XLSX;
        $contentType = $extension == 'csv' ? ['Content-Type' => 'text/csv'] : ['Content-Type' => 'text/xlsx'];
        $fileName = 'template_products_import.' . $extension;

        return (new AdminTemplateProductExport($extension,$business_type))->download($fileName, $writeType, $contentType);
    }
}
