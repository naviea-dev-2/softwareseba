<?php

namespace App\Models\Production;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
    use HasFactory;

    protected $table = 'production_quality_inspections';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

     public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

}
