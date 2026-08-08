<?php

namespace App\Imports;

use App\Models\Inventory\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ValidateProductImport extends ProductImport
{
    public function model(array $row): Product|null
    {


        $name = $this->request->input('name');
        $slug = $this->request->input('slug');


        return $this->storeProduction();




    }

    public function storeProduction()
    {

        $product = collect($this->request->all());

        $collect = collect([
            'name' => $product['name'],
            'model' => $product,
        ]);
        // dd( $collect);
        $this->onSuccess($collect);

        return null;
    }

}
