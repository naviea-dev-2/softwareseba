<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceImport extends Model
{
    protected $fillable = [
        'status',
        'total_rows',
        'error_rows',
        'error_message',
        'file_path',
    ];

    public function rows()
    {
        return $this->hasMany(
            AttendanceImportRow::class,
            'attendance_import_id'
        );
    }
}