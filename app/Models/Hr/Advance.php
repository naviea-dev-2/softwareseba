<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    protected $fillable = ['employee_id', 'advance_date', 'amount', 'reason'];

    protected $casts = ['advance_date' => 'date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
