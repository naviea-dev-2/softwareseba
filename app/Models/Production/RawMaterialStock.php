<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialStock extends Model
{
    use HasFactory;

    protected $table = 'production_raw_material_stocks';

    protected $fillable = [];

    protected $guarded = ['id'];
    
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

}
