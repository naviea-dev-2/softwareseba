<?php
namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Model;

class TaxSlab extends Model
{
    protected $fillable = ['tax_setting_id', 'from_amount', 'to_amount', 'rate'];

    public function taxSetting()
    {
        return $this->belongsTo(TaxSetting::class);
    }
}
