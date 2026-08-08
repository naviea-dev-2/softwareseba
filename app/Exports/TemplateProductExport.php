<?php

namespace App\Exports;

use App\Models\Inventory\AttributeSet;
use App\Models\Inventory\Brand;
use App\Models\Inventory\Category;
use App\Models\Inventory\Generic;
use App\Models\Inventory\Manufature;
use App\Models\Inventory\ProductType;
use App\Models\Inventory\Tax;
use App\Models\Inventory\Unit;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class TemplateProductExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithStrictNullComparison,
    WithColumnWidths,
    ShouldAutoSize
{
    use Exportable;

    protected Collection $results;

    protected string $exportType;

    protected int $totalRow;
    protected Collection $manufactures;
    protected Collection $brands;
    protected Collection $categories;
    protected Collection $unitlist;
    protected Collection $taxes;
    protected int $business_type_id = 0;
    protected Collection $attributeSets;
    protected Collection $p_types;
    protected Collection $generics;
    protected bool $enabledDigital;

    protected bool $isMarketplaceActive;

    public function __construct(string $exportType = Excel::XLSX,$business_type_id)
    {
        $this->exportType = $exportType;
        $this->business_type_id  = $business_type_id;
        $productNames = collect([
            'Bread - Sour Sticks With Onion',
            'Cheese - Cheddar Mild',
            'Creme De Banane - Marie',
        ]);
        $productCodes = collect([
            'Pro-145',
            'd-000001',
            '410001',
        ]);
        $manufactures = Manufature::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
        $this->manufactures = collect($manufactures);
        $categories = Category::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
        $this->categories = collect($categories);
        $brands = Brand::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
        $this->brands = collect($brands);
        $productName = $productNames->random();
        $productCode = $productCodes->random();
        $taxes = Tax::query()->pluck('name', 'id')->all();
        $this->taxes = collect($taxes);
        $unitlist = Unit::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
        $this->unitlist = collect($unitlist);
        if($this->business_type_id == 5){
            $p_types = ProductType::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
            $this->p_types = collect($p_types);
            $generics = Generic::where('business_type_id',$business_type_id)->pluck('name', 'id')->all();
            $this->generics = collect($generics);
        }

      
        $attribute_sets = AttributeSet::where('business_type_id',$business_type_id)->get();
        $this->attributeSets = collect($attribute_sets);

        //$business_name = auth()->user()->business->name;

        // $images=Media_option::query()->inRandomOrder()->limit(2)->get();
        // $images =$images->pluck('title', 'id')->all();
        // $images = collect($images);
       // dd($images);
        //$taxes = Tax::query()->inRandomOrder()->limit(2)->get();
        $price = rand(20, 100);
        if($this->business_type_id == 5){
            $product = array_replace($this->getTempProductData(), [
                'manufacturer' =>  $this->manufactures->count() ? $this->manufactures->random() : null,
                'category' =>  $this->categories->count() ? $this->categories->random() : null,
                'brand' => $this->brands->count() ? $this->brands->random() : null,
                'name' => $productName,
                'code' => $productCode,
                'batch_no' => 'T-01474477',
                'manufacture_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'exipre_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'p_type' => $this->p_types->count() ? $this->p_types->random() : null,
                'generic' => $this->generics->count() ? $this->generics->random() : null,
                'taxes' =>  $this->taxes->count() ? $this->taxes->random() : null,
                // 'taxes' => implode(',', $taxes->pluck('title')->all()),
                'unit' => $this->unitlist->count() ? $this->unitlist->random() : null,
                'discount_type' =>  Arr::random(['percent', 'fixed']),
                'discount' =>  Arr::random([10, 20]),
                'cost_price' => $price,
                'sale_price' => $price,
                // 'whole_sale_price' => $price,
                'product_image' => 'products/1.jpg',
            ]);
        }else if($this->business_type_id == 6){
            $product = array_replace($this->getTempProductData(), [
                'manufacturer' =>  $this->manufactures->count() ? $this->manufactures->random() : null,
                'category' =>  $this->categories->count() ? $this->categories->random() : null,
                'brand' => $this->brands->count() ? $this->brands->random() : null,
                'name' => $productName,
                'code' => $productCode,
                'batch_no' => 'T-01474477',
                'manufacture_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'exipre_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'imei_1' => 'T-01474477',
                'imei_2' => 'T-01474477',
                'taxes' =>  $this->taxes->count() ? $this->taxes->random() : null,
                // 'taxes' => implode(',', $taxes->pluck('title')->all()),
                'unit' => $this->unitlist->count() ? $this->unitlist->random() : null,
                'discount_type' =>  Arr::random(['percent', 'fixed']),
                'discount' =>  Arr::random([10, 20]),
                'cost_price' => $price,
                'sale_price' => $price,
                // 'whole_sale_price' => $price,
                'product_image' => 'products/1.jpg',
            ]);
        }else{
            $product = array_replace($this->getTempProductData(), [
                'manufacturer' =>  $this->manufactures->count() ? $this->manufactures->random() : null,
                'category' =>  $this->categories->count() ? $this->categories->random() : null,
                'brand' => $this->brands->count() ? $this->brands->random() : null,
                'name' => $productName,
                'code' => $productCode,
                'batch_no' => 'T-01474477',
                'manufacture_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'exipre_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'taxes' =>  $this->taxes->count() ? $this->taxes->random() : null,
                // 'taxes' => implode(',', $taxes->pluck('title')->all()),
                'unit' => $this->unitlist->count() ? $this->unitlist->random() : null,
                'discount_type' =>  Arr::random(['percent', 'fixed']),
                'discount' =>  Arr::random([10, 20]),
                'cost_price' => $price,
                'sale_price' => $price,
                // 'whole_sale_price' => $price,
                'product_image' => 'products/1.jpg',
            ]);
        }


        $this->results = collect([
            $product,
            // $productVariation1,
            // $productVariation2,
        ]);
       // dd($this->results);
        $this->totalRow = $exportType == Excel::XLSX ? 100 : ($this->results->count() + 1);
    }

    public function collection(): Collection
    {
      // dd($this->results);
        return $this->results;
    }

    public function headings(): array
    {
        $headings = [
            'name' => 'Product name',
            'code' => 'Product Code',
            'batch_no' => 'Batch No.',
            'category' => 'Category',
            'brand' => 'Brand',
            'manufacturer' => 'Manufacturer',
            'manufacture_date' => 'Manufacturer Date',
            'exipre_date' => 'Expire Date',

        ];
        if($this->business_type_id == 5){
            $headings['product_type'] = 'Product Type';
            $headings['generic'] = 'Generics';
        }
        if($this->business_type_id == 6){
            $headings['imei_1'] = 'IMEI 1';
            $headings['imei_2'] = 'IMEI 2';
        }
        $headings['cost_price'] = 'Purchase Price';
        $headings['sale_price'] = 'Sale price';
        $headings['discount'] = 'Discount';
        $headings['discount_type'] = 'Discount Type';
        $headings['discount'] = 'Discount';
        $headings['taxes'] = 'Taxes';
        $headings['unit'] = 'Unit';
        $headings['product_image'] = 'Product Image';
         

        foreach($this->attributeSets as $attributeSet) {
            //dd($attributeSet);
            $headings[str_slug_c($attributeSet->title)] = $attributeSet->title;
        }


        return $headings;
    }

    public function getTempProductData(): array
    {
        return array_fill_keys(array_keys($this->headings()), '');
    }

    public function stringFromColumnIndex(string $column): string
    {
        
        $key = array_search($column, array_keys($this->headings()));
        if($column != 'name'){
         //   dd($this->headings());
        }
        
        if ($key !== false) {
            return Coordinate::stringFromColumnIndex($key + 1);
        }

        return '';
    }

    public function registerEvents(): array
    {
       // dd("ssttt");
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                // $statusColumn = $this->stringFromColumnIndex('status');
                // dd($statusColumn);
                // $stockColumn = $this->stringFromColumnIndex('stock_status');
                // $autoGenerateSKUColumn = $this->stringFromColumnIndex('auto_generate_sku');
                // $isFeaturedColumn = $this->stringFromColumnIndex('is_featured');
                // $brandColumn = $this->stringFromColumnIndex('brand');

                // $importTypeColumn = $this->stringFromColumnIndex('import_type');
                // $isVariationDefaultColumn = $this->stringFromColumnIndex('is_variation_default');
                // $withStorehouseManagementColumn = $this->stringFromColumnIndex('with_storehouse_management');
                // $allowCheckoutWhenOutOfStockColumn = $this->stringFromColumnIndex('allow_checkout_when_out_of_stock');
                // $quantityColumn = $this->stringFromColumnIndex('quantity');
                // $priceColumn = $this->stringFromColumnIndex('price');
                // $saleColumn = $this->stringFromColumnIndex('sale_price');
                // $weightColumn = $this->stringFromColumnIndex('weight');
                // $lengthColumn = $this->stringFromColumnIndex('length');
                // $wideColumn = $this->stringFromColumnIndex('wide');
                // $heightColumn = $this->stringFromColumnIndex('height');
                // $costPerItemColumn = $this->stringFromColumnIndex('cost_per_item');
                // $productTypeColumn = $this->stringFromColumnIndex('product_type');

                // // set dropdown list for first data row
                // // $statusValidation = $this->getStatusValidation();
                // // $stockValidation = $this->getStockValidation();
                // $booleanValidation = $this->getBooleanValidation();
                // $importTypeValidation = $this->getImportTypeValidation();
                // $wholeNumberValidation = $this->getWholeNumberValidation();
                // $decimalValidation = $this->getDecimalValidation();
                // $brandValidation = $this->getBrandValidation();

                // $productTypeValidation = $this->getProductTypeValidation();

                // clone validation to remaining rows
                // for ($index = 2; $index <= $this->totalRow; $index++) {
                //     // $event->sheet->getCell($statusColumn . $index)->setDataValidation($statusValidation);
                //     // $event->sheet->getCell($stockColumn . $index)->setDataValidation($stockValidation);
                //     $event->sheet->getCell($autoGenerateSKUColumn . $index)->setDataValidation($booleanValidation);
                //     $event->sheet->getCell($isFeaturedColumn . $index)->setDataValidation($booleanValidation);
                //     $event->sheet->getCell($brandColumn . $index)->setDataValidation($brandValidation);

                //     $event->sheet->getCell($importTypeColumn . $index)->setDataValidation($importTypeValidation);
                //     $event->sheet->getCell($isVariationDefaultColumn . $index)->setDataValidation($booleanValidation);
                //     $event->sheet->getCell($withStorehouseManagementColumn . $index)
                //         ->setDataValidation($booleanValidation);
                //     $event->sheet->getCell($allowCheckoutWhenOutOfStockColumn . $index)
                //         ->setDataValidation($booleanValidation);

                //     $event->sheet->getCell($quantityColumn . $index)->setDataValidation($wholeNumberValidation);

                //     $event->sheet->getCell($weightColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($lengthColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($wideColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($heightColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($saleColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($priceColumn . $index)->setDataValidation($decimalValidation);
                //     $event->sheet->getCell($costPerItemColumn . $index)->setDataValidation($decimalValidation);

                //     // if ($this->enabledDigital) {
                //     //     $event->sheet->getCell($productTypeColumn . $index)->setDataValidation($productTypeValidation);
                //     // }
                // }
              //  dd($this->columnFormats());
                $delegate = $event->sheet->getDelegate();
                foreach ($this->columnFormats() as $column => $format) {
                    $delegate
                        ->getStyle($column)
                        ->getNumberFormat()
                        ->setFormatCode($format);
                }

                $delegate->getStyle('A1'); // Reset selected
            },
        ];
    }

    // protected function getStatusValidation(): DataValidation
    // {
    //     return $this->getDropDownListValidation(BaseStatusEnum::values());
    // }

    // protected function getProductTypeValidation(): DataValidation
    // {
    //     return $this->getDropDownListValidation(ProductTypeEnum::values());
    // }

    protected function getDropDownListValidation(array $options): DataValidation
    {
        // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.value_not_in_list'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.pick_from_list'));
        $validation->setPrompt(trans('plugins/ecommerce::bulk-import.export.template.prompt_list'));
        $validation->setFormula1(sprintf('"%s"', implode(',', $options)));

        return $validation;
    }



    protected function getBooleanValidation(): DataValidation
    {
        return $this->getDropDownListValidation(['No', 'Yes']);
    }

    protected function getImportTypeValidation(): DataValidation
    {
        return $this->getDropDownListValidation(['product', 'variation']);
    }

    protected function getWholeNumberValidation(int $min = 0): DataValidation
    {
        // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.number_not_allowed'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.allowed_input'));
        $validation->setPrompt(trans(
            'plugins/ecommerce::bulk-import.export.template.prompt_whole_number',
            compact('min')
        ));
        $validation->setFormula1((string)$min);
        $validation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);

        return $validation;
    }

    protected function getDecimalValidation(int $min = 0): DataValidation
    {
       // dd("sss");
       // // set dropdown list for first data row
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle(trans('plugins/ecommerce::bulk-import.export.template.input_error'));
        $validation->setError(trans('plugins/ecommerce::bulk-import.export.template.number_not_allowed'));
        $validation->setPromptTitle(trans('plugins/ecommerce::bulk-import.export.template.allowed_input'));
        $validation->setPrompt(trans('plugins/ecommerce::bulk-import.export.template.prompt_decimal', compact('min')));
        $validation->setFormula1((string)$min);
        $validation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);

        return $validation;
    }

    protected function getBrandValidation(): DataValidation
    {
        return $this->getDropDownListValidation(['-- None --'] + $this->brands->toArray());
    }

    public function columnFormats(): array
    {
       // dd("ss");
        if ($this->exportType != Excel::XLSX) {
            return [];
        }

        $columns = [
            'name' => NumberFormat::FORMAT_TEXT,

            'sku' => NumberFormat::FORMAT_TEXT,
            'category' => NumberFormat::FORMAT_TEXT,


            'manufacturer' => NumberFormat::FORMAT_TEXT,
            'brand' => NumberFormat::FORMAT_TEXT,
            'tax' => NumberFormat::FORMAT_TEXT,
            'cost_price' => NumberFormat::FORMAT_NUMBER_00,
            'sale_price' => NumberFormat::FORMAT_NUMBER_00,



            // 'stock_qty' =>  NumberFormat::FORMAT_NUMBER,

            // 'actual_weight' => NumberFormat::FORMAT_GENERAL,
            // 'length' => NumberFormat::FORMAT_GENERAL,
            // 'width' =>NumberFormat::FORMAT_GENERAL,
            // 'height' => NumberFormat::FORMAT_GENERAL,

        ];
        //dd("ss");
        $formatted = [];
        foreach ($columns as $key => $value) {
            $column = $this->stringFromColumnIndex($key);
            $formatted[$column . '2:' . $column . $this->totalRow] = $value;
        }

        return $formatted;
    }

    public function columnWidths(): array
    {
        return [
            $this->stringFromColumnIndex('name') => 25,
            // $this->stringFromColumnIndex('description') => 30,
        ];
    }

    public function rules(): array
    {
      //  dd("ss");
        $rules = [
            'name' => 'required|string|max:220',
            'code' => 'nullable|string',
            'batch_no' => 'nullable|string',
            'category' => 'nullable|[Category name | Category ID]',
            'brand' => 'nullable|[Brand name | Brand ID]',
            'manufacturer' => 'nullable|[Manufacurer name | Manufacurer ID]',
            'manufacture_date' => 'YYYY-MM-DD|ex. 2000-12-25',
            'exipre_date' => 'YYYY-MM-DD|ex. 2000-12-25',
        ];
        if($this->business_type_id == 5){
            $rules['product_type'] = 'nullable|[Product Type name | Product Typ ID]';
            $rules['generic'] ='nullable|[Generic name | Generic ID]';
        }
        if($this->business_type_id == 6){
            $rules['imei_1'] = 'nullable|string';
            $rules['imei_2'] = 'nullable|string';
        }
        $rules['cost_price'] =  'nullable|number';
        $rules['sale_price'] = 'nullable|number';
        $rules['discount_type'] = 'nullable|percent,fixed|default:percent';
        $rules['discount'] = 'nullable|number|default:0';
        $rules['taxes'] = 'nullable|Tax title | Tax ID';
        $rules['unit'] = 'nullable|Unit title | Unit ID';
        $rules['product_image'] = 'Product Image';
       
        foreach($this->attributeSets as $attributeSet) {
            $rules[str_slug_c($attributeSet->title)] = 'nullable|string';
        }

        return $rules;
    }
}
