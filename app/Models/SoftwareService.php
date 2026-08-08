<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareService extends Model
{
    use HasFactory;
    function getImageShowAttribute(){
        return $this->image != "" ? asset("public/upload/soft_service/".$this->image) : $this->no_image;
    }
    function getNoImageAttribute(){
        return asset("public/assets/images/no_service.webp");
    }
}
