<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Department extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $table = "departments";
    public function getIconShowAttribute(){
        return $this->icon != "" ? asset('public/upload/department/'. $this?->icon) : asset('public/frontend/images/No-image.jpg');
    }

}
