<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoeswtProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'deskripsi_produk',
        'foto_produk',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
