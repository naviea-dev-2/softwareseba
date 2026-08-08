<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    function getHeaderImageShowAttribute(){
        return asset("public/upload/site_setting/".$this->header_image);
    }
    function getFooterImageShowAttribute(){
        return asset("public/upload/site_setting/".$this->footer_image);
    }
    function getPaymentImageShowAttribute(){
        return $this->payment_image != "" ? asset("public/upload/site_setting/".$this->payment_image) : $this->no_image;
    }
    function getFaviconShowAttribute(){
        return asset("public/upload/site_setting/".$this->favicon);
    }
    function getNoImageAttribute(){
        return asset("public/frontend/images/No-image.jpg");
    }
}
