<?php
namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    protected $fillable = ['name', 'tax_type', 'rate', 'threshold', 'effective_from', 'status'];

    protected $casts = ['effective_from' => 'date'];

    public function slabs()
    {
        return $this->hasMany(TaxSlab::class);
    }
}
