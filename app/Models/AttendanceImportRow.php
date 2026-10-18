<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceImportRow extends Model
{
    protected $fillable = [
        'attendance_import_id',
        'row_number',
        'raw_data',
        'errors',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'errors' => 'array',
    ];

    public function import()
    {
        return $this->belongsTo(
            AttendanceImport::class,
            'attendance_import_id'
        );
    }
}