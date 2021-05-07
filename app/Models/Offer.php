<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\OfferPurchase;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pembeli',
        'total_biaya',
    ];

    public function products(){
        return $this->belongsToMany(Product::class)
        ->withPivot('qty', 'harga')
        ->withTimestamps();
    }

    public function purchases(){
        return $this->belongsToMany(Purchase::class)
        // ->using(OfferPurchase::class)
        ->withPivot('status', 'created_at', 'purchase_at', 'done_at');
    }
}
