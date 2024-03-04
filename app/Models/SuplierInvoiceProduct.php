<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuplierInvoiceProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function suplierInvoice()
    {
        return $this->belongsTo(SuplierInvoice::class);
    }

    public function suplierProduct()
    {
        return $this->belongsTo(SuplierProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    function brand(){
        return $this->belongsTo(Brand::class);
    }
}
