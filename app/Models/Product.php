<?php

namespace App\Models;

// use App\Models\OfferDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_produk',
        'product_type_id',
        'status',
        'harga_satuan',
    ];

    public function detail(){
        return $this->hasOne('App\Models\ProductDetail');
    }

    public function type(){
        return $this->belongsTo('App\Models\ProductType', 'product_type_id');
    }

    public function offers(){
        return $this->belongsToMany('App\Models\Offer')
        ->withPivot('qty', 'harga')
        ->withTimestamps();
    }


}
