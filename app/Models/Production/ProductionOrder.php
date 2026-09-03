<?php

namespace App\Models\Production;

use App\Models\Inventory\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $guarded = ['id'];


    public function product(){
        return $this->belongsTo(Product::class);
    }
}
