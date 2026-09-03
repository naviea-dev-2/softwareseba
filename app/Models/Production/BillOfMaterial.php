<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'production_bill_of_materials';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function items() {
        return $this->hasMany(BillOfMaterialItem::class, 'bom_id');
    }
}
