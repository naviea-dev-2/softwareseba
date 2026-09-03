<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionHistory extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $guarded = ['id'];
}
