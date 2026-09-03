<?php

namespace App\Models\Production;

use App\Models\Inventory\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'production_raw_materials';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(Vendor::class, 'supplier_id');
    }
}
