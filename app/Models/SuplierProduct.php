<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuplierProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    function user(){
        return $this->belongsTo(User::class);
    }
    function suplier(){
        return $this->belongsTo(Suplier::class);
    }
    function category(){
        return $this->belongsTo(Category::class);
    }
    function brand(){
        return $this->belongsTo(Brand::class);
    }
}
