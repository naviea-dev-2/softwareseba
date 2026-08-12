<?php

namespace App\Models;

use App\Models\Inventory\Currency;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Multitenantable;
use App\Traits\WithBusiness;
use App\Models\Inventory\Branch;

class User extends Authenticatable implements MustVerifyEmail

{
    use HasApiTokens, HasFactory, Notifiable, Multitenantable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function getIsActive(){
        return $this->status == "Active" ? true : false;
    }
    function business(){

        return $this->belongsTo(Business::class,"business_id","id");
    }
    function getCurrencySymbolAttribute(){
        if($this->business->currency){
            return $this->business->currency->symbol;
        }else{
            return "$";
        }
    }
    function getImageShowAttribute(){
        return $this->image == '' ? $this->no_image : asset("public/upload/business_user/".$this->image);
    }
    function getNoImageAttribute(){
        return asset("public/assets/images/avatars/avatar-2.png");
    }
    function role(){
        return $this->belongsTo(Role::class,"role_id","id");
    }
    function branch(){
        return $this->belongsTo(Branch::class,"branch_id","id");
    }
    function member(){
        return $this->belongsTo(Member::class,"member_id","id");
    }
}
