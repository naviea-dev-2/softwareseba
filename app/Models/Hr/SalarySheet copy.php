<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\WithBusiness;
class SalarySheet extends Model
{
    use WithBusiness;
    function detail_list(){
        
        return $this->hasMany(SalaryDetails::class,'salary_id','id');
    
    }
}
