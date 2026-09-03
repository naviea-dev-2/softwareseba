<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterialItem extends Model
{
    use HasFactory;

    protected $table = 'production_bill_of_material_items';

    protected $fillable = [];

    protected $guarded = ['id'];
}
