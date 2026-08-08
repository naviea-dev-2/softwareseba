<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Payroll extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $table = "payrolls";
}
