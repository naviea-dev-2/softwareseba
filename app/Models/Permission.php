<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    function child_menus(){
        return $this->hasMany(Permission::class,'parent_id','id');
    }
   
}
