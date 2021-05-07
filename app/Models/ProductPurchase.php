<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductPurchase extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'tanggal_beli',
        'masa_garansi',
        'tanggal_selesai',
        'purchase_location_id',
    ];

    public function location(){
        return $this->belongsTo('App\Models\PurchaseLocation', 'purchase_location_id');
    }

}
