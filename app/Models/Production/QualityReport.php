<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityReport extends Model
{
    use HasFactory;

    protected $table = 'production_quality_reports';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function inspection()
    {
        return $this->belongsTo(QualityInspection::class);
    }
}
