<?php

namespace App\Models\Hr\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Department extends Model
{
    use HasFactory, Multitenantable, WithBusiness;
    protected $table = "departments";
    protected $fillable = ['name', 'code', 'head', 'status'];
    public function getIconShowAttribute(){
        return $this->icon != "" ? asset('public/upload/department/'. $this?->icon) : asset('public/frontend/images/No-image.jpg');
    }

}
