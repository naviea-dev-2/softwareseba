<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineMaintenance extends Model
{
    use HasFactory;

    protected $table = 'production_machine_maintenances';

    protected $fillable = [];

    protected $guarded = ['id'];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
