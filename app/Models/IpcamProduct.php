<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpcamProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'model_produk',
        'max_resolution',
        'lens',
        'wdr',
        'form_factor',
        'protection',
        'deskripsi_produk',
        'foto_produk'
    ];

    public function product(){
        return $this->belongsTo('Product');
    }
}
