<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductPurchase;

class Purchase extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'status',
    ];

    public function offers(){
        return $this->belongsToMany(Offer::class)->using(OfferPurchase::class)
        ->withPivot('status', 'created_at', 'purchased_at', 'done_at');
    }

    public function products(){
        return $this->belongsToMany(Product::class)->using(ProductPurchase::class)
        ->withPivot('serial_number', 'tanggal_beli', 'masa_garansi', 'purchase_location_id', 'tanggal_selesai')
        ->withTimestamps();
    }
}
