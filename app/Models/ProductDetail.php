<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_manufacture_id',
        'spesifikasi',
        'foto_produk',
    ];

    public function products(){
        return $this->belongsTo('App\Models\Product');
    }

    public function manufacture(){
        return $this->belongsTo('App\Models\ProductManufacture', 'product_manufacture_id');
    }
}
