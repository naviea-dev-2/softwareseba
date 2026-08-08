<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Payment_range extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $table = "payment_ranges";
    protected $fillable=['department_id ', 'designation_id', 'hrrank_id', 'minimum_amount', 'maximum_amount'];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

}
