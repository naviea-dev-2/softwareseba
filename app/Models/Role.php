<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
class Role extends Model
{
    use HasFactory, Multitenantable, WithBusiness;

    function permissions(){
        return $this->hasMany(RolePermission::class,'role_id','id')->with('permission');
    }
    function menu_permissions(){
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }
}
