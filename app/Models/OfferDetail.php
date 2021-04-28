<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;
use App\Models\Product;

class OfferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'product_id',
        'qty',
        'harga',
        // 'disc'
    ];

    // public function offer(){
    //     return $this->belongsTo(Offer::Class);
    // }

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
